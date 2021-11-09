<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariant extends JsonResource
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

        /* get type instance */
        $productTypeInstance = $product->getTypeInstance();
        return [
            'id'               => $this->id,
            'parent_id'        => $this->parent_id,
            'additional'       => $this->additional,
            'price'            => $productTypeInstance->getMinimalPrice(),
            'converted_price'  => core()->convertPrice($productTypeInstance->getMinimalPrice()),
            "color"            => $this->color,
            "size"=>$this->size,
            "brand"=>$this->brand,

            "special_price"=> $this->special_price,
            "special_price_from"=>$this->special_price_from,
              "special_price_to"=>$this->special_price_to,
              "length"=>$this->length,
              "width"=>$this->width,
              "height"=>$this->height,
              "weight"=>$this->weight,
              "quantity" => $this->inventories->sum('qty')
        ];
    }

}