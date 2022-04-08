<?php

namespace Sarga\API\Http\Controllers;

use App\Http\Controllers\Controller;
use Sarga\API\Http\Resources\Catalog\Attribute;
use Sarga\API\Http\Resources\Catalog\Brand;
use Sarga\API\Http\Resources\Catalog\Category;
use Sarga\Shop\Repositories\CategoryRepository;


class Categories extends Controller
{

    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }
    public function index()
    {
        return Category::collection(
            $this->categoryRepository->getVisibleCategoryTree(request()->input('parent_id'))
        );
    }

    public function details($id){
        $children = $this->categoryRepository->findWhere(['parent_id' => $id, 'status'=>1])
            ->orderBy('position', 'ASC');

    }

    public function filters($id){
        $category = $this->categoryRepository->with(['filterableAttributes','children',
            'brands' => function ($q){
                $q->where('status',1);
            } ])
            ->find($id);

        if($category)
            return response([
                'subcategories' =>$category->children,
                'attributes' =>Attribute::collection($category->filterableAttributes),
                'brands' => Brand::collection($category->brands),
                ]);
        else{
            return response(['error'=>'not found'],404);
        }
    }

}