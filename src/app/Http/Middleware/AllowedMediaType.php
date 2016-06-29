<?php

namespace Thinmartian\Cms\App\Http\Middleware;

use Closure, CmsImage;

class AllowedMediaType
{
    /**
     * Proceed ONLY if the media type in the url (?type) is "enabled" in the config
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $type = $request->get("type");
        if (! array_key_exists($type, CmsImage::getMediaTypes()) or ! CmsImage::getMediaTypes("{$type}.enabled")) {
            return redirect()->route("admin.media.type")->withError("Sorry, the media type {$type} is not allowed");
        }
        return $next($request);
    }
}
