<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;

class ApiController extends Controller
{
    public function get($id = null, $model = null) {
        if (class_exists($model)) {
            $model = $model::find($id);
            return $model;
        }
        return [];
    }

    public function getAll($model = null) {
        // does the model exist?
        if (class_exists($model)) {
            // Get all items you want
            $items = $model::get();

            // Get the current page from the url if it's not set default to 1
            $page = Input::get('page', 1);

            // Number of items per page
            $perPage = Input::get('amount', 10);

            // limit to maximum of 100 per page
            $perPage = $perPage > 100 ? 100 : $perPage;
            
            // Start displaying items from this number;
            $offSet = ($page * $perPage) - $perPage; // Start displaying items from this number

            // Get only the items you need using array_slice (only get 10 items since that's what you need)
            $itemsForCurrentPage = array_slice($items->toArray(), $offSet, $perPage, true);

            // Return the paginator with only 10 items but with the count of all items and set the it on the correct page
            return new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $page);
        }
        return [];
    }
}