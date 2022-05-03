<?php

namespace Sarga\API\Http\Controllers;

use App\Http\Controllers\Controller;
use Sarga\API\Http\Resources\Catalog\Suggestion;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Product\Repositories\ProductFlatRepository;

class SearchController extends Controller
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

        return Suggestion::collection($products->merge($brands)->merge($categories)->sortBy('name'));

    }

    private function searchBrands($key){
        $brands = $this->brandRepository->getModel()::search(implode(' OR ', $key))
//            ->where('status',1)
//            ->orderBy('name','asc')
            ->take(10)
            ->query(fn ($query) => $query->select('id','name')->orderBy('name'))
            ->get();

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
            ->where('status', 1)
            ->where('visible_individually', 1)
            ->where('channel', $channel)
            ->where('locale', $locale)
            ->take(10)
            ->query(fn ($query) => $query->select('id','name','product_id','description')
//                ->addSelect(DB::raw("\'product\' as type" ))
                ->orderBy('name'))
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