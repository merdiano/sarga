<?php

namespace Sarga\API\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Sarga\Brand\Repositories\BrandRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Models\ProductAttributeValueProxy;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductRepository as WProductRepository;

class ProductRepository extends WProductRepository
{
    protected $attributeGroupRepo;
    protected $optionRepository;
    protected $productFlatRepository;
    protected $attributeValueRepository;
    protected $imageRepository;
    protected $vendorProductRepository;
    protected $brandRepository;

    protected $fillableTypes = ['sku', 'name', 'url_key', 'short_description', 'description', 'price', 'weight', 'status'];

    public function __construct(AttributeRepository $attributeRepository,
                                App $app,
                                AttributeGroupRepository $attributeGroupRepo,
                                ProductFlatRepository $productFlatRepository,
                                ProductAttributeValueRepository $productAttributeValueRepository,
                                ProductImageRepository $productImageRepository,
                                VendorProductRepository $vendorProductRepository,
                                BrandRepository $brandRepository,
                                AttributeOptionRepository $optionRepository)
    {
        $this->attributeGroupRepo = $attributeGroupRepo;
        $this->optionRepository = $optionRepository;
        $this->attributeValueRepository = $productAttributeValueRepository;
        $this->productFlatRepository = $productFlatRepository;
        $this->imageRepository = $productImageRepository;
        $this->vendorProductRepository = $vendorProductRepository;
        $this->brandRepository = $brandRepository;

        parent::__construct($attributeRepository, $app);
    }

    public function create($data){
        $product['sku'] = $data['sku'];
//        return array_map(fn($value): int => $value * 2, range(1, 5));

        $product['type'] = (!empty($data['color_variants'])  || !empty($data['size_variants'])) ? 'configurable':'simple';

        $attributes = Arr::only($data,['brand','cinsiyet']);

        try {
            DB::beginTransaction();

            if (array_key_exists('attributes', $data)) {
                $attributes [] = Arr::collapse($data['attributes']);

                $grp = $this->getAttributeFamily(array_keys($attributes));

                $product['attribute_family_id'] = $grp ? $grp->attribute_family_id :
                    (($product['type'] == 'configurable') ? 2 : 1);//default_configurable_product: default_simple_prodcut
            } else
                $product['attribute_family_id'] = $product['type'] == 'configurable' ? 2 : 1;

            //create product
            $parentProduct = $this->getModel()->create($product);
            $this->assignAttributes($parentProduct, [
                'sku' => $parentProduct->sku,
                'name' => $data['name'],
                'weight' => 0,
                'status' => 1,
                'visible_individually' => 1,
                'url_key' => $parentProduct->sku,
                'short_description' => $data['url_key'],
                'description' => implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']))
            ]);

            if (!empty($data['images'])) {
                $this->assignImages($parentProduct, $data['images']);
            }

            if(!empty($data['categories'])){
                $parentProduct->categories()->attach($data['categories']);
            }

            if($data['vendor'] && $seller = $this->vendorRepository->findOneByField('shop_title',$data['vendor'])){
                $this->createSellerProduct($product, $seller->id);
            }

            if(!empty($data['brand'])){
                $brand = $this->brandRepository->firstOrCreate(['code' =>Str::slug($data['brand'])],[
                    'name' =>$data['brand'],
                    'status' =>1,
                ]);

                if($brand){
                    $brand->products()->attach($parentProduct->id);

                    if(!empty($data['categories'])){
                        $brand->categories()->attach($data['categories']);
                    }

                    if($seller){
                        $brand->sellers()->attach($seller->id);
                    }
                }
            }

            if ($product['type'] == 'configurable') {
                $variant = null;
                //create variants color
                if (!empty($data['color_variants'])) {
                    $attribute = $this->attributeRepository->findOneByField('code', 'color');
                    $parentProduct->super_attributes()->attach($attribute->id);

                    foreach ($data['color_variants'] as $colorVariant) {
                        $description = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $colorVariant['descriptions']));
                        if (!empty($colorVariant['size_variants'])) {
                            foreach ($colorVariant['size_variants'] as $sizeVariant) {
                                $variant = $this->createVariant($parentProduct, $colorVariant['product_number'] . $sizeVariant['size']);

                                $this->assignImages($variant, $colorVariant['images']);

                                $this->assignAttributes($variant, [
                                    'sku' => $variant->sku,
                                    'color' => $this->getAttributeOptionId('color', $colorVariant['color']),
                                    'name' => $colorVariant['name'],
                                    'size' => $this->getAttributeOptionId('size', $sizeVariant['size']),
                                    'price' => $sizeVariant['price'],
                                    'weight' => 0,
                                    'status' => 1,
                                    'visible_individually' => 1,
                                    'url_key' => $variant->sku,
                                    'short_description' => $colorVariant['url_key'],
                                    'description' => $description
                                ]);
                            }
                        } else {
                            $variant = $this->createVariant($parentProduct, $colorVariant['product_number']);
                            $this->assignImages($variant, $colorVariant['images']);
                            $this->assignAttributes($variant, [
                                'sku' => $variant->sku,
                                'color' => $this->getAttributeOptionId('color', $colorVariant['color']),
                                'name' => $colorVariant['name'],
                                'price' => Arr::get($colorVariant, 'price.discountedPrice.value'),
                                'weight' => 0,
                                'status' => 1,
                                'visible_individually' => 1,
                                'url_key' => $variant->sku,
                                'short_description' => $colorVariant['url_key'],
                                'description' => $description
                            ]);
                        }
                    }
                }
                if (!empty($data['size_variants'])) {
                    $attribute = $this->attributeRepository->findOneByField('code', 'size');
                    $parentProduct->super_attributes()->attach($attribute->id);
                    foreach ($data['size_variants'] as $sizeVariant) {
                        $variant = $this->createVariant($parentProduct, $data['product_number'] . $sizeVariant['size']);
                        $this->assignImages($variant, $data['images']);
                        $attributes = [
                            'sku' => $variant->sku,
                            'size' => $this->getAttributeOptionId('size', $sizeVariant['size']),
                            'name' => $data['name'],
                            'price' => $sizeVariant['price'],
                            'weight' => 0,
                            'status' => 1,
                            'visible_individually' => 1,
                            'url_key' => $variant->sku,
                            'short_description' => $data['url_key'],
                            'description' => implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']))
                        ];
                        if (!empty($data['color'])) {
                            $attributes['color'] = $this->getAttributeOptionId('color', $data['color']);
                        }
                        $this->assignAttributes($variant, $attributes);
                    }
                }
                if($variant){
                    $parentProduct->getTypeInstance()->setDefaultVariantId($variant->id);
                }
            }

