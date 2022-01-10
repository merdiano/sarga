<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
//        $this->productReviewHelper = app('Webkul\Product\Helpers\Review');

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

        /* generating resource */
        return [
            /* product's information */
            'id'                     => $product->id,
//            'sku'                    => $product->sku,
            'type'                   => $product->type,
            'name'                   => $product->name,
            'url_key'                => $product->url_key,
            'price'                  => core()->convertPrice($productTypeInstance->getMinimalPrice()),
            'formatted_price'        => core()->currency($productTypeInstance->getMinimalPrice()),
            'short_description'      => $product->short_description,
            'description'            => $product->description,
            'images'                 => ProductImage::collection($product->images),
            /* product's checks */
            'in_stock'               => $product->haveSufficientQuantity(1),
            'is_wishlisted'          => $this->wishlistHelper->getWishlistProduct($product) ? true : false,
            'is_item_in_cart'        => \Cart::hasProduct($product),
//            'show_quantity_changer'  => $this->when(
//                $product->type !== 'grouped',
//                $product->getTypeInstance()->showQuantityBox()
//            ),
            /*
             * attributes
             */
//            'specifications' => app('Webkul\Product\Helpers\View')->getAdditionalData($product),
            /* product's extra information */
//            $this->merge($this->allProductExtraInfo()),

            /* special price cases */
            $this->merge($this->specialPriceInfo()),

            /* super attributes */
            $this->mergeWhen($productTypeInstance->isComposite(), [
                'super_attributes' => Attribute::collection($product->super_attributes),
            ]),
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

    /**
     * Get all product's extra information.
     *
     * @return array
     */
    private function allProductExtraInfo()
    {
        $product = $this->product ? $this->product : $this;

        $productTypeInstance = $product->getTypeInstance();

        return [
            /* grouped product */
            $this->mergeWhen(
                $productTypeInstance instanceof \Webkul\Product\Type\Grouped,
                $product->type == 'grouped'
                    ? $this->getGroupedProductInfo($product)
                    : null
            ),

            /* bundle product */
            $this->mergeWhen(
                $productTypeInstance instanceof \Webkul\Product\Type\Bundle,
                $product->type == 'bundle'
                    ? $this->getBundleProductInfo($product)
                    : null
            ),

            /* configurable product */
            $this->mergeWhen(
                $productTypeInstance instanceof \Webkul\Product\Type\Configurable,
                $product->type == 'configurable'
                    ? $this->getConfigurableProductInfo($product)
                    : null
            ),

        ];
    }

    /**
     * Get grouped product's extra information.
     *
     * @param  \Webkul\Product\Models\Product
     * @return array
     */
    private function getGroupedProductInfo($product)
    {
        return [
            'grouped_products' => $product->grouped_products->map(function($groupedProduct) {
                $associatedProduct = $groupedProduct->associated_product;

                $data = $associatedProduct->toArray();

                return array_merge($data, [
                    'qty'                   => $groupedProduct->qty,
                    'isSaleable'            => $associatedProduct->getTypeInstance()->isSaleable(),
                    'formated_price'        => $associatedProduct->getTypeInstance()->getPriceHtml(),
                    'show_quantity_changer' => $associatedProduct->getTypeInstance()->showQuantityBox(),
                ]);
            })
        ];
    }

    /**
     * Get bundle product's extra information.
     *
     * @param  \Webkul\Product\Models\Product
     * @return array
     */
    private function getBundleProductInfo($product)
    {
        return [
            'currency_options' => core()->getAccountJsSymbols(),
            'bundle_options' => app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product)
        ];
    }

    /**
     * Get configurable product's extra information.
     *
     * @param  \Webkul\Product\Models\Product
     * @return array
     */
    private function getConfigurableProductInfo($product)
    {
        return [
            'variants' => ProductVariant::collection($product->variants)
        ];
    }

}
