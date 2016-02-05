<?php
/**
 * CarbuncleAuth.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:37.
 */

namespace Cerberus\Middleware;

use Closure;
use Carbuncle;

class CarbuncleAuth
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
        if (!Carbuncle::check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                $request->session()->reflash();
                return redirect()->guest(route('cerberus.login'));
            }
        }

        return $next($request);
    }
}
