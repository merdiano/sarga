<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Sarga\API\Http\Resources\Catalog\Attribute;
use Sarga\API\Http\Resources\Catalog\Brand;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\RestApi\Http\Controllers\V1\V1Controller;

class Filters extends V1Controller
{
    public function __construct(protected CategoryRepository $categoryRepository,
                                protected BrandRepository $brandRepository,
                                protected AttributeRepository $attributeRepository
    ){}

    public function index(Request $request){

        if($request->has('category')){

            $categories = $this->categoryRepository->with(['filterableAttributes','brands' => function ($q){
                $q->take(20);
            } ]);

            $categories->find($request->get('category'));

        }
    }

    public function filters($id){
        $category = $this->categoryRepository->with(['filterableAttributes','children',
            'brands' => function ($q){
                $q->where('status',1);
            } ])
            ->find($id);

        if($category)
            return response([
                'subcategories' => Category::collection($category->children),
                'attributes' => Attribute::collection($category->filterableAttributes),
                'brands' => Brand::collection($category->brands),
            ]);
        else{
            return response(['error'=>'not found'],404);
        }
    }
}