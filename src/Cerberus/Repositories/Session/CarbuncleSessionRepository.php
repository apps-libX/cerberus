<?php
/**
 * CarbuncleSessionRepository.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:30.
 */

namespace Cerberus\Repositories\Session;

use Config;
use Illuminate\Events\Dispatcher;
use Einherjars\Carbuncle\Carbuncle;
use Einherjars\Carbuncle\Throttling\UserBannedException;
use Einherjars\Carbuncle\Throttling\UserSuspendedException;
use Einherjars\Carbuncle\Users\UserNotActivatedException;
use Einherjars\Carbuncle\Users\UserNotFoundException;
use Einherjars\Carbuncle\Users\LoginRequiredException;
use Einherjars\Carbuncle\Users\PasswordRequiredException;
use Einherjars\Carbuncle\Users\WrongPasswordException;
use Cerberus\DataTransferObjects\BaseResponse;
use Cerberus\DataTransferObjects\ExceptionResponse;
use Cerberus\DataTransferObjects\SuccessResponse;
use Cerberus\DataTransferObjects\FailureResponse;

class CarbuncleSessionRepository implements CerberusSessionRepositoryInterface
{
    private $carbuncle;
    private $carbuncleThrottleProvider;
    private $carbuncleUserProvider;
    private $dispatcher;

    public function __construct(Carbuncle $carbuncle, Dispatcher $dispatcher)
    {
        // Carbuncle Singleton Object
        $this->carbuncle     = $carbuncle;
        $this->dispatcher = $dispatcher;

        // Get the Throttle Provider
        $this->carbuncleThrottleProvider = $this->carbuncle->getThrottleProvider();

        // Enable the Throttling Feature
        $this->carbuncleThrottleProvider->enable();

        // Get the user provider
        $this->carbuncleUserProvider = $this->carbuncle->getUserProvider();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return BaseResponse
     */
    public function store($data)
    {
        try {
            // Check for 'rememberMe' in POST data
            $rememberMe = isset($data['rememberMe']);

            // Set login credentials
            $credentials['password'] = e($data['password']);
            $credentials['email']    = isset($data['email']) ? e($data['email']) : '';

            // Should we check for a username?
            if (Config::get('Cerberus::auth.allow_usernames', false) && isset($data['username'])) {
                $credentials['username'] = e($data['username']);
            }

            // If the email address is blank or not valid, try using the username as the primary login credential
            if (!$this->validEmail($credentials['email'])) {
                // Tell carbuncle to look for a username when attempting login
                $this->carbuncleUserProvider->getEmptyUser()->setLoginAttributeName('username');

                // Remove the email credential
                unset($credentials['email']);

                // Set the 'username' credential
                $credentials['username'] = (isset($credentials['username']) ? $credentials['username'] : e($data['email']));
            }

            //Check for suspension or banned status
            $user     = $this->carbuncleUserProvider->findByCredentials($credentials);
            $throttle = $this->carbuncleThrottleProvider->findByUserId($user->id);
            $throttle->check();

            // Try to authenticate the user
            $user = $this->carbuncle->authenticate($credentials, $rememberMe);

            // Might be unnecessary, but just in case:
            $this->carbuncleUserProvider->getEmptyUser()->setLoginAttributeName('email');

            // Login was successful. Fire the Cerberus.user.login event
            $this->dispatcher->fire('cerberus.user.login', ['user' => $user]);

            // Return Response Object
            return new SuccessResponse('');
        } catch (WrongPasswordException $e) {
            $message = trans('Cerberus::sessions.invalid');
            $this->recordLoginAttempt($credentials);
            return new ExceptionResponse($message);
        } catch (UserNotFoundException $e) {
            $message = trans('Cerberus::sessions.invalid');
            return new ExceptionResponse($message);
        } catch (UserNotActivatedException $e) {
            $url = route('cerberus.reactivate.form');
            $this->recordLoginAttempt($credentials);
            $message = trans('Cerberus::sessions.notactive', array('url' => $url));
            return new ExceptionResponse($message);
        } catch (UserSuspendedException $e) {
            $message = trans('Cerberus::sessions.suspended');
            $this->recordLoginAttempt($credentials);
            return new ExceptionResponse($message);
        } catch (UserBannedException $e) {
            $message = trans('Cerberus::sessions.banned');
            $this->recordLoginAttempt($credentials);
            return new ExceptionResponse($message);
        }
    }

    /**
     * Log the current user out and destroy their session
     *
     * @param  int $id
     *
     * @return BaseResponse
     */
    public function destroy()
    {
        // Fire the Cerberus User Logout event
        $user = $this->carbuncle->getUser();
        $this->dispatcher->fire('cerberus.user.logout', ['user' => $user]);

        // Destroy the user's session and log them out
        $this->carbuncle->logout();

        return new SuccessResponse('');
    }

    /**
     * Record a login attempt to the throttle table.  This only works if the login attempt was
     * made against a valid user object.
     *
     * @param $credentials
     */
    private function recordLoginAttempt($credentials)
    {
        if (array_key_exists('email', $credentials)) {
            $throttle = $this->carbuncle->findThrottlerByUserLogin(
                $credentials['email'],
                \Request::ip()
            );
        }

        if (array_key_exists('username', $credentials)) {
            $this->carbuncleUserProvider->getEmptyUser()->setLoginAttributeName('username');
            $throttle = $this->carbuncle->findThrottlerByUserLogin(
                $credentials['username'],
                \Request::ip()
            );
        }

        if (isset($throttle)) {
            $throttle->ip_address = \Request::ip();

            $throttle->addLoginAttempt();
        }
    }

    /**
     * Validate an email address
     * http://stackoverflow.com/questions/12026842/how-to-validate-an-email-address-in-php
     */
    private function validEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
