<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Core\Contracts\Validations\Slug;
use Sarga\API\Repositories\ProductRepository;

class IntegrationController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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


    public function bulk_upload(){

        $products = json_decode(request()->getContent());
        Storage::put('scrap/products' . time() . '.txt', request()->getContent());

        foreach ($products as $product){


        }

    }

    public function create(){

        try {
            $data = json_decode(request()->getContent(),true);
        }
        catch (\Exception $e){
            return response()->json(['errors'=>$e->getMessage()],400);
        }

        $validation = Validator::make($data, [
//            'category' => 'required',
            'product_code' => ['required', 'unique:products,sku', new Slug],
            'images' => 'required',
            'name' => 'required',
            'url_key'=> 'required',
            'price' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        if($id = $this->productRepository->create($data)){
            return response()->json(['success'=>true,'product_id' => $id]);
        }else{
            return response()->json(['success'=>false]);
        }


    }

}