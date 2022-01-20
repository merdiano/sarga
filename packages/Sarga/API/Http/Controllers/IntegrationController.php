<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Core\Contracts\Validations\Slug;
use Sarga\API\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class IntegrationController extends Controller
{
    protected $productRepository;
    protected $sellerRepository;

    public function __construct(ProductRepository $productRepository, SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
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

        Log::info($data);

        $validation = Validator::make($data, [
            'category' => 'required',
            'product_code' => ['required', 'unique:products,sku', new Slug],
            'images' => 'required',
            'name' => 'required',
            'url_key'=> 'required',
            'price' => 'required',
            'vendor' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        if($product = $this->productRepository->create($data)){
            $seller = $this->sellerRepository->findOneByField('shop_title',$data['vendor']);
            if($seller){
                $sellerProduct = $this->productRepository->createSellerProduct($product, $seller->id);
            }
            return response()->json(['success'=>true,'product_id' => $product->id]);
        }else{
            return response()->json(['success'=>false]);
        }

    }

}