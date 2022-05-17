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

            $query->where('attribute_id',23);
//            foreach ($request->except($this->requestException) as $input => $value) {
//                $query->whereIn($input, array_map('trim', explode(',', $value)));
//            }

//            if($key = $request->input('search')){
//                $query->where('admin_name','like', '%'.$key.'%');
//                //todo search in translations
//            }
//
//            if ($sort = $request->input('sort')) {
//                $query->orderBy($sort, $request->input('order') ?? 'desc');
//            } else {
//                $query->orderBy('id', 'desc');
//            }
//
//            if($category = $request->input('category')){
//                $query->join('product_attribute_values','product_attribute_values.integer_value','=','attribute_options.id')
//                    ->join('product_categories','product_categories.product_id','=','product_attribute_values.product_id')
//                    ->where('product_attribute_values.attribute_id','attribute_options.attribute_id')
//                    ->where('product_categories.category_id',$category);
//            }
            return $query;
        });

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            $results = $query->paginate($request->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->getResourceCollection($results);
    }

    public function index($attribute_id){
        return  $this->getRepositoryInstance()->findWhere(['attribute_id'=>$attribute_id]);
    }
}