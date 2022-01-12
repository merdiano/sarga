<?php

namespace Sarga\API\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductRepository as WProductRepository;

class ProductRepository extends WProductRepository
{
    protected $attributeGroupRepo;
    protected $optionRepository;
    protected $productFlatRepository;
    protected $attributeValueRepository;
    protected $imageRepository;
    protected $fillableTypes = ['sku', 'name', 'url_key', 'short_description', 'description', 'price', 'weight', 'status'];
    public function __construct(AttributeRepository $attributeRepository,
                                App $app,
                                AttributeGroupRepository $attributeGroupRepo,
                                ProductFlatRepository $productFlatRepository,
                                ProductAttributeValueRepository $productAttributeValueRepository,
                                ProductImageRepository $productImageRepository,
                                AttributeOptionRepository $optionRepository)
    {
        $this->attributeGroupRepo = $attributeGroupRepo;
        $this->optionRepository = $optionRepository;
        $this->attributeValueRepository = $productAttributeValueRepository;
        $this->productFlatRepository = $productFlatRepository;
        $this->imageRepository = $productImageRepository;
        parent::__construct($attributeRepository, $app);
    }

    public function create($data){
        $product['sku'] = $data['sku'];
//        return array_map(fn($value): int => $value * 2, range(1, 5));

        $product['type'] = (!empty($data['color_variants'])  || !empty($data['size_variants'])) ? 'configurable':'simple';

        $attributes = Arr::only($data,['brand','cinsiyet']);

        try {
            DB::beginTransaction();

            if (array_key_exists('attributes', $data)) {
                $attributes [] = Arr::collapse($data['attributes']);

                $grp = $this->getAttributeFamily(array_keys($attributes));

                $product['attribute_family_id'] = $grp ? $grp->attribute_family_id :
                    (($product['type'] == 'configurable') ? 2 : 1);//default_configurable_product: default_simple_prodcut
            } else
                $product['attribute_family_id'] = $product['type'] == 'configurable' ? 2 : 1;

            //create product
            $parentProduct = $this->getModel()->create($product);
            $this->assignAttributes($parentProduct, [
                'sku' => $parentProduct->sku,
                'name' => $data['name'],
                'weight' => 0,
                'status' => 1,
                'url_key' => $parentProduct->sku,
                'short_description' => $data['url_key'],
                'description' => implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']))
            ]);

            if (!empty($data['images'])) {
                $this->assignImages($parentProduct, $data['images']);
            }

            if(!empty($data['categories'])){
                $parentProduct->categories()->attach($data['categories']);
            }

            if ($product['type'] == 'configurable') {
                //create variants color
                if (!empty($data['color_variants'])) {
                    $attribute = $this->attributeRepository->findOneByField('code', 'color');
                    $parentProduct->super_attributes()->attach($attribute->id);

                    foreach ($data['color_variants'] as $colorVariant) {
                        $description = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $colorVariant['descriptions']));
                        if (!empty($colorVariant['size_variants'])) {


                            foreach ($colorVariant['size_variants'] as $sizeVariant) {
                                $variant = $this->createVariant($parentProduct, $colorVariant['product_number'] . $sizeVariant['size']);

                                $this->assignImages($variant, $colorVariant['images']);

                                $this->assignAttributes($variant, [
                                    'sku' => $variant->sku,
                                    'color' => $this->getAttributeOptionId('color', $colorVariant['color']),
                                    'name' => $colorVariant['name'],
                                    'size' => $this->getAttributeOptionId('size', $sizeVariant['size']),
                                    'price' => $sizeVariant['price'],
                                    'weight' => 0,
                                    'status' => 1,
                                    'url_key' => $variant->sku,
                                    'short_description' => $colorVariant['url_key'],
                                    'description' => $description
                                ]);
                            }
                        } else {
                            $variant = $this->createVariant($parentProduct, $colorVariant['product_number']);
                            $this->assignImages($variant, $colorVariant['images']);
                            $this->assignAttributes($variant, [
                                'sku' => $variant->sku,
                                'color' => $this->getAttributeOptionId('color', $colorVariant['color']),
                                'name' => $colorVariant['name'],
                                'price' => Arr::get($colorVariant, 'price.discountedPrice.value'),
                                'weight' => 0,
                                'status' => 1,
                                'url_key' => $variant->sku,
                                'short_description' => $colorVariant['url_key'],
                                'description' => $description
                            ]);
                        }
                    }
                }
                if (!empty($data['size_variants'])) {
                    $attribute = $this->attributeRepository->findOneByField('code', 'size');
                    $parentProduct->super_attributes()->attach($attribute->id);
                    foreach ($data['size_variants'] as $sizeVariant) {
                        $variant = $this->createVariant($parentProduct, $data['product_number'] . $sizeVariant['size']);
                        $this->assignImages($variant, $data['images']);
                        $attributes = [
                            'sku' => $variant->sku,
                            'size' => $this->getAttributeOptionId('size', $sizeVariant['size']),
                            'name' => $data['name'],
                            'price' => $sizeVariant['price'],
                            'weight' => 0,
                            'status' => 1,
                            'url_key' => $variant->sku,
                            'short_description' => $data['url_key'],
                            'description' => implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']))
                        ];
                        if (!empty($data['color'])) {
                            $attributes['color'] = $this->getAttributeOptionId('color', $data['color']);
                        }
                        $this->assignAttributes($variant, $attributes);
                    }
                }
                //todo default_variant_id
            }

