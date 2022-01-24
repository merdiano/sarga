<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Sarga\API\Http\Resources\Core\Vendor;
use Sarga\API\Repositories\ProductRepository;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class Vendors extends Controller
{

    protected $vendorRepository;
    protected $categoryRepository;
    protected $productRepository;

    public function __construct(SellerRepository $sellerRepository,
                                ProductRepository $productRepository,
                                CategoryRepository $categoryRepository)
    {
        $this->vendorRepository = $sellerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $vendors = $this->vendorRepository->select('marketplace_sellers.id','url','logo','banner','shop_title','brand_attribute_id')
            ->where('is_approved',true)
            ->with(['categories:seller_id,type,categories'])
//            ->leftJoin('seller_categories','marketplace_sellers.id','=','seller_categories.seller_id')
            ->get();

//        foreach ($vendors as $vendor){
//            if($vendor->categories && $mainCats = $vendor->categories->where('type','main')->first()){
//                $cat_ids = json_decode($mainCats->categories,true);
////                $vendor->test = Category::collection($this->categoryRepository->getVisibleCategoryTree($cat_ids[0]));
//                $vendor->main_categories = $this->categoryRepository->whereIn('id',$cat_ids)
//                    ->select('id','image','position','parent_id','display_mode','category_icon_path')
//                    ->where('status',1)
//                    ->with(['children'=> function($q){
//                        $q->orderBy('position','asc');
//                    }])
//                    ->orderBy('position','asc')
//                    ->get();
//                if($vendor->main_categories->count()){
//                    foreach($vendor->main_categories as $category){
//                        $category->filters = app(ProductFlatRepository::class)->getProductsRelatedFilterableAttributes($category);
//                    }
//                }
//
//            }
//        }
        return Vendor::collection($vendors);
    }

    public function vendor_products($vendor_id){
        return ProductResource::collection($this->productRepository->findAllBySeller($vendor_id,request()->input('category_id')));
    }
}