<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Pagination\Paginator;

class ApiController extends Controller
{
    public function get($id = null, $model = null) {
        if (class_exists($model)) {
            $model = $model::find($id);
            return $model;
        }
    }

    public function getAll($model = null) {
        if (class_exists($model)) {
            $model = $model::paginate(10)->getCollection();
            return $model;
        }
    }
}