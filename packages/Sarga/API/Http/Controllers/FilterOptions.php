<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
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

//            foreach ($request->except($this->requestException) as $input => $value) {
//                $query->whereIn($input, array_map('trim', explode(',', $value)));
//            }

            if ($sort = $request->input('sort')) {
                $query->orderBy($sort, $request->input('order') ?? 'desc');
            } else {
                $query->orderBy('id', 'desc');
            }

            if($category = $request->input('category')){
                $query->rightJoin('product_attribute_values',function ($q) use ($request){
                    $q->on('product_attribute_values.integer_value','=','attribute_options.id')
                        ->where('product_attribute_values.attribute_id',$request->get('attribute_id'));
                })->leftJoin('product_categories',function ($q) use($category){
                    $q->on('product_categories.product_id','=','product_attribute_values.product_id')
                        ->where('product_categories.category_id',$category);
                });
            }
            return $query->where('attribute_options.attribute_id',$request->get('attribute_id'));
        });

        if($key = $request->get('search')){
            $query->where('admin_name','LIKE', '%'.$key.'%');
        }

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            $results = $query->paginate($request->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->getResourceCollection($results);
    }
}