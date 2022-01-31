<?php

namespace Sarga\API\Http\Controllers;

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
            $data =[];
            $distinctVariants =  $variants->unique($attribute->code);//->only([$attribute_main->code]);

            $gr_data = array('attribute' => SuperAttribute::make($attribute),'options' =>[]);

//            return $attribute->options->whereIn('id',$distinctVariants->pluck($attribute->code)->toArray());
            foreach($distinctVariants as $variant){
                $option = $attribute->options->where('id',$variant->{$attribute->code})->first();
                $item = [
                    'option' => $option->admin_name,
                    'images' => $variant->images,
                ];

                if($product->super_attributes->count()>1 && $option){
                    $last_attribute = $product->super_attributes->last();
                    $item['variants']['attribute'] = SuperAttribute::make($last_attribute);
                    $item['variants']['products'] = ProductResource::collection($variants->where($attribute->code,$variant->{$attribute->code}),$last_attribute);
                }
                else{
                    $item['product'] = ProductVariant::make($variants);
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
