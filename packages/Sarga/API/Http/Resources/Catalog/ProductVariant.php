<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariant extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */

    public function __construct($resource,$attribute)
    {
//        $this->productReviewHelper = app('Webkul\Product\Helpers\Review');

        $this->wishlistHelper = app('Webkul\Customer\Helpers\Wishlist');
        $this->attribute = $attribute;
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
            'id'               => $this->id,
            'parent_id'        => $this->parent_id,
            'additional'       => $this->additional,
            'price'            => $productTypeInstance->getMinimalPrice(),
            'converted_price'  => core()->currency($productTypeInstance->getMinimalPrice()),
            "attribute"            => $this->attribute,
//            "size"             => $this->size,
//            "brand"=>$this->brand,

            /* special price cases */
            $this->merge($this->specialPriceInfo()),

        ];
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