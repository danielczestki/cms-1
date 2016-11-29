<?php

namespace Thinmartian\Cms\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotPermitted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $filename = null)
    {
        $user = Auth::guard("cms")->user();
        $perms = $user->permissions;
        if ($filename === 'Users' && $user->access_level !== "Admin") {
            return redirect('/admin')->withError("You do not have permission to view this page");
        }
        if (! empty($perms)) {
            if (! in_array($filename, $perms)) {
                return redirect('/admin')->withError("You do not have permission to view this page");
            }
        }



        return $next($request);
    }
}
