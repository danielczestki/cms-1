<?php

namespace Thinmartian\Cms\App\Http\Middleware;

use Closure;

class AllowedMediaType
{
    /**
     * Proceed ONLY if the media type in the url (?type) is "enabled" in the config
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $media = app()->make("Thinmartian\Cms\App\Services\Media\Media");
        if (! $media->isValidMediaType($type = $request->get("type"))) {
            return redirect()->route("admin.media.type")->withError("Sorry, the media type {$type} is not allowed");
        }
        return $next($request);
    }
}
