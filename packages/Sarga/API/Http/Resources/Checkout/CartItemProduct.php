<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
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
        try{
            return [
                'id'                => $this->id,
                'name'              => $this->name,
                'images'            => ProductImage::collection($this->images),
                /* super attributes */
                $this->mergeWhen(!empty($this->parent && $this->parent->super_attributes), [
                    'super_attributes' => $this->super_attributes($this->parent->super_attributes),
                ]),
            ];
        }catch (\Exception $ex){
            return [
                'id'                => $this->id,
                'name'              => $this->name,
                'images'            => ProductImage::collection($this->images),
            ];
        }

    }

    private function super_attributes($attributes){
        if(is_countable($attributes)){
            return $attributes->map(function($item, $key){
                return [
                    'code' => $item->code,
                    'value' => $this->{$item->code},
                    'name' => $item->name ?? $item->admin_name,
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