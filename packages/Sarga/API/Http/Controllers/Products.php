<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Repositories\ProductRepository;
use Webkul\API\Http\Controllers\Shop\ProductController;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;


class Products extends ProductController
{

    public function __construct(ProductRepository $productRepository)
    {
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
        return new ProductResource(
            $this->productRepository->findOrFail($id)
        );
    }

    public function variants($id){

        $product = $this->productRepository->with(['super_attributes','variants'=>function($query){
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
        return $product;
        $variants =  $this->productRepository->variants($id);
        return $variants;
//        return ProductResource::collection($this->productRepository->variants($id));
    }

}
