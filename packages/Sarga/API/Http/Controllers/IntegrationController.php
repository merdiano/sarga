<?php

namespace Sarga\API\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Core\Contracts\Validations\Slug;
use Webkul\Product\Repositories\ProductRepository;

class IntegrationController extends Controller
{
    protected $productRepository;
    protected $attributeFamilyRepository;

    public function __construct(ProductRepository $productRepository, AttributeFamilyRepository $attributeFamilyRepository)
    {
        $this->productRepository = $productRepository;
        $this->attributeFamilyRepository = $attributeFamilyRepository;
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

        $data = json_decode(request()->getContent(),true);

        $validation = Validator::make($data, [
            'type'                => 'required',
            'category' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new Slug],
            'images' => 'required',
            'name' => 'required',
            'url_key'=> 'required',
            'price' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->getMessageBag()->all());
        }

        $product['sku'] = $data['sku'];

        //todo test here add some attributes,families
        $product['attribute_family_id'] = $this->getAttributeFamily(array_keys($data['attributes']));

        $product['super_attributes']= [];

        $product['type'] = ($data->color_variants != null || $data->size_variants != null) ? 'configurable':'simple';

        //$this->productRepository->create($product);

    }

    //find attribute family
    private function getAttributeFamily($attrubetCodes){
        if($attrubetCodes)
            return $this->attributeFamilyRepository->whereHas('custom_attributes',function ($query) use ($attrubetCodes){
                $query->whereIn('attributes.code',  $attrubetCodes);
            },'=',count($attrubetCodes))->first()->id ?? 1;
        return 1; //default attribute family
    }
}