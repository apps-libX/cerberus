<?php
/**
 * SessionController.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:59.
 */

namespace Cerberus\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Cerberus\FormRequests\LoginRequest;
use Cerberus\Repositories\Session\CerberusSessionRepositoryInterface;
use Cerberus\Traits\CerberusRedirectionTrait;
use Cerberus\Traits\CerberusViewfinderTrait;
use Sentry;
use View;
use Input;
use Event;
use Redirect;
use Session;
use Config;

class SessionController extends BaseController
{
    /**
     * Traits
     */
    use CerberusRedirectionTrait;
    use CerberusViewfinderTrait;

    /**
     * Constructor
     */
    public function __construct(CerberusSessionRepositoryInterface $sessionManager)
    {
        $this->session = $sessionManager;
    }

    /**
     * Show the login form
     */
    public function create()
    {
        // Is this user already signed in?
        if (Sentry::check()) {
            return $this->redirectTo('session_store');
        }

        // No - they are not signed in.  Show the login form.
        return $this->viewFinder('Cerberus::sessions.login');
    }

    /**
     * Attempt authenticate a user.
     *
     * @return Response
     */
    public function store(LoginRequest $request)
    {
        // Gather the input
        $data = Input::all();

        // Attempt the login
        $result = $this->session->store($data);

        // Did it work?
        if ($result->isSuccessful()) {
            // Login was successful.  Determine where we should go now.
            if (!config('cerberus.views_enabled')) {
                // Views are disabled - return json instead
                return Response::json('success', 200);
            }
            // Views are enabled, so go to the determined route
            $redirect_route = config('cerberus.routing.session_store');

            return Redirect::intended($this->generateUrl($redirect_route));
        } else {
            // There was a problem - unrelated to Form validation.
            if (!config('cerberus.views_enabled')) {
                // Views are disabled - return json instead
                return Response::json($result->getMessage(), 400);
            }
            Session::flash('error', $result->getMessage());

            return Redirect::route('cerberus.session.create')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        $this->session->destroy();

        return $this->redirectTo('session_destroy');
    }
}
