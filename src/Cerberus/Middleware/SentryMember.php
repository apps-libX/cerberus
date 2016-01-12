<?php
/**
 * SentryMember.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:38.
 */

namespace Cerberus\Middleware;

use Closure;
use Sentry;
use Session;

class SentryMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $group
     * @return mixed
     */
    public function handle($request, Closure $next, $group)
    {
        if (!Sentry::check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('cerberus.login'));
            }
        }

        // Find the specified group
        $group = Sentry::findGroupByName($group);

        // Now check to see if the current user is a member of the specified group
        if (!Sentry::getUser()->inGroup($group)) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                Session::flash('error', trans('Cerberus::users.noaccess'));

                return redirect()->route('cerberus.login');
            }
        }

        return $next($request);
    }
}
