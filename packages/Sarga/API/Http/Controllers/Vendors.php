<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Catalog\Brand;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Sarga\API\Http\Resources\Core\Vendor;
use Sarga\Shop\Repositories\ProductRepository;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\CategoryRepository;
use Sarga\Shop\Repositories\VendorRepository;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class Vendors extends Controller
{

    public function __construct(VendorRepository $sellerRepository,
                                CategoryRepository $categoryRepository)
    {
        $this->vendorRepository = $sellerRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $vendors = $this->vendorRepository->select('marketplace_sellers.id','url','logo','banner','shop_title','brand_attribute_id')
            ->where('is_approved',true)
            ->with(['categories:seller_id,type,categories'])
//            ->leftJoin('seller_categories','marketplace_sellers.id','=','seller_categories.seller_id')
            ->get();

        foreach ($vendors as $vendor){
            if($vendor->categories && $mainCats = $vendor->categories->where('type','main')->first()){
                $cat_ids = json_decode($mainCats->categories,true);
//                $vendor->test = Category::collection($this->categoryRepository->getVisibleCategoryTree($cat_ids[0]));
                $vendor->main_categories = $this->categoryRepository->whereIn('id',$cat_ids)
                    ->select('id','image','position','parent_id','display_mode','category_icon_path')
                    ->where('status',1)
                    ->with(['children'=> function($q){
                        $q->orderBy('position','asc');
                    }])
                    ->orderBy('position','asc')
                    ->get();
//                if($vendor->main_categories->count()){
//                    foreach($vendor->main_categories as $category){
//                        $category->filters = app(ProductFlatRepository::class)->getProductsRelatedFilterableAttributes($category);
//                    }
//                }

            }
        }
//        return $vendors;
        return Vendor::collection($vendors);
    }

    public function products(ProductRepository $productRepository,$seller_id){
        $products = $productRepository->findAllBySeller($seller_id,request()->input('category_id'));

        return ProductResource::collection($products);
    }

    public function brands(BrandRepository $brandRepository, $seller_id){

        return Brand::collection($brandRepository->findAllBySeller($seller_id));
    }
}