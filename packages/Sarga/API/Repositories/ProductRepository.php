<?php

namespace Sarga\API\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository as WProductRepository;

class ProductRepository extends WProductRepository
{
    protected $attributeGroupRepo;
    protected $optionRepository;

    public function __construct(AttributeRepository $attributeRepository,
                                App $app,
                                AttributeGroupRepository $attributeGroupRepo,
                                AttributeOptionRepository $optionRepository)
    {
        $this->attributeGroupRepo = $attributeGroupRepo;
        $this->optionRepository = $optionRepository;
        parent::__construct($attributeRepository, $app);
    }

    public function create($data){
        $product['sku'] = $data['sku'];
        $product['status'] = true;

        $product['type'] = (!empty($data['color_variants'])  || !empty($data['size_variants'])) ? 'configurable':'simple';
        //todo test here add some attributes,families

        if(array_key_exists('attributes',$data)){
            $grp = $this->getAttributeFamily(array_keys(Arr::collapse($data['attributes'])));
            $product['attribute_family_id'] = $grp ? $grp->attribute_family_id :
                (($product['type'] == 'configurable') ? 2:1);//default_configurable_product: default_simple_prodcut
        }
        else
            $product['attribute_family_id'] = $product['type'] == 'configurable' ? 2:1;

        if(!empty($data['size_variants'])){
            $sizes = Arr::pluck($data['size_variants'],'size');

            if(!empty($data['color_variants'])){

                $variant_size = Arr::pluck(Arr::collapse(Arr::pluck($data['color_variants'],'size_variants')),'size');
                $sizes = array_unique(array_merge($sizes, $variant_size));
            }

            if($sizes){
                $product['super_attributes']['size'] = $this->attributeValues($sizes,'size');
            }

        }

        if(!empty($data['color_variants'])){
            $colors = Arr::pluck($data['color_variants'],'color');
            $colors[] = $data['color'];
            $product['super_attributes']['color'] = $this->attributeValues($colors,'color');
        }

        $productCreated = $this->model()->create($product);

        if($product['type'] != 'configurable'){
            Event::dispatch('catalog.product.create.after', $product);
        }else{

        }

//        if($productCreated && !empty($data['attributes'])){
//            $this->updateAttributes($productCreated,$data['attributes']);
//        }

        return $productCreated;
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