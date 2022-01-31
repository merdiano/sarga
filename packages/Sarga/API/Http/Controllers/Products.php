<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Sarga\API\Http\Resources\Catalog\Attribute;
use Sarga\API\Http\Resources\Catalog\AttributeOption;
use Sarga\API\Http\Resources\Catalog\ProductVariant;
use Sarga\API\Http\Resources\Catalog\SuperAttribute;
use Sarga\API\Repositories\ProductRepository;
use Webkul\API\Http\Controllers\Shop\ProductController;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Sarga\Shop\Repositories\AttributeOptionRepository;


class Products extends ProductController
{
    protected $attributeOptionRepository;

    public function __construct(ProductRepository $productRepository,
                                AttributeOptionRepository $attributeOptionRepository)
    {
        parent::__construct($productRepository);
        $this->attributeOptionRepository = $attributeOptionRepository;
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
        return new ProductResource(
            $this->productRepository->findOrFail($id)
        );
    }

    public function variants($id){

        $product = $this->productRepository->with(['super_attributes:id,code','variants'=>function($query){
//            $query->select('id','parent_id');
            $query->with(['images','product_flats' => function($qf){
                $channel = core()->getRequestedChannelCode();

                $locale = core()->getRequestedLocaleCode();
                $qf->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key')
                    ->where('status',1);
            }]);
        }])->find($id);
//        return $product->variants; //Attribute::make($product->super_attributes->first());
        if(!empty($product) && $product->super_attributes->isNotEmpty() && $product->variants->isNotEmpty()){

            $variants = $product->variants->makeHidden(['type','created_at','updated_at','parent_id','attribute_family_id',
                'additional','new','featured','visible_individually','status','guest_checkout','meta_title','meta_keywords',
                'product_flats','attribute_family','short_description','sku','brand']);

            $attribute = $product->super_attributes->first();
            $distinctVariants =  $variants->unique($attribute->code);//->only([$attribute_main->code]);

            $gr_data = array('attribute' => SuperAttribute::make($attribute),'options' =>[]);

            foreach($distinctVariants as $variant){
                $option = $attribute->options->firstWhere('id',$variant->{$attribute->code});

                $item = [
                    'option' => $option->admin_name,
                    'images' => $variant->images,
                ];

                if($product->super_attributes->count()>1 && $option){
                    $last_attribute = $product->super_attributes->last();

                    $products =  $variants->where($attribute->code,$variant->{$attribute->code})
                        ->map(function ($item,$key) use ($last_attribute){
                        $option = $last_attribute->options->where('id',$item->{$last_attribute->code})->first();

                        return ProductVariant::make($item,$option);
                    });

                    $item['variants']['attribute'] = SuperAttribute::make($last_attribute);
                    $item['variants']['products'] = $products->values();
                }
                else{
                    $item['product'] = ProductVariant::make($variant,$option);
                }
                $gr_data['options'][] = $item;
            }

            return response()->json($gr_data);
        }
        else{
            return response()->json(['message' => 'not found'],404);
        }

//        $variants =  $this->productRepository->variants($id);
//        return $variants;
//        return ProductResource::collection($this->productRepository->variants($id));
    }

}
