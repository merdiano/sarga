<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductVariant extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */

    public function __construct($resource, $attributes)
    {
        $this->attributes = $attributes;
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        /* assign product */
        $product = $this->product ? $this->product : $this;

        /* get type instance */
        $productTypeInstance = $product->getTypeInstance();
        return [
            'id'                => $this->id,
            'name'              => $product->name,
//            'url_key'           => $product->url_key,
            'price'             => core()->convertPrice($productTypeInstance->getMinimalPrice()),
            'formatted_price'   => core()->currency($productTypeInstance->getMinimalPrice()),
//            'short_description' => $product->short_description,
            'description'       => $product->description,
            "option_value"      => $this->last_attribute_value(),
            /* product's checks */
//            'in_stock'          => $product->haveSufficientQuantity(1),
            'is_item_in_cart'   => \Cart::hasProduct($product),
            /* special price cases */
            $this->merge($this->specialPriceInfo()),
            'images'            => ProductImage::collection($product->images),
            'attributes'        => $this->super_attributes(),
        ];
    }

    private function super_attributes(){
        if(is_countable($this->attributes)){
            return $this->attributes->map(function($item, $key){
                $option = $item->options()->where('id',$this->{$item->code})->first();
                return [
                    'code' => (string)$item->id,
                    'value' => $this->{$item->code},
                    'name' => $item->name??$item->admin_name,
                    'label' => $option->admin_name,
                ];
            })->toArray();
        }else{
            $item = $this->attributes;
            $option = $item->options()->where('id',$this->{$item->code})->first();
            return [
                'code' => (string)$item->id,
                'value' => $this->{$item->code},
                'name' => $item->name??$item->admin_name,
                'label' => $option->admin_name,
            ];
        }

    }

    private function last_attribute_value(){

        $last_attribute = $this->attributes->last();

        if(!empty($last_attribute->options))
        {
            $option = $last_attribute->options->where('id',$this->{$last_attribute->code})->first();
            return $option->admin_name;
        }

        return null;
    }
    /**
     * Get special price information.
     *
     * @return array
     */
    private function specialPriceInfo()
    {
        $product = $this->product ? $this->product : $this;

        $productTypeInstance = $product->getTypeInstance();

        return [
            'special_price'          => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                core()->convertPrice($productTypeInstance->getSpecialPrice())
            ),
            'formatted_special_price' => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                core()->currency($productTypeInstance->getSpecialPrice())
            ),
            'regular_price'          => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                data_get($productTypeInstance->getProductPrices(), 'regular_price.price')
            ),
            'formatted_regular_price' => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                data_get($productTypeInstance->getProductPrices(), 'regular_price.formated_price')
            ),
        ];
    }
}