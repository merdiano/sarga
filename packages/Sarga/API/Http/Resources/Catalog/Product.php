<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class Product extends JsonResource
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
            'id'                     => $product->id,
            'type'                   => $product->type,
            'name'                   => $product->name,
//            'description'            => $product->description,
//            'is_wishlisted'          => $this->isWishlisted($product), //todo transfer to mobile
//            'is_item_in_cart'        => \Cart::hasProduct($product),//todo transfer to mobile
//            'shop_title'             => $this->shop_title,
            'brand'                  => $product->brand->name ?? '',
            'images'                 => ProductImage::collection($product->images),
            'color_count'            => $this->related_products->count(),

            /* special price cases */
            $this->merge($this->specialPriceInfo()),

        ];
    }

    /**
     * Get special price information.
     *
     * @return array
     */
    protected function specialPriceInfo()
    {
        if($this->type == 'configurable' && $variant = $this->product->getTypeInstance()->getMinPriceVariant())
        {
            $product = $variant->product;

        }else{
            $product = $this->product ? $this->product : $this;
        }

        $typeInstance = $product->getTypeInstance();

        return [

            'price'                  => (double) core()->convertPrice($typeInstance->getMinimalPrice()),

            'formatted_price'        => core()->currency($typeInstance->getMinimalPrice()),

            'regular_price'          => $this->when(
                $typeInstance->haveSpecialPrice(),
                (double) core()->convertPrice($typeInstance->getMaximumPrice())
            ),
            'formatted_regular_price' => $this->when(
                $typeInstance->haveSpecialPrice(),
                core()->currency($typeInstance->getMaximumPrice())
            ),
        ];
    }

//    private function isWishlisted($product):bool
//    {
//        $wishlist = false;
//
//        if ($customer = auth('sanctum')->user() && $wishlist = auth('sanctum')->user()->wishlist_items) {
//            $wishlist = $wishlist->filter(function ($item) use ($product) {
//                return $item->product_id == $product->product_id;
//            })->first();
//        }
//
//        return $wishlist ? true : false;
//    }
}
