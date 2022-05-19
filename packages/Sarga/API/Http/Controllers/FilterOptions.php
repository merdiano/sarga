<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Sarga\API\Http\Resources\Catalog\AttributeOption;
use Sarga\Shop\Repositories\AttributeOptionRepository;

class FilterOptions extends \Webkul\RestApi\Http\Controllers\V1\Shop\ResourceController
{
    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token','locale','search','category'];
    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return AttributeOptionRepository::class;
    }
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return AttributeOption::class;
    }

    /**
     * Is resource authorized.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return false;
    }

    public function allResources(Request $request)
    {
        $query = $this->getRepositoryInstance()->scopeQuery(function ($query) use ($request) {

            return $query->where('attribute_options.attribute_id',$request->get('attribute_id'));
        });

        if($request->has('category')){
//                $query->join('product_attribute_values',function ($q){
//                    $q->on('product_attribute_values.integer_value','=','attribute_options.id')
//                        ->where('product_attribute_values.attribute_id',request()->get('attribute_id'))
//                        ->whereNotNull('integer_value')
//                        ->join('product_categories',function ($q) {
//                            $q->on('product_categories.product_id','=','product_attribute_values.product_id')
//                                ->where('product_categories.category_id',request()->get('category'));
//                        });
//                });

            $query->whereIn('id',function ($q) {
                $q->select('integer_value')
                    ->from('product_attribute_values')
                    ->whereNotNull('product_attribute_values.integer_value')
                    ->join('product_categories',function ($q) {
                        $q->on('product_categories.product_id','=','product_attribute_values.product_id')
                            ->where('product_categories.category_id',request()->get('category'));
                    });
            });
        }

        //Log::info($query->toSql());

        if($key = $request->get('search')){
            $query->where('admin_name','LIKE', '%'.$key.'%');
        }

        if ($sort = $request->input('sort')) {
            $query->orderBy($sort, $request->input('order') ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            $results = $query->paginate($request->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->getResourceCollection($results);
    }
}