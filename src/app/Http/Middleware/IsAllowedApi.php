<?php

namespace Thinmartian\Cms\App\Http\Middleware;

use Closure;
//use App\Cms\CmsMedium;
use Thinmartian\Cms\App\Models\Core\CmsApiKeys;

class IsAllowedApi
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
        // key is in db
        if (!empty($request->key) && CmsApiKeys::where('key', $request->key)->first()) return $next($request);

        // key is not in db
        abort(403, 'Access denied');
    }
}
