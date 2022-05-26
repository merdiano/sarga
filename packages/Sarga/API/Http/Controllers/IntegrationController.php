<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Core\Contracts\Validations\Slug;
use Sarga\Shop\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class IntegrationController extends Controller
{

    public function __construct(protected ProductRepository $productRepository,
                                protected SellerRepository $sellerRepository){}

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

    public function create(){

        try {
            $data = json_decode(request()->getContent(),true);
            if(!$data){
                return response()->json(['message'=>'data not found'],405);
            }
        }
        catch (\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['errors'=>$e->getMessage()],400);
        }

        $validation = Validator::make($data, [
            'categories' => 'required',
//            'sku' => ['required', 'unique:products,sku', new Slug],
            'images' => 'required',
            'name' => 'required',
            'url_key'=> 'required',
            'price' => 'required',
            'vendor' => 'required',
            'weight' => 'required'
        ]);

        if ($validation->fails()) {
            Log::info($data);

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        if($product = $this->productRepository->findOneByField('sku',$data['product_group_id']))
        {//product_group_id
            return response()->json(['success'=>true,'product_id' => $product->id]);
        }
        elseif($product = $this->productRepository->createProduct($data)){

            return response()->json(['success'=>true,'product_id' => $product->id]);
        }else{

            return response()->json(['success'=>false],400);
        }

    }

    public function update(){
        try {
            $data = json_decode(request()->getContent(),true);
        }
        catch (\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['errors'=>$e->getMessage()],400);
        }

        if(! $product = $this->productRepository->findOneByField('sku',$data['product_group_id'])){
            return response()->json(['success'=> false,'message' => 'product not found'],404);
        }

        if($this->productRepository->updateProduct($product,$data)){
            return response()->json(['success'=>true,'product_id' => $product->id]);
        }
    }

    public function updateOrderStatus(){
        Log::info(request()->input());
    }

}