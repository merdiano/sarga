<?php

namespace Sarga\API\Http\Resources\Catalog;

class SizeVariant extends ProductDetail
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->product ? $this->product : $this;
        $flat =    $this->product_flats->first();
        return [
            /* product's information */
            'id'         => $product->id,
            'type'       => $product->type,
            'name'       => $product->name,
            'size'       => $flat->size ?? 0,
            'size_label' => $flat->size_label ?? null,

            /* special price cases */
            $this->merge($this->specialPriceInfo()),

        ];
    }
}