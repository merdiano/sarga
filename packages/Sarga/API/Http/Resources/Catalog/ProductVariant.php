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

    public function __construct($resource, $opion)
    {
//        $this->productReviewHelper = app('Webkul\Product\Helpers\Review');

        $this->wishlistHelper = app('Webkul\Customer\Helpers\Wishlist');
        $this->option = $opion;
        $this->wishlistHelper = app('Webkul\Customer\Helpers\Wishlist');
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
            'url_key'           => $product->url_key,
            'price'             => core()->convertPrice($productTypeInstance->getMinimalPrice()),
            'formatted_price'   => core()->currency($productTypeInstance->getMinimalPrice()),
            'short_description' => $product->short_description,
            'description'       => $product->description,
            "option_value"            => $this->option->admin_name,
//            "size"             => $this->size,
//            "brand"=>$this->brand,
            /* product's checks */
            'in_stock'               => $product->haveSufficientQuantity(1),
            'is_wishlisted'          => $this->wishlistHelper->getWishlistProduct($product) ? true : false,
            'is_item_in_cart'        => \Cart::hasProduct($product),
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