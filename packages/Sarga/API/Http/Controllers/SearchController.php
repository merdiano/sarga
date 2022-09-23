<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Catalog\Suggestion;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\RestApi\Http\Controllers\V1\V1Controller;

class SearchController extends V1Controller
{
    public function __construct(protected BrandRepository $brandRepository,
                                protected ProductFlatRepository $productFlatRepository,
                                protected CategoryRepository $categoryRepository)
    {

    }

    public function index(){

        $key = request('search');

        if(!strlen($key)>= 3){
            return response()->json(['message' => '3 karakterden kuchuk','status'=>false]);
        }

        $queries = explode(' ', $key);

        $brands =$this->searchBrands($queries);

        $products = $this->searchProducts($queries);

        $categories = $this->searchCategories($queries);

        return Suggestion::collection($categories->merge($brands)->merge($products));

    }

    private function searchBrands($key){
        $brands = $this->brandRepository->search($key);

        if($brands->count()){
            $brands->flatMap(fn ($val) => $val['suggestion_type']='brand');
        }

        return $brands;
    }

    private function searchCategories(){
        $key = request('search');
        $categories = CategoryTranslationProxy::modelClass()::select('category_id as id','name','description')
            ->where('locale', core()->getRequestedLocaleCode())
            ->where('name', 'like', '%'.$key.'%')
            ->take(10)
            ->orderBy('name')
            ->get();

        if($categories->count()){
            $categories->flatMap(fn ($val) => $val['suggestion_type']='category');
        }

        return $categories;
    }

    private function searchProducts($key){

        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();
        $products = $this->productFlatRepository->getModel()::search(implode(' OR ', $key))
//            ->where('channel', $channel)
//            ->where('locale', $locale)
            ->take(100)
            ->query(fn ($query) => $query->select('id','name','product_id','description')
                ->where('status', 1)
                ->where('visible_individually', 1)
//                ->addSelect(DB::raw("\'product\' as type" ))
                ->orderBy('name'))
                ->take(10)
            ->get();

        if($products->count()){
            $products->map(function ($item,$key) {
                $item['suggestion_type'] = 'product';
                return $item;
            });

        }

        return $products;
    }
}