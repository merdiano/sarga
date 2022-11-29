<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail extends Product
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
            /* product's information */
            'id'             => $product->id,
            'type'           => $product->type,
            'name'           => $product->name,
            'description'    => $product->description,
            'brand'          => $product->brand->name ?? '',
            'color'          => $product->color,
            'images'         => ProductImage::collection($product->images),
            'color_variants' => ColorVariant::collection($product->related_products->where('status',1)),
            'size_variants'  => SizeVariant::collection($product->variants->where('status',1)),
            /* special price cases */
            $this->merge($this->specialPriceInfo()),

        ];
    }


}