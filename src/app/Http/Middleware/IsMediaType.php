<?php

namespace Thinmartian\Cms\App\Http\Middleware;

use Closure;
use App\Cms\CmsMedium;

class IsMediaType
{
    /**
     * Confirm the cms_medium in question is a specifc media type
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $type = null)
    {
        //return redirect()->route("admin.media.index")->withError("You can only set the focal point on images");
        // We do have a id right?
        $cms_medium_id = $request->cms_medium_id ?: app()->abort(404);
        // Good, does it exist?
        $cms_medium = CmsMedium::find($cms_medium_id) ?: app()->abort(404);
        // Cool, is it the type we want?
        if ($cms_medium->type != $type) {
            return redirect()->route("admin.media.index")->withError("You can only set the focal point on images");
        }
        
        
        return $next($request);
    }
}
