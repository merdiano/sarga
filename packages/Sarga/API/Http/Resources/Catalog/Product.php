<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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

//        $this->wishlistHelper = app('Webkul\Customer\Helpers\Wishlist');

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
        $product = $this->product ? $this->product : $this;

        $productTypeInstance = $product->getTypeInstance();

        return [
            /* product's information */
            'id'                     => $product->id,
//            'sku'                    => $product->sku,
            'type'                   => $product->type,
            'name'                   => $product->name,
//            'url_key'                => $product->url_key,
            'price'                  => (double) core()->convertPrice($productTypeInstance->getMinimalPrice()),

            'formatted_price'        => core()->currency($productTypeInstance->getMinimalPrice()),
//            'short_description'      => $product->short_description,
            'description'            => $product->description,
            'images'                 => ProductImage::collection($product->images),
            /* product's checks */
//            'in_stock'               => $product->haveSufficientQuantity(1),
            'is_wishlisted'          => $this->isWishlisted($product) ,
            'is_item_in_cart'        => \Cart::hasProduct($product),
            'shop_title'             => $this->shop_title,
//            'new'                    => $this->new,
//            'featured'               => $this->featured,
            'brand'                  => $product->brand->name ?? '',
//            'show_quantity_changer'  => $this->when(
//                $product->type !== 'grouped',
//                $product->getTypeInstance()->showQuantityBox()
//            ),
            /*
             * attributes
             */
//            'specifications' => app('Webkul\Product\Helpers\View')->getAdditionalData($product),
            /* product's extra information */
            $this->merge($this->allProductExtraInfo()),

            /* special price cases */
            $this->merge($this->specialPriceInfo()),

            /* super attributes */
            $this->mergeWhen($this->super_attributes, [
                'super_attributes' => $this->super_attributes,
            ]),
        ];
    }
    private function super_attributes(){
        if(is_countable($this->super_attributes)){
            return $this->super_attributes->map(function($item, $key){
                return [
                    'code' => $item->code,
                    'value' => $this->{$item->code},
                    'name' => $item->name??$item->admin_name,
                    'label' => $item->options->where('id',$this->{$item->code})->first()->admin_name
                ];
            })->toArray();
        }else{
            $item = $this->super_attributes;
            return [
                'code' => $item->code,
                'value' => $this->{$item->code},
                'name' => $item->name??$item->admin_name,
                'label' => $item->options->where('id',$this->{$item->code})->first()->admin_name
            ];
        }

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
                (double) core()->convertPrice($productTypeInstance->getSpecialPrice())
            ),
            'formatted_special_price' => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                core()->currency($productTypeInstance->getSpecialPrice())
            ),
            'regular_price'          => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                (double) data_get($productTypeInstance->getProductPrices(), 'regular_price.price')
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
//            $this->mergeWhen(
//                $productTypeInstance instanceof \Webkul\Product\Type\Grouped,
//                $product->type == 'grouped'
//                    ? $this->getGroupedProductInfo($product)
//                    : null
//            ),
//
//            /* bundle product */
//            $this->mergeWhen(
//                $productTypeInstance instanceof \Webkul\Product\Type\Bundle,
//                $product->type == 'bundle'
//                    ? $this->getBundleProductInfo($product)
//                    : null
//            ),

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
        $data =  [
            'variants_count' => $this->variants->count(),
            'color_count' => $this->variants->groupBy('color')->count(),
        ];

        $special_variant = $this->variants->sortBy('min_price')->first();

        if($special_variant  && $special_variant->min_price < $special_variant->max_price){
            $data = array_merge($data, [
                'special_price' => core()->convertPrice($special_variant->min_price),
                'formatted_special_price' => core()->currency($special_variant->min_price),
                'regular_price'          => core()->convertPrice($special_variant->price),
                'formatted_regular_price' => core()->currency($special_variant->price),
            ]);
        }
        return $data;
    }

    private function isWishlisted($product):bool
    {
        $wishlist = false;

        if ($customer = auth('sanctum')->user() && $wishlist = auth('sanctum')->user()->wishlist_items) {
            $wishlist = $wishlist->filter(function ($item) use ($product) {
                return $item->product_id == $product->product_id;
            })->first();
        }

        return $wishlist ? true : false;
    }
}
