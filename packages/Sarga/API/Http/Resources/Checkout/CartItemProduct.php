<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Catalog\ProductImage;

class CartItemProduct extends JsonResource
{
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
        return [
            'id'                => $this->id,
            'name'              => $product->name,
            'images'            => ProductImage::collection($product->images),
            /* super attributes */
            $this->mergeWhen(!empty($product->parent), [
                'super_attributes' => $this->super_attributes($product->parent->super_attributes),
            ]),
        ];
    }

    private function super_attributes($attributes){
        if(is_countable($attributes)){
            return $attributes->map(function($item, $key){
                return [
                    'code' => $item->code,
                    'value' => $this->{$item->code},
                    'name' => $item->name,
                    'label' => $item->options->where('id',$this->{$item->code})->first()->admin_name
                ];
            })->toArray();
        }else{
            $item = $attributes;
            return [
                'code' => $item->code,
                'value' => $this->{$item->code},
                'name' => $item->name,
                'label' => $item->options->where('id',$this->{$item->code})->first()->admin_name
            ];
        }

    }
}