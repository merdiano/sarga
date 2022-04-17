<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Sarga\API\Http\Resources\Catalog\ProductVariant;
use Sarga\API\Http\Resources\Catalog\SuperAttribute;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\ProductRepository;
use Webkul\API\Http\Controllers\Shop\ProductController;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Sarga\Shop\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductFlatRepository;


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
        return ProductResource::collection($this->productRepository->getAll(request()->input('category_id')));
    }


    /**
     * Returns a individual resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        return  ($product = $this->productRepository->find($id))?
            new ProductResource($product) :
            response()->json(['error' => 'not found'],404);
    }

    public function variants($id)
    {
        $product = $this->productRepository->with(['super_attributes:id,code','variants'=>function($query)
        {
            $query->with(['images','product_flats' => function($qf)
            {
                $channel = core()->getRequestedChannelCode();

                $locale = core()->getRequestedLocaleCode();

                $qf->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key')
                    ->where('status',1);
            }]);
        }])->find($id);

        if(!empty($product) && $product->super_attributes->isNotEmpty() && $product->variants->isNotEmpty())
        {
            $variants = $product->variants->makeHidden(['type','created_at','updated_at','parent_id','attribute_family_id',
                'additional','new','featured','visible_individually','status','guest_checkout','meta_title','meta_keywords',
                'product_flats','attribute_family','short_description','sku','brand']);

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



    public function suggestions(BrandRepository $brandRepository){

        $key = request('search');

        if(!strlen($key)>= 3){
            return response()->json(['message' => '3 karakterden kuchuk','status'=>false]);
        }

        $queries = explode(' ', $key);

        $brands = $brandRepository->getModel()::search(implode(' OR ', $queries))
//            ->where('status',1)
//            ->orderBy('name','asc')
            ->take(10)
            ->get();
//        return $brands;

//        $products = $this->productRepository->searchProductByAttribute($key);

        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();
        $products = app(ProductFlatRepository::class)->getModel()::search(implode(' OR ', $queries))
            ->where('status', 1)
            ->where('visible_individually', 1)
            ->where('channel', $channel)
            ->where('locale', $locale)
            ->orderBy('product_id', 'desc')
            ->take(10)
            ->get('name');

        return $products;

        if($products->count() >0){

            return Arr::sort(($products->items()));
        }

    }

}
