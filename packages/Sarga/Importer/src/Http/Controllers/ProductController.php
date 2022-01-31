<?php

namespace Sarga\Importer\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Sarga\API\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class ProductController extends Controller
{
    use ValidatesRequests;

    protected $productRepository;
    protected $sellerRepository;

    public function __construct(ProductRepository $productRepository, SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
        $this->productRepository = $productRepository;
    }

    public function create()
    {
        try {
            $data = json_decode(request()->getContent(),true);
        }
        catch (\Exception $e){
            Log::error($e);
            return response()->json(['errors'=>$e->getMessage()],400);
        }

        $validation = Validator::make($data, [
            'categories' => 'required',
//            'sku' => ['required', 'unique:products,sku', new Slug],
            'images' => 'required',
            'name' => 'required',
            'url_key'=> 'required',
            'price' => 'required',
            'vendor' => 'required'
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        if($product = $this->productRepository->findOneByField('sku',$data['sku']))
        {
            return response()->json(['success'=>true,'product_id' => $product->id]);
        }
        elseif($product = $this->productRepository->create($data))
        {
            return response()->json([
                'success'=>true,
                'product_id' => $product->id
            ]);
        }else
        {
            return response()->json(['success'=>false],400);
        }

    }

    public function update(){

    }
}