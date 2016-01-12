<?php
/**
 * SentryAdminAccess.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:37.
 */

namespace Cerberus\Middleware;

use Closure;
use Session;
use Sentry;
use Illuminate\Contracts\Routing\Middleware;

class SentryAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // First make sure there is an active session
        if (!Sentry::check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('cerberus.login'));
            }
        }

        // Now check to see if the current user has the 'admin' permission
        if (!Sentry::getUser()->hasAccess('admin')) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                Session::flash('error', trans('Cerberus::users.noaccess'));

                return redirect()->route('cerberus.login');
            }
        }

        // All clear - we are good to move forward
        return $next($request);
    }
}