            // assign attributes
//        $this->assignCustomAttributes($parentProduct,$attributes);

            Event::dispatch('catalog.product.create.after', $parentProduct);

            DB::commit();
            return $parentProduct->id;
        }
        catch(\Exception $ex){
            DB::rollBack();
            Log::error($ex);
            return false;
        }

    }

    private function assignImages($product,$images){
        foreach($images as $image){
            $this->imageRepository->create([
                'type' => 'cdn',
                'path' => $image,
                'product_id' => $product->id,
            ]);
        }
    }
    private function assignAttributes($product, $attributes,$check_option_values = false){
        foreach($attributes as $code => $value){
            $attribute = $this->attributeRepository->findOneByField('code', $code);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValueRepository->create([
                                'product_id'   => $product->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                                'value'        => $value,
                            ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $product->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => $channel->code,
                            'value'        => $value,
                        ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $product->id,
                            'attribute_id' => $attribute->id,
                            'locale'       => $locale->code,
                            'value'        => $value,
                        ]);
                    }
                } else {
                    $this->attributeValueRepository->create([
                        'product_id'   => $product->id,
                        'attribute_id' => $attribute->id,
                        'value'        => $value,
                    ]);
                }
            }
        }
    }

    private function createVariant($product, $sku){
        return $this->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => 'simple',
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $sku,
        ]);

    }

    private function createFlat($product){

        $channel = core()->getDefaultChannel();

        foreach ($channel->locales as $locale){
            $productFlat = $this->productFlatRepository->findOneWhere([
                'product_id' => $product->id,
                'channel'    => $channel->code,
                'locale'     => $locale->code,
            ]);

            if (! $productFlat) {
                $productFlat = $this->productFlatRepository->create([
                    'product_id' => $product->id,
                    'channel'    => $channel->code,
                    'locale'     => $locale->code,
                ]);
            }
        }

    }

    private function attributeValues($values,$attributeCode){

        $attribute = $this->attributeRepository->getAttributeByCode($attributeCode);

        $all_options = $attribute->options()
            ->orderBy('sort_order','asc')
            ->get();

        $options = $all_options->whereIn('admin_name',$values)->pluck('admin_name')->toArray();
        //create new options if doesn exist
        if(count($values) != count($options)
            && $new_options = array_diff($values,$options)){

            $order = $all_options->last()->sort_order ?? 0;

            foreach($new_options as $new_option){
                $order++;
                $this->optionRepository->create([
                    'admin_name' => $new_option,
                    'sort_order' => $order,
                    'attribute_id' =>  $attribute->id
                ]);
            }
            $options = array_merge($options,$new_options);
        }

        return $options;
    }

    private function getAttributeOptionId($attr,$value){
        $attribute_id = $this->attributeRepository->getAttributeByCode($attr)->id;

        $option = $this->optionRepository->findOneWhere(['attribute_id'=>$attribute_id,'admin_name'=>$value]);

        if(! $option){
            $option =$this->optionRepository->create(['attribute_id'=>$attribute_id,'admin_name'=>$value]);
        }

        return $option->id;
    }

    //find attribute family
    private function getAttributeFamily($attrubetCodes){
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
}