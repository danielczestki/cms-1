<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use App\User;
use App\Http\Controllers\Controller;

use Thinmartian\Cms\App\Models\Core\CmsApiKeys;

use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $fields = [
                            ['type' => 'text',
                             'label' => 'Label',
                             'persist' => 1,
                             //'value' => '',
                             'name' => 'label'],
                            /*['type' => 'text',
                             'label' => 'Key',
                             'persist' => 1,
                             //'value' => '',
                             'name' => 'key'],*/
                        ];

    protected $columns = ['id' => [
                                    'label' => 'ID',
                                    'sortable' => 1,
                                    'name' => 'id',
                                    'type' => 'number'
                                  ],
                          'label' => [
                                       'label' => 'Label',
                                       'sortable' => 1,
                                       'name' => 'label',
                                       'type' => 'text'
                                     ],
                          'key' => [
                                      'label' => 'Key',
                                      'sortable' => 1,
                                      'name' => 'key',
                                      'type' => 'text'
                                   ],
                          'created_at' => [
                                             'label' => 'Date created',
                                             'sortable' => 1,
                                             'name' => 'created_at',
                                             'type' => 'datetime'
                                          ],
                          'created_at' => [
                                             'label' => 'Date updated',
                                             'sortable' => 1,
                                             'name' => 'updated_at',
                                             'type' => 'datetime'
                                          ],
                         ];

    public function index()
    {
        return view("cms::admin.api.index", [
            'title' => 'API Keys',
            'controller' => 'Core\ApiController',
            'filters' => [],
            'listing' => CmsApiKeys::all(),
            'perpage' => 99,
            'columns' => $this->columns,
        ]);
    }

    public function edit($id)
    {
        return view("cms::admin.api.form", [
            'title' => 'API Keys',
            'subtitle' => 'Edit',
            'controller' => 'Core\ApiController',
            'filters' => [],
            'type' => 'edit',
            'resource' => CmsApiKeys::find($id),
            'fields' => $this->fields,
        ]);
    }

    public function create()
    {
        return view("cms::admin.api.form", [
            'title' => 'API Keys',
            'subtitle' => 'Create',
            'controller' => 'Core\ApiController',
            'filters' => [],
            'type' => 'create',
            'fields' => $this->fields,
        ]);
    }

    public function store(Request $request)
    {
        $key = new CmsApiKeys();
        $key->label = $request->label;
        $key->key = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 64)), 0, 64);
        $key->save();
        return \Redirect::to(url('admin/api'))->with('success', 'Key created successfully');
    }

    public function update($id, Request $request)
    {
        $key = CmsApiKeys::find($id);
        $key->label = $request->label;
        $key->save();
        return \Redirect::back()->with('success', 'Key updated successfully');
    }

    public function destroy(Request $request)
    {
        if ($request->_confirmed) {
            CmsApiKeys::whereIn('id', $request->ids)->delete();
            return \Redirect::to(url('admin/api'))->with('success', 'Keys deleted successfully');
        }
        return view("cms::admin.api.destroy", [
            'title' => 'API Keys',
            'subtitle' => 'Delete',
            'controller' => 'Core\ApiController',
            'filters' => [],
        ]);
    }

    public function get($id = null, $model = null)
    {
        if (class_exists($model)) {
            $model = $model::find($id);
            return $model;
        }
        return [];
    }

    public function getAll($model = null)
    {
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
