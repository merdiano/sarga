<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Catalog\Attribute;
use Sarga\API\Http\Resources\Catalog\Brand;
use Sarga\API\Http\Resources\Catalog\Category as CategoryResource;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\RestApi\Http\Controllers\V1\Shop\Catalog\CategoryController;


class Categories extends CategoryController
{
//    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token','locale'];
    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return CategoryRepository::class;
    }
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return CategoryResource::class;
    }

    public function filters($id){
        $category = $this->categoryRepository->with(['filterableAttributes','brands' => function ($q){
                $q->take(20);
            } ])
            ->find($id);

        if($category)
            return response([
                'attributes' => Attribute::collection($category->filterableAttributes),
                'brands' => Brand::collection($category->brands),
                ]);
        else{
            return response(['error'=>'not found'],404);
        }
    }

}