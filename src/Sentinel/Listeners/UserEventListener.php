<?php
/**
 * UserEventListener.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:40.
 */

namespace Cerberus\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Config\Repository;

class UserEventListener
{
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('cerberus.user.login', 'Cerberus\Listeners\UserEventListener@onUserLogin', 10);
        $events->listen('cerberus.user.logout', 'Cerberus\Listeners\UserEventListener@onUserLogout', 10);
        $events->listen('cerberus.user.registered', 'Cerberus\Listeners\UserEventListener@welcome', 10);
        $events->listen('cerberus.user.resend', 'Cerberus\Listeners\UserEventListener@welcome', 10);
        $events->listen('cerberus.user.reset', 'Cerberus\Listeners\UserEventListener@passwordReset', 10);
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($user)
    {
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout()
    {
    }

    /**
     * Send a welcome email to a new user.
     *
     * @param $user
     * @param $activated
     *
     * @return bool
     * @internal param string $email
     * @internal param int $userId
     * @internal param string $activationCode
     */
    public function welcome($user, $activated)
    {
        $subject = $this->config->get('cerberus.subjects.welcome');
        $view = $this->config->get('cerberus.email.views.welcome', 'Cerberus::emails.welcome');
        $data['hash'] = $user->hash;
        $data['code'] = $user->getActivationCode();
        $data['email'] = $user->email;

        if (! $activated) {
            $this->sendTo($user->email, $subject, $view, $data);
        }
    }

    /**
     * Email Password Reset info to a user.
     *
     * @param $user
     * @param $code
     *
     * @internal param string $email
     * @internal param int $userId
     * @internal param string $resetCode
     */
    public function passwordReset($user, $code)
    {
        $subject = $this->config->get('cerberus.subjects.reset_password');
        $view = $this->config->get('cerberus.email.views.reset', 'Cerberus::emails.reset');
        $data['hash'] = $user->hash;
        $data['code'] = $code;
        $data['email'] = $user->email;

        $this->sendTo($user->email, $subject, $view, $data);
    }

    /**
     * Convenience function for sending mail
     *
     * @param $email
     * @param $subject
     * @param $view
     * @param array $data
     */
    private function sendTo($email, $subject, $view, $data = array())
    {
        $sender = $this->gatherSenderAddress();

        Mail::queue($view, $data, function ($message) use ($email, $subject, $sender) {
            $message->to($email)
                ->from($sender['address'], $sender['name'])
                ->subject($subject);
        });
    }

    /**
     * If the application does not have a valid "from" address configured, we should stub in
     * a temporary alternative so we have something to pass to the Mailer
     *
     * @return array|mixed
     */
    private function gatherSenderAddress()
    {
        $sender = config('mail.from', []);

        if (!array_key_exists('address', $sender) || is_null($sender['address'])) {
            return ['address' => 'noreply@example.com', 'name' => ''];
        }

        if (is_null($sender['name'])) {
            $sender['name'] = '';
        }

        return $sender;
    }
}
