<?php
/**
 * SentryGuest.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:37.
 */

namespace Cerberus\Middleware;

use Closure;
use Sentry;

class SentryGuest
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
        if (Sentry::check()) {
            $destination = config('cerberus.redirect_if_authenticated', 'home');
            return redirect()->route($destination);
        }

        return $next($request);
    }
}
