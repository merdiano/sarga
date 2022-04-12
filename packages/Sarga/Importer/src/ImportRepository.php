<?php

namespace Sarga\Importer\src;

use Illuminate\Container\Container as Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\AttributeValueRepository;
use Sarga\Shop\Repositories\VendorRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class ImportRepository extends \Webkul\Core\Eloquent\Repository
{

    public function __construct(protected AttributeRepository $attributeRepository,
                                protected AttributeValueRepository $attributeValueRepository,
                                protected AttributeGroupRepository $attributeGroupRepo,
                                protected BrandRepository $brandRepository,
                                protected VendorRepository $vendorRepository,
                                Application $app)
    {
        parent::__construct($app);
    }

    /**
     * @inheritDoc
     */
    public function model()
    {
        return \Webkul\Product\Contracts\Product::class;
    }

    public function create($data){
//        $time_start = microtime(true);
        $attributes = Arr::only($data,['source','cinsiyet']);

        $product = [
            'sku'   => 'G-'.$data['product_group_id'],
            'type'  => (!empty($data['color_variants'])  || !empty($data['size_variants'])) ? 'configurable':'simple'
        ];

        if (array_key_exists('attributes', $data)) {
            $attributes [] = Arr::collapse($data['attributes']);

            $grp = $this->getAttributeFamily(array_keys($attributes));

            $product['attribute_family_id'] = $grp ? $grp->attribute_family_id :
                (($product['type'] == 'configurable') ? 2 : 1);//default_configurable_product: default_simple_prodcut
        } else
            $product['attribute_family_id'] = $product['type'] == 'configurable' ? 2 : 1;

        DB::beginTransaction();

        //brand
        if(!empty($data['brand']) &&
            $brand = $this->brandRepository->firstOrCreate([
                'name' => $data['brand'],
                'code' => Str::slug($data['brand']),
            ]))
        {
            $product['brand_id'] = $brand->id;
        }

        $parentProduct = $this->getModel()->create($product);

        //product categories
        if(!empty($data['categories'])){
            $parentProduct->categories()->attach($data['categories']);
        }

        //seller
        if($data['vendor'] && $seller = $this->vendorRepository->findOneByField('shop_title',$data['vendor'])){
            $this->createSellerProduct($parentProduct, $seller->id);
        }

        $this->{"create_".$parentProduct->type}($parentProduct,$data);
    }

    private function getAttributeFamily($attrubetCodes)
    {
        $count = count($attrubetCodes);
        $str = "'" . implode("','", $attrubetCodes) . "'";

        $grups = $this->attributeGroupRepo->leftJoin('attribute_group_mappings','attribute_groups.id','=','attribute_group_mappings.attribute_group_id')
            ->leftJoin('attributes',function($join) use ($attrubetCodes) {
                $join->on('attributes.id','=','attribute_group_mappings.attribute_id')
                    ->whereIn('code',$attrubetCodes);
            })
            ->groupBy('attribute_groups.id')
            ->havingRaw("SUM(IF(attributes.code IN($str),1,0)) = $count")
            ->select('attribute_groups.attribute_family_id')
            ->first();

        return $grups;
    }

    private function create_simple($product,$data)
    {
        $originalPrice = Arr::get($data, 'price.originalPrice.value');
        $discountedPrice = Arr::get($data, 'price.discountedPrice.value');

        $main_attributes = [
            'sku' => $product->sku,
            'product_number' => $data['product_number'],
            'name' => $data['name'],
            'weight' => $data['weight'] ?? 0.45,
            'source' => $data['url_key'],
            'status' => 1,
            'visible_individually' => 1,
            'url_key' => Str::slug($data['name']),
//                'short_description' => $data['url_key'],
            'description' => implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']))
        ];

        if($originalPrice > $discountedPrice){
            $main_attributes['price'] = $originalPrice;
            $main_attributes['special_price'] = $discountedPrice;
        }
        else{
            $main_attributes['price'] = $discountedPrice;
        }

        $this->assignAttributes($product, $main_attributes);
    }

    private function create_configurable(){

    }

    private function assignAttributes($product, $attributes,$check_option_values = false){
        foreach($attributes as $code => $value){
            $attribute = $this->attributeRepository->findOneByField('code', $code);

            $attr = [
                'product_id'   => $product->id,
                'attribute_id' => $attribute->id,
                'value'        => $value
            ];

            if($attribute->value_per_channel){
                $attr['channel'] = config('app.channel');
            }

            if ($attribute->value_per_locale){
                foreach (core()->getAllLocales() as $locale){
                    $attr['locale'] = $locale->code;
                    $this->attributeValueRepository->create($attr);
                }

            }else{
                $this->attributeValueRepository->create($attr);
            }

        }
    }
}