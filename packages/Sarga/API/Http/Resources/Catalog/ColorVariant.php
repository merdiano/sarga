<?php

namespace Sarga\API\Http\Resources\Catalog;

class ColorVariant extends Product
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

        return [
            'id'                     => $product->id,
            'type'                   => $product->type,
            'name'                   => $product->name,
            'description'            => $product->description,
            'images'                 => ProductImage::collection($product->images),
            'size_variants'          => SizeVariant::collection($product->variants),
            $this->merge($this->specialPriceInfo()),

        ];
    }
}