            // assign attributes
//        $this->assignCustomAttributes($parentProduct,$attributes);

            Event::dispatch('catalog.product.create.after', $parentProduct);

            DB::commit();
            return $parentProduct;
        }
        catch(\Exception $ex){
            DB::rollBack();
            Log::error($ex);
            return false;
        }

    }

    /**
     * Returns the all products of the seller
     *
     * @param integer $seller
     * @return Collection
     */
    public function findAllBySeller($seller_id,$category_id = null)
    {
        $params = request()->input();

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($seller_id, $params,$category_id) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                ->addSelect('product_flat.*')
                ->join('product_flat as variants', 'product_flat.id', '=', DB::raw('COALESCE(' . DB::getTablePrefix() . 'variants.parent_id, ' . DB::getTablePrefix() . 'variants.id)'))
                ->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                ->leftJoin('product_attribute_values', 'product_attribute_values.product_id', '=', 'variants.product_id')
                ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price1'))

                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->leftJoin('marketplace_products', 'product_flat.product_id', '=', 'marketplace_products.product_id')
                ->where('product_flat.visible_individually', 1)
                ->where('product_flat.status', 1)
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key')
                ->where('marketplace_products.marketplace_seller_id', $seller_id)
                ->where('marketplace_products.is_approved', 1);

            $qb->addSelect(DB::raw('(CASE WHEN marketplace_products.is_owner = 0 THEN marketplace_products.price ELSE product_flat.price END) AS price2'));

            if ($category_id) {
                $qb->whereIn('product_categories.category_id', explode(',', $category_id));
            }

            $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                    ->where('flat_variants.channel', $channel)
                    ->where('flat_variants.locale', $locale);
            });

            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($params['sort'] == 'price') {
                    $qb->orderBy($attribute->code, $params['order']);
                } else {
                    $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                }
            }

            //brand attribute added code
            $attributeFilters = $this->attributeRepository
                ->getProductDefaultAttributes(array_keys(
                    request()->input()
                ));

            if (count($attributeFilters) > 0) {
                $qb = $qb->where(function ($filterQuery) use ($attributeFilters) {

                    foreach ($attributeFilters as $attribute) {
                        $filterQuery->orWhere(function ($attributeQuery) use ($attribute) {

                            $column = DB::getTablePrefix() . 'product_attribute_values.' . ProductAttributeValueProxy::modelClass()::$attributeTypeFields[$attribute->type];

                            $filterInputValues = explode(',', request()->get($attribute->code));

                            # define the attribute we are filtering
                            $attributeQuery = $attributeQuery->where('product_attribute_values.attribute_id', $attribute->id);

                            # apply the filter values to the correct column for this type of attribute.
                            if ($attribute->type != 'price') {

                                $attributeQuery->where(function ($attributeValueQuery) use ($column, $filterInputValues) {
                                    foreach ($filterInputValues as $filterValue) {
                                        if (! is_numeric($filterValue)) {
                                            continue;
                                        }
                                        $attributeValueQuery->orWhereRaw("find_in_set(?, {$column})", [$filterValue]);
                                    }
                                });

                            } else {
                                $attributeQuery->where($column, '>=', core()->convertToBasePrice(current($filterInputValues)))
                                    ->where($column, '<=', core()->convertToBasePrice(end($filterInputValues)));
                            }
                        });
                    }

                });

                $qb->groupBy('variants.id');
                $qb->havingRaw('COUNT(*) = ' . count($attributeFilters));
            }

            return $qb->groupBy('product_flat.id');
        })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }

    public function createSellerProduct($product, $seller_id){
        Event::dispatch('marketplace.catalog.product.create.before');

        $sellerProduct = $this->vendorProductRepository->create([
            'marketplace_seller_id' => $seller_id,
            'is_approved'           => 1,
            'condition'             => 'new',
            'description'           => 'scraped product',
            'is_owner'              => 1,
            'product_id'            => $product->id,
        ]);

        foreach ($sellerProduct->product->variants as $baseVariant) {
            $this->vendorProductRepository->create([
                'parent_id' => $sellerProduct->id,
                'product_id' => $baseVariant->id,
                'is_owner' => 1,
                'marketplace_seller_id' => $seller_id,
                'is_approved' => 1,
                'condition'             => 'new',
            ]);
        }

        Event::dispatch('marketplace.catalog.product.create.after', $sellerProduct);

        return $sellerProduct;
    }
    private function assignImages($product,$images){
        foreach($images as $image){
            $this->imageRepository->create([
                'type' => 'cdn',
                'path' => $image,
                'product_id' => $product->id,
            ]);
        }
    }
    private function assignAttributes($product, $attributes,$check_option_values = false){
        foreach($attributes as $code => $value){
            $attribute = $this->attributeRepository->findOneByField('code', $code);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValueRepository->create([
                                'product_id'   => $product->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                                'value'        => $value,
                            ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $product->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => $channel->code,
                            'value'        => $value,
                        ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $product->id,
                            'attribute_id' => $attribute->id,
                            'locale'       => $locale->code,
                            'value'        => $value,
                        ]);
                    }
                } else {
                    $this->attributeValueRepository->create([
                        'product_id'   => $product->id,
                        'attribute_id' => $attribute->id,
                        'value'        => $value,
                    ]);
                }
            }
        }
    }

    private function createVariant($product, $sku){
        return $this->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => 'simple',
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $sku,
        ]);

    }

    private function createFlat($product){

        $channel = core()->getDefaultChannel();

        foreach ($channel->locales as $locale){
            $productFlat = $this->productFlatRepository->findOneWhere([
                'product_id' => $product->id,
                'channel'    => $channel->code,
                'locale'     => $locale->code,
            ]);

            if (! $productFlat) {
                $productFlat = $this->productFlatRepository->create([
                    'product_id' => $product->id,
                    'channel'    => $channel->code,
                    'locale'     => $locale->code,
                ]);
            }
        }

    }

    private function attributeValues($values,$attributeCode){

        $attribute = $this->attributeRepository->getAttributeByCode($attributeCode);

        $all_options = $attribute->options()
            ->orderBy('sort_order','asc')
            ->get();

        $options = $all_options->whereIn('admin_name',$values)->pluck('admin_name')->toArray();
        //create new options if doesn exist
        if(count($values) != count($options)
            && $new_options = array_diff($values,$options)){

            $order = $all_options->last()->sort_order ?? 0;

            foreach($new_options as $new_option){
                $order++;
                $this->optionRepository->create([
                    'admin_name' => $new_option,
                    'sort_order' => $order,
                    'attribute_id' =>  $attribute->id
                ]);
            }
            $options = array_merge($options,$new_options);
        }

        return $options;
    }

    private function getAttributeOptionId($attr,$value){
        $attribute_id = $this->attributeRepository->getAttributeByCode($attr)->id;

        $option = $this->optionRepository->findOneWhere(['attribute_id'=>$attribute_id,'admin_name'=>$value]);

        if(! $option){
            $option =$this->optionRepository->create(['attribute_id'=>$attribute_id,'admin_name'=>$value]);
        }

        return $option->id;
    }

    //find attribute family
    private function getAttributeFamily($attrubetCodes){
        $count = count($attrubetCodes);
        $str = "'" . implode("','", $attrubetCodes) . "'";

        $grups = $this->attributeGroupRepo->leftJoin('attribute_group_mappings','attribute_groups.id','=','attribute_group_mappings.attribute_group_id')
            ->leftJoin('attributes',function($join) use ($attrubetCodes) {
                $join->on('attributes.id','=','attribute_group_mappings.attribute_id')
                    ->whereIn('code',$attrubetCodes);
            })
            ->groupBy('attribute_groups.id')
            ->havingRaw("SUM(IF(attributes.code IN($str),1,0)) = $count")
            ->select('attribute_groups.attribute_family_id')
            ->first();

        return $grups;

    }

    public function variants($product_id){
        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();

        return $this->productFlatRepository->where('product_flat.channel', $channel)
            ->where('product_flat.locale', $locale)
//            ->whereNotNull('product_flat.url_key')
            ->where('product_flat.status',1)
            ->whereIn('product_flat.product_id',function($query) use($product_id) {
                $query->select('products.id')->from('products')->where('products.parent_id',$product_id);
            })
            ->get();

    }
}