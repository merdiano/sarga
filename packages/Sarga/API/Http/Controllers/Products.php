<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Sarga\API\Http\Resources\Catalog\ProductDetail;
use Sarga\API\Http\Resources\Catalog\ProductVariant;
use Sarga\API\Http\Resources\Catalog\SuperAttribute;
use Sarga\Shop\Repositories\ProductRepository;
use Webkul\API\Http\Controllers\Shop\ProductController;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Sarga\Shop\Repositories\AttributeOptionRepository;



class Products extends ProductController
{
    protected $attributeOptionRepository;

    public function __construct(ProductRepository $productRepository,
                                AttributeOptionRepository $attributeOptionRepository)
    {
        $this->attributeOptionRepository = $attributeOptionRepository;

        parent::__construct($productRepository);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection($this->productRepository->getAll(request()->input('category')));
    }


    /**
     * Returns a individual resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $sku = request()->has('sku');

        if($sku){
            $product = $this->productRepository->findOneByField('sku',$id);
        }
        else{
            $product = $this->productRepository->find($id);
        }

        return  ($product)?
            new ProductResource($product) :
            response()->json(['error' => 'not found'],404);
    }

    public function product($id){
        $product = $this->productRepository->select('id','attribute_family_id','type','brand_id')
            ->with(['brand','related_products'=> fn($rp) => $rp->select('id','type','attribute_family_id','brand_id')->with('brand'),
                'variants' => function($query){
                $query->with(['product_flats' => function($qf){
                    $channel = core()->getRequestedChannelCode();

                    $locale = 'tm';//core()->getRequestedLocaleCode();

                    $qf->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
//                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.status',1);
                }]);
            }])->find($id);

//        return $product;

        return ProductDetail::make($product);

    }

    public function variants($id)
    {
        $product = $this->productRepository->with(['super_attributes:id,code','variants'=>function($query)
        {
            $query->with(['images','product_flats' => function($qf)
            {
                $channel = core()->getRequestedChannelCode();

                $locale = 'tm';//core()->getRequestedLocaleCode();

                $qf->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key')
                    ->where('product_flat.status',1);
            }]);
        }])->find($id);

//        Log::info($product->variants->map->only(['status']));

        if(!empty($product) && $product->super_attributes->isNotEmpty() && $product->variants->isNotEmpty())
        {
            $variants = $product->variants->where('status',1)->makeHidden(['type','created_at','updated_at','parent_id','attribute_family_id',
                'additional','new','featured','visible_individually','guest_checkout','meta_title','meta_keywords',
                'product_flats','attribute_family','short_description','sku','brand']);
//            Log::info($variants->map->only(['status']));
            $attribute = $product->super_attributes->first();

            $distinctVariants =  $variants->unique($attribute->code);

            $gr_data = array(
                'attribute' => SuperAttribute::make($attribute),
                'options' =>[],
                'level' => $product->super_attributes->count()
            );

            foreach($distinctVariants as $variant)
            {
                $option = $attribute->options->firstWhere('id',$variant->{$attribute->code});

                $item = [
                    'option' => $option->admin_name,
                    'images' => $variant->images,
                ];

                $attributes = $product->super_attributes;
                if($attributes->count()>1 && $option)
                {
                    $products = $variants->where($attribute->code,$variant->{$attribute->code})
                        ->map(function ($item,$key) use ($attributes){
                        return ProductVariant::make($item,$attributes);
                    });

                    $item['variants']['attribute'] = SuperAttribute::make($product->super_attributes->last());
                    $item['variants']['products'] = $products->values();
                }
                else
                {
                    $item['product'] = ProductVariant::make($variant,$attributes);
                }
                $gr_data['options'][] = $item;
            }
            return response()->json($gr_data);
        }

        return response()->json(['message' => 'not found'],404);
    }


    public function searchProducts(){
        return ProductResource::collection($this->productRepository->searchProductByAttribute(request('key')));
    }

    public function discountedProducts(){
        return ProductResource::collection($this->productRepository->getDiscountedProducts(request()->input('vendor'),request()->input('category')));
    }

    public function popularProducts(){
        return ProductResource::collection($this->productRepository->getPopularProducts(request()->input('category')));
    }

}
