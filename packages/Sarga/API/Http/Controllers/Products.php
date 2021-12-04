<?php

namespace Sarga\API\Http\Controllers;

use Webkul\API\Http\Controllers\Shop\ProductController;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Webkul\Core\Contracts\Validations\Slug;

class Products extends ProductController
{

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

    public function store(){
        if(!request()->has('product')){
            return response()->json(['status' =>false, 'message' => 'bad request'],405);
        }

        $product = json_decode(request('product'),true);

        $this->validate($product, [
            'sku'                 => ['required', 'unique:products,sku', new Slug],
        ]);

//        $product = $this->productRepository->create(request()-
        return $product;
    }



}
