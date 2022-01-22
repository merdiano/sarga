<?php

namespace Sarga\API\Http\Controllers;

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
//        return $product;
        if(!empty($product) && $product->super_attributes->isNotEmpty() && $product->variants->isNotEmpty()){

            $variants = $product->variants->makeHidden(['type','created_at','updated_at','parent_id','attribute_family_id',
                'additional','new','featured','visible_individually','status','guest_checkout','meta_title','meta_keywords','product_flats','attribute_family']);
            $data = array();

            $attribute_main = $product->super_attributes->first();

            if($product->super_attributes->count() > 1){

                $last_attribute = $product->super_attributes->last();
                foreach($variants as $variant){
                    $option = $this->attributeOptionRepository->getOptionLabel($variant->{$attribute_main->code});
                    $data[$attribute_main->code][$option]['image'] = $variant->images;
                    $data[$attribute_main->code][$option][$last_attribute->code][] = $variant->makeHidden(['type','created']);
                }
            }
            else{
                foreach($variants as $variant){
                    $option = $this->attributeOptionRepository->getOptionLabel($variant->{$attribute_main->code});

                    $data[$attribute_main->code][$option] = [
                        'product' => ProductResource::make($variant)
                    ];
                }
            }

            return response()->json($data);
        }
        else{
            return response()->json(['message' => 'not found'],404);
        }

//        $variants =  $this->productRepository->variants($id);
//        return $variants;
//        return ProductResource::collection($this->productRepository->variants($id));
    }

}
