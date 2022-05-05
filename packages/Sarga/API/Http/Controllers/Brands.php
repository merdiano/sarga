<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Sarga\API\Http\Resources\Catalog\Brand;
use Sarga\Brand\Repositories\BrandRepository;

class Brands extends \Webkul\RestApi\Http\Controllers\V1\Shop\ResourceController
{
    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token','locale','search','category'];

    /**
     * Is resource authorized.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return false;
    }

    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return BrandRepository::class;
    }

    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return Brand::class;
    }

    public function allResources(Request $request)
    {
        $query = $this->getRepositoryInstance()->scopeQuery(function ($query) use ($request) {

            foreach ($request->except($this->requestException) as $input => $value) {
                $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
            }

            if($key = $request->input('search')){
                $query = $query->where('name','like', '%'.$key.'%');
            }

            if($category = $request->input('category')){
                $query = $query->rightJoin('category_brands','brands.id','=','category_brands.brand_id')
                    ->where('category_brands.category_id',$category);
            }

            if ($sort = $request->input('sort')) {
                $query = $query->orderBy($sort, $request->input('order') ?? 'asc');
            } else {
                $query = $query->orderBy('id', 'asc');
            }

            return $query;
        });

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            $results = $query->paginate($request->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->getResourceCollection($results);
    }
}