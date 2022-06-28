<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Core\Contracts\Validations\Slug;
use Sarga\Shop\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;

class IntegrationController extends Controller
{

    public function __construct(protected ProductRepository $productRepository,
                                protected ProductAttributeValueRepository $attributeValueRepository,
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
//            Log::info($data);

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        if($product = $this->productRepository->findOneByField('sku',$data['product_group_id']))
        {//product_group_id
            Log::info($data);
            $this->updateVariants($product,$data);
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

    private function updateVariants($product,$data) {
        try{
            DB::beginTransaction();
            if($product->type == 'configurable'){
                if (!empty($data['color_variants'])) {
                    foreach ($data['color_variants'] as $colorVariant) {
                        $description = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $colorVariant['descriptions']));
                        if (!empty($colorVariant['size_variants']))
                            foreach ($colorVariant['size_variants'] as $sizeVariant) {
                                $sku = "{$data['product_group_id']}-{$colorVariant['product_number']}-{$sizeVariant['itemNumber']}";
                                if($variant = $this->productRepository->findOneByField('sku',$sku ))
                                    $this->updateAttribute($variant,$sizeVariant);
                                else{
                                    $variant = $this->productRepository->createVariant($product,$sku);
                                    $this->productRepository->assignImages($variant,$colorVariant['images']);
                                    $attributes = [
                                        'sku' => $variant->sku,
                                        'product_number' => "{$colorVariant['product_number']}-{$sizeVariant['itemNumber']}",
                                        'color' => $this->productRepository->getAttributeOptionId('color', $colorVariant['color']),
                                        'name' => $colorVariant['name'],
                                        'size' => $this->productRepository->getAttributeOptionId('size', $sizeVariant['attributeValue']),
//                                        'price' => $sizeVariant['price'],
                                        'weight' => $colorVariant['weight'] ?? 0.45,
                                        'status' => 1,
                                        'visible_individually' => 1,
                                        'url_key' => $variant->sku,
                                        'source' => $colorVariant['url_key'],
                                        'description' => $description,
                                        'short_description' => $description,
                                        'favoritesCount' => $colorVariant['favorite_count']
                                    ];
                                    $this->productRepository->assignAttributes($variant, array_merge($attributes,$this->productRepository->calculatePrice($sizeVariant['price'])));
                                }
                            }
                        elseif($variant = $this->productRepository->findOneByField('sku', "{$data['product_group_id']}-{$colorVariant['product_number']}"))
                        {
                            $this->updateAttribute($variant,$colorVariant);
                        }
                        else{
                            $variant = $this->productRepository->createVariant($product,"{$data['product_group_id']}-{$colorVariant['product_number']}");
                            $this->productRepository->assignImages($variant,$colorVariant['images']);
                            $desc = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']));
                            $attributes = [
                                'sku' => $variant->sku,
                                'product_number' => $colorVariant['product_number'],
                                'color' => $this->productRepository->getAttributeOptionId('color', $colorVariant['color']),
                                'name' => $colorVariant['name'],
//                                'price' => Arr::get($colorVariant, 'price.discountedPrice.value'),
                                'weight' => $colorVariant['weight'] ?? 0.45,
                                'status' => 1,
                                'visible_individually' => 1,
                                'url_key' => $variant->sku,
                                'source' => $colorVariant['url_key'],
                                'description' => $description,
                                'short_description' => $description,
                                'favoritesCount' => $colorVariant['favorite_count']
                            ];

                            $this->assignAttributes($variant, array_merge($attributes,$this->productRepository->calculatePrice($colorVariant['price'])));
                        }
                    }

                }
                elseif (!empty($data['size_variants'])){
                    foreach ($data['size_variants'] as $sizeVariant) {
                        $sku = "{$data['product_group_id']}-{$data['product_number']}-{$sizeVariant['itemNumber']}";
                        if($variant = $this->productRepository->findOneByField('sku', $sku)){
                            $this->updateAttribute($variant,$sizeVariant);

                        }else{
                            $variant = $this->productRepository->createVariant($product,$sku);
                            $this->productRepository->assignImages($variant,$data['images']);

                            $desc = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']));
                            $attributes = [
                                'sku' => $variant->sku,
                                'size' => $this->productRepository->getAttributeOptionId('size', $sizeVariant['attributeValue']),
                                'product_number' => "{$data['product_number']}-{$sizeVariant['itemNumber']}",
                                'name' => $data['name'],
//                                'price' => $sizeVariant['price'],
                                'weight' => $data['weight'] ?? 0.45,
                                'status' => 1,
                                'featured'=> 0,
                                'new' => 0,
                                'visible_individually' => 1,
                                'url_key' => $variant->sku,
                                'source' => $data['url_key'],
                                'description' => $desc,
                                'short_description' => $desc,
                                'favoritesCount' => $data['favorite_count']
                            ];

                            if (!empty($data['color'])) {
                                $attributes['color'] = $this->productRepository->getAttributeOptionId('color', $data['color']);
                            }

                            $this->assignAttributes($variant, array_merge($attributes,$this->productRepository->calculatePrice($sizeVariant['price'])));
                        }
                    }
                }
            }else if($product->type == 'simple'){
                $this->updateAttribute($product,$data);
            }
            Event::dispatch('catalog.product.update.after', $product);
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollBack();
            Log::error($ex);
//            Log::info($data);
            return false;
        }
    }

    private function updateAttribute($product,$data){

        if(isset($data['is_sellable']) && !$data['is_sellable']){
            Log::info($data);
            //$attribute = $this->attributeRepository->findOneByField('code', 'status'); status id = 8
            $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>8],['boolean_value'=>0]);

        }else{
            $originalPrice = Arr::get($data, 'price.originalPrice.value');
            $discountedPrice = Arr::get($data, 'price.discountedPrice.value');
            $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>8],['boolean_value'=>1]);
            if($discountedPrice >= $originalPrice){
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>11],['float_value'=>$discountedPrice]);// price id 11
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>13],['float_value'=>null]);//special price id 13
            }else{
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>11],['float_value'=>$originalPrice]);// price id 11
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>13],['float_value'=>$discountedPrice]);//special price id 13
            }
        }
    }

    private function createVariant($variant){

    }
    public function updateOrderStatus(){
        Log::info(request()->input());
        //todo update order status,
    }

}