<?php
namespace Sarga\API\ProductType;

use Illuminate\Support\Facades\Event;
use Webkul\Product\Type\AbstractType;

class Scrapable extends AbstractType
{
    /**
     * Create configurable product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $product = $this->productRepository->getModel()->create($data);

        if($product['type'] != 'configurable'){
            Event::dispatch('catalog.product.create.after', $product);
            return;
        }

        if (isset($data['super_attributes'])) {
            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id);
            }

            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }

        return $product;
    }

    public function createVariant($product, $permutation, $data = [])
    {
        if (! count($data)) {
            $data = [
                'sku'         => $product->sku . '-variant-' . implode('-', $permutation),
                'name'        => '',
                'inventories' => [],
                'price'       => 0,
                'weight'      => 0,
                'status'      => 1,
            ];
        }

        $data = $this->fillRequiredFields($data);

        $typeOfVariants = 'simple';
        $productInstance = app(config('product_types.' . $product->type . '.class'));

        if (isset($productInstance->variantsType) && ! in_array($productInstance->variantsType , ['bundle', 'configurable', 'grouped'])) {
            $typeOfVariants = $productInstance->variantsType;
        }

        $variant = $this->productRepository->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => $typeOfVariants,
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $data['sku'],
        ]);

        foreach ($this->fillableTypes as $attributeCode) {
            if (! isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValueRepository->create([
                                'product_id'   => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                                'value'        => $data[$attributeCode],
                            ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => $channel->code,
                            'value'        => $data[$attributeCode],
                        ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'locale'       => $locale->code,
                            'value'        => $data[$attributeCode],
                        ]);
                    }
                } else {
                    $this->attributeValueRepository->create([
                        'product_id'   => $variant->id,
                        'attribute_id' => $attribute->id,
                        'value'        => $data[$attributeCode],
                    ]);
                }
            }
        }

        foreach ($permutation as $attributeId => $optionId) {
            $this->attributeValueRepository->create([
                'product_id'   => $variant->id,
                'attribute_id' => $attributeId,
                'value'        => $optionId,
            ]);
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }

    public function fillRequiredFields(array $data): array
    {
        /**
         * Name field is not present when variant is created so adding sku.
         */
        return array_merge($data, [
            'url_key' => $data['sku'],
            'short_description' => $data['sku'],
            'description' => $data['sku']
        ]);
    }
}