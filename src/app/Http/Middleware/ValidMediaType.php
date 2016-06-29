<?php

namespace Thinmartian\Cms\App\Http\Middleware;

use Closure;

class ValidMediaType
{
    /**
     * Proceed only if the media type in the url (?type) is a valid type
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $request->get("type"))  {
            return redirect()->route("admin.media.type")->withError("Please select the media type you wish to upload");
        }
        return $next($request);
    }
}
