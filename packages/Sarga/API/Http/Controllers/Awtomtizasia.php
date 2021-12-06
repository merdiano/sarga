<?php

namespace Sarga\API\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Core\Contracts\Validations\Slug;

class Awtomtizasia extends Controller
{
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


    public function bulk_upload(){

        $content = json_decode(request()->getContent());
        Storage::put('scrap/products' . time() . '.txt', request()->getContent());

    }
}