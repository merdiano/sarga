<?php

namespace Sarga\Shop\Repositories;

use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Sarga\Brand\Repositories\BrandRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeOptionTranslationRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Facades\Cart;
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

    protected $fillableTypes = ['sku', 'name', 'url_key', 'short_description', 'description', 'price', 'weight', 'status','source','favoritesCount'];

    public function __construct(AttributeRepository $attributeRepository,
                                App $app,
                                AttributeGroupRepository $attributeGroupRepo,
                                ProductFlatRepository $productFlatRepository,
                                ProductAttributeValueRepository $productAttributeValueRepository,
                                ProductImageRepository $productImageRepository,
                                VendorProductRepository $vendorProductRepository,
                                VendorRepository $vendorRepository,
                                BrandRepository $brandRepository,
                                AttributeOptionRepository $optionRepository,
                                AttributeOptionTranslationRepository $optionTranslationRepository

    )
    {
        $this->attributeGroupRepo = $attributeGroupRepo;
        $this->optionRepository = $optionRepository;
        $this->optionTranslationRepository = $optionTranslationRepository;
        $this->attributeValueRepository = $productAttributeValueRepository;
        $this->productFlatRepository = $productFlatRepository;
        $this->imageRepository = $productImageRepository;
        $this->vendorProductRepository = $vendorProductRepository;
        $this->brandRepository = $brandRepository;
        $this->vendorRepository = $vendorRepository;

        parent::__construct($attributeRepository, $app);
    }

    /**
     * Get all products.
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getAll($categoryId = null)
    {
        $params = request()->input();

        if (core()->getConfigData('catalog.products.storefront.products_per_page')) {
            $pages = explode(',', core()->getConfigData('catalog.products.storefront.products_per_page'));

            $perPage = isset($params['limit']) ? (! empty($params['limit']) ? $params['limit'] : 9) : current($pages);
        } else {
            $perPage = isset($params['limit']) && ! empty($params['limit']) ? $params['limit'] : 9;
        }

        $page = Paginator::resolveCurrentPage('page');

        $repository = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($params, $categoryId) {
            $channel = core()->getRequestedChannelCode();

            $locale = core()->getRequestedLocaleCode();

            $qb = $query->distinct()
                ->select('product_flat.*')
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale);
//                ->whereNotNull('product_flat.url_key');

            if ($categoryId) {
                $qb->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                    ->whereIn('product_categories.category_id', explode(',', $categoryId));
            }

            if(isset($params['brand'])) {
                $qb->whereIn('product_flat.brand_id', explode(',', $params['brand']));
            }

            if(isset($params['discount']) && $params['discount']){
                $qb->whereNotNull('product_flat.special_price')
                    ->where('product_flat.special_price','>',0);
            }

            if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                $qb = $this->checkOutOfStockItem($qb);
            }

            if (is_null(request()->input('status'))) {
                $qb->where('product_flat.status', 1);
            }

            if (is_null(request()->input('visible_individually'))) {
                $qb->where('product_flat.visible_individually', 1);
            }

//            if(isset($params['color'])){
//                $qb->whereIn('product_flat.color', explode(',', $params['color']));
//            }

            if(isset($params['size'])){
                $qb->whereIn('product_flat.size', explode(',', $params['size']));
            }
//            if (isset($params['search'])) {
//                $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');
//            }
//
//            if (isset($params['name'])) {
//                $qb->where('product_flat.name', 'like', '%' . urldecode($params['name']) . '%');
//            }
//
//            if (isset($params['url_key'])) {
//                $qb->where('product_flat.url_key', 'like', '%' . urldecode($params['url_key']) . '%');
//            }

            # sort direction
            $orderDirection = 'asc';
            if (isset($params['order']) && in_array($params['order'], ['desc', 'asc'])) {
                $orderDirection = $params['order'];
            } else {
                $sortOptions = $this->getDefaultSortByOption();

                $orderDirection = ! empty($sortOptions) ? $sortOptions[1] : 'asc';
            }

            if (isset($params['sort'])) {
                $qb = $this->checkSortAttributeAndGenerateQuery($qb, $params['sort'], $orderDirection);
            } else {
                $sortOptions = $this->getDefaultSortByOption();
                if (! empty($sortOptions)) {
                    $qb = $this->checkSortAttributeAndGenerateQuery($qb, $sortOptions[0], $orderDirection);
                }
            }
//select distinct `product_flat`.* from `product_flat` inner join `product_categories` on `product_categories`.`product_id` = `product_flat`.`product_id` where `product_flat`.`locale` = 'tm' and `product_flat`.`url_key` is not null and `product_categories`.`category_id` in (6) and `product_flat`.`status` = 1 and `product_flat`.`visible_individually` = 1 and `product_flat`.`color` in (24435) group by `product_flat`.`id` order by `product_flat`.`created_at` desc
//select distinct `product_flat`.id,`product_flat`.name, `product_flat`.color, `product_flat`.size, `product_flat`.locale, `product_flat`.product_id,`product_flat`.parent_id, `product_flat`.visible_individually,`product_flat`.url_key from `product_flat` left join `product_categories` on `product_categories`.`product_id` = `product_flat`.`product_id` where `product_flat`.`locale` = 'tm' and `product_flat`.`url_key` is not null and `product_categories`.`category_id` in (6) and `product_flat`.`status` = 1 and `product_flat`.`visible_individually` = 1 group by `product_flat`.`id` order by `product_flat`.`created_at` desc limit 10;
//select distinct `product_flat`.id,`product_flat`.name, `product_flat`.color, `product_flat`.size, `product_flat`.locale, `product_flat`.product_id,`product_flat`.parent_id, `product_flat`.visible_individually,`product_flat`.url_key from `product_flat` left join `product_categories` on `product_categories`.`product_id` = `product_flat`.`product_id` inner join `product_flat` as `variants` on `product_flat`.`id` = COALESCE(variants.parent_id, variants.id) left join `product_attribute_values` on `product_attribute_values`.`product_id` = `variants`.`product_id` where `product_flat`.`locale` = 'tm' and `product_categories`.`category_id` in (6,7) and `product_flat`.`status` = 1 and `product_flat`.`visible_individually` = 1 and ((`product_attribute_values`.`attribute_id` = 23 and (find_in_set(24437, product_attribute_values.integer_value)))) group by `variants`.`id`, `product_flat`.`id` having COUNT(*) = 1 limit 100;
            if ($priceFilter = request('price')) {
                $priceRange = explode(',', $priceFilter);

                if (count($priceRange) > 0) {
                    $customerGroupId = null;

                    if (auth()->guard()->check()) {
                        $customerGroupId = auth()->guard()->user()->customer_group_id;
                    } else {
                        $customerGuestGroup = app('Webkul\Customer\Repositories\CustomerGroupRepository')->getCustomerGuestGroup();

                        if ($customerGuestGroup) {
                            $customerGroupId = $customerGuestGroup->id;
                        }
                    }

                    $this->variantJoin($qb);
                    if($priceRange[0]<1){
                        $priceRange[0]=1;
                    }
                    $qb
                        ->leftJoin('catalog_rule_product_prices', 'catalog_rule_product_prices.product_id', '=', 'variants.product_id')
                        ->leftJoin('product_customer_group_prices', 'product_customer_group_prices.product_id', '=', 'variants.product_id')
                        ->where(function ($qb) use ($priceRange, $customerGroupId) {
                            $qb->where(function ($qb) use ($priceRange) {
                                $qb
                                    ->where('variants.min_price', '>=', core()->convertToBasePrice($priceRange[0]))
                                    ->where('variants.min_price', '<=', core()->convertToBasePrice(end($priceRange)));
                            })
                                ->orWhere(function ($qb) use ($priceRange) {
                                    $qb
                                        ->where('catalog_rule_product_prices.price', '>=', core()->convertToBasePrice($priceRange[0]))
                                        ->where('catalog_rule_product_prices.price', '<=', core()->convertToBasePrice(end($priceRange)));
                                })
                                ->orWhere(function ($qb) use ($priceRange, $customerGroupId) {
                                    $qb
                                        ->where('product_customer_group_prices.value', '>=', core()->convertToBasePrice($priceRange[0]))
                                        ->where('product_customer_group_prices.value', '<=', core()->convertToBasePrice(end($priceRange)))
                                        ->where('product_customer_group_prices.customer_group_id', '=', $customerGroupId);
                                });
                        });
                }
            }

            $attributeFilters = $this->attributeRepository
                ->getProductDefaultAttributes(array_keys(
                    request()->except([
                        'price',
//                        'color',
                        'size'
                    ])
                ));

            if (count($attributeFilters) > 0) {
                $this->variantJoin($qb);

                $qb->where(function ($filterQuery) use ($attributeFilters) {
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

                # this is key! if a product has been filtered down to the same number of attributes that we filtered on,
                # we know that it has matched all of the requested filters.
                $qb->groupBy('variants.id');
                $qb->havingRaw('COUNT(*) = ' . count($attributeFilters));
            }

            return $qb->groupBy('product_flat.id');

        });

        # apply scope query so we can fetch the raw sql and perform a count


        $repository->applyScope();


        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
//        Log::info($repository->model->toSql());
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        $results = new LengthAwarePaginator($items, $count, $perPage, $page, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        return $results;
    }

    public function getDiscountedProducts($categoryId = null){

        $results = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($categoryId) {
            $channel = core()->getRequestedChannelCode();
            $locale = core()->getRequestedLocaleCode();

            $query->distinct()
                ->addSelect('product_flat.*')


//                ->where('product_flat.min_price','>','product_flat.max_price')
                ->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1)
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale);

            if ($categoryId) {
                $query->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                    ->whereIn('product_categories.category_id', explode(',', $categoryId));
            }
            return $query->inRandomOrder();
        })->whereNotNull('product_flat.special_price')
            ->where('product_flat.special_price','>',0)->paginate(10);

        return $results;
    }

    public function getPopularProducts($categoryId = null)
    {

        $results = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($categoryId) {
            $channel = core()->getRequestedChannelCode();
            $locale = core()->getRequestedLocaleCode();

            $query->distinct()
                ->addSelect('product_flat.*')
                ->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1)
                ->whereNotNull('product_flat.favoritesCount')
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale);

            if ($categoryId) {
                $query->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                    ->whereIn('product_categories.category_id', explode(',', $categoryId));
            }
            return $query->orderBy('product_flat.favoritesCount','desc');
        })->paginate(request()->input('limit')??10);

        return $results;
    }

    private function calculatePrice($price){
        $originalPrice = Arr::get($price, 'originalPrice.value');
        $discountedPrice = Arr::get($price, 'discountedPrice.value');

        $price_attributes = [
//            'min_price'=>$discountedPrice,'max_price'=>$originalPrice
        ];

        if($originalPrice > $discountedPrice){
            $price_attributes['price'] = $originalPrice;
            $price_attributes['special_price'] = $discountedPrice;
        }
        else{
            $price_attributes['price'] = $discountedPrice;
        }

        return $price_attributes;
    }

    public function createProduct($data){
        $time_start = microtime(true);

        $product['sku'] = $data['product_group_id'];
//        return array_map(fn($value): int => $value * 2, range(1, 5));

        $product['type'] = (!empty($data['color_variants'])  || !empty($data['size_variants'])) ? 'configurable':'simple';

        $attributes = Arr::only($data,['source','cinsiyet']);

        try {
            DB::beginTransaction();

            if (array_key_exists('attributes', $data)) {
                $attributes [] = Arr::collapse($data['attributes']);

                $grp = $this->getAttributeFamily(array_keys($attributes));

                $product['attribute_family_id'] = $grp ? $grp->attribute_family_id :
                    (($product['type'] == 'configurable') ? 2 : 1);//default_configurable_product: default_simple_prodcut
            } else
                $product['attribute_family_id'] = $product['type'] == 'configurable' ? 2 : 1;

            if(!empty($data['brand']))
            {
                $code = Str::slug($data['brand']);

                if(! $brand = $this->brandRepository->findOneByField('code',$code))
                    $brand = $this->brandRepository->create(['name' => $data['brand'],
                    'code' => $code]);

                $product['brand_id'] = $brand->id;
                if(!empty($data['categories'])) {
                    $existing_ids = $brand->categories()->whereIn('id', $data['categories'])->pluck('id');
                    $brand->categories()->attach(array_diff($data['categories'],$existing_ids->toArray()));
                }
            }
            //create product
            $parentProduct = $this->getModel()->create($product);

            $desc = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']));

            $main_attributes = [
                'sku' => $parentProduct->sku,
                'product_number' => $data['product_number'],
                'name' => $data['name'],
                'weight' => $data['weight'] ?? 0.45,
                'source' => $data['url_key'],
                'status' => 1,
                'visible_individually' => 1,
                'url_key' => $parentProduct->sku,
                'short_description' => $desc,
                'description' => $desc,
                'favoritesCount' => $data['favorite_count']
            ];

            if (!empty($data['images'])) {
                $this->assignImages($parentProduct, $data['images']);
            }

            if(!empty($data['categories'])){
                $parentProduct->categories()->attach($data['categories']);
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
//                            $first = reset( $colorVariant['size_variants'] );
                            foreach ($colorVariant['size_variants'] as $sizeVariant)
                            {
                                $sizeNumber = $sizeVariant['itemNumber'] ?:$sizeVariant['attributeValue'];
                                if($variant = $this->createVariant($parentProduct, "{$data['product_group_id']}-{$colorVariant['product_number']}-{$sizeNumber}"))
                                {
                                    if(!empty($data['categories'])){
                                        $variant->categories()->attach($data['categories']);
                                    }

                                    $this->assignImages($variant, $colorVariant['images']);
                                    $attributes = [
                                        'sku' => $variant->sku,
                                        'product_number' => "{$colorVariant['product_number']}-{$sizeVariant['itemNumber']}",
                                        'color' => $this->getAttributeOptionId('color', $colorVariant['color']),
                                        'name' => $colorVariant['name'],
                                        'size' => $this->getAttributeOptionId('size', $sizeVariant['attributeValue']),
//                                        'price' => $sizeVariant['price'],
                                        'weight' => $colorVariant['weight'] ?? 0.45,
                                        'status' => 1,
                                        'visible_individually' => 1,
                                        'url_key' => $variant->sku,
                                        'source' => $colorVariant['url_key'],
                                        'description' => $description,
                                        'short_description' => $description,
                                        'favoritesCount' => $colorVariant['favorite_count']
                                    ];

//                                    $attributes[] = $this->calculatePrice($sizeVariant['price']);
                                    $this->assignAttributes($variant, array_merge($attributes,$this->calculatePrice($sizeVariant['price'])));
                                }
                            }
                        }
                        elseif($variant = $this->createVariant($parentProduct, "{$data['product_group_id']}-{$colorVariant['product_number']}"))
                        {
                            if(!empty($data['categories'])){
                                $variant->categories()->attach($data['categories']);
                            }
                            $this->assignImages($variant, $colorVariant['images']);
                            $attributes = [
                                'sku' => $variant->sku,
                                'product_number' => $colorVariant['product_number'],
                                'color' => $this->getAttributeOptionId('color', $colorVariant['color']),
                                'name' => $colorVariant['name'],
//                                'price' => Arr::get($colorVariant, 'price.discountedPrice.value'),
                                'weight' => $colorVariant['weight'] ?? 0.45,
                                'status' => 1,
                                'visible_individually' => 1,
                                'url_key' => $variant->sku,
                                'source' => $colorVariant['url_key'],
                                'description' => $description,
                                'short_description' => $description,
                                'favoritesCount' => $colorVariant['favorite_count']
                            ];

                            $this->assignAttributes($variant, array_merge($attributes,$this->calculatePrice($colorVariant['price'])));
                        }
                    }
                }
                if (!empty($data['size_variants'])) {
                    $attribute = $this->attributeRepository->findOneByField('code', 'size');
                    $parentProduct->super_attributes()->attach($attribute->id);
                    foreach ($data['size_variants'] as $sizeVariant) {
                        if($variant = $this->createVariant($parentProduct, "{$data['product_group_id']}-{$data['product_number']}-{$sizeVariant['itemNumber']}")){
                            if(!empty($data['categories'])){
                                $variant->categories()->attach($data['categories']);
                            }
                            $this->assignImages($variant, $data['images']);
                            $desc = implode(array_map(fn($value): string => '<p>' . $value['description'] . '</p>', $data['descriptions']));
                            $attributes = [
                                'sku' => $variant->sku,
                                'size' => $this->getAttributeOptionId('size', $sizeVariant['attributeValue']),
                                'product_number' => "{$data['product_number']}-{$sizeVariant['itemNumber']}",
                                'name' => $data['name'],
//                                'price' => $sizeVariant['price'],
                                'weight' => $data['weight'] ?? 0.45,
                                'status' => 1,
                                'featured'=> 0,
                                'new' => 0,
                                'visible_individually' => 1,
                                'url_key' => $variant->sku,
                                'source' => $data['url_key'],
                                'description' => $desc,
                                'short_description' => $desc,
                                'favoritesCount' => $data['favorite_count']
                            ];

                            if (!empty($data['color'])) {
                                $attributes['color'] = $this->getAttributeOptionId('color', $data['color']);
                            }

                            $this->assignAttributes($variant, array_merge($attributes,$this->calculatePrice($sizeVariant['price'])));
                        }
                    }
                }
                if($variant){
                    $parentProduct->getTypeInstance()->setDefaultVariantId($variant->id);
                }
            }
            else{
                $main_attributes = array_merge($main_attributes, $this->calculatePrice($data['price']));
            }

            $this->assignAttributes($parentProduct, $main_attributes);

            if($data['vendor'] && $seller = $this->vendorRepository->findOneByField('url',$data['vendor'])){
                $this->createSellerProduct($parentProduct, $seller->id);
            }

            Event::dispatch('catalog.product.create.after', $parentProduct);
            DB::commit();

            return $parentProduct;
        }
        catch(\Exception $ex){
            DB::rollBack();
            Log::error($ex->getMessage());
            return false;
        }

    }
    /**
     * Variant join.
     *
     * @param  mixed  $query
     * @return void
     */
    private function variantJoin($query)
    {
        static $alreadyJoined = false;

        if (! $alreadyJoined) {
            $alreadyJoined = true;

            $query
                ->join('product_flat as variants', 'product_flat.id', '=', DB::raw('COALESCE(' . DB::getTablePrefix() . 'variants.parent_id, ' . DB::getTablePrefix() . 'variants.id)'))
                ->leftJoin('product_attribute_values', 'product_attribute_values.product_id', '=', 'variants.product_id');
        }
    }
    public function updateProduct($product,$data){
        $time_start = microtime(true);

        try{
            DB::beginTransaction();
            if($product->type === 'simple'){

                $this->updateAttribute($product,$data);

            }
            elseif($product->type === 'configurable'){
                if (!empty($data['color_variants'])) {
                    foreach ($data['color_variants'] as $colorVariant) {
                        if (!empty($colorVariant['size_variants']))
                            foreach ($colorVariant['size_variants'] as $sizeVariant) {
                                if($variant = $this->findOneByField('sku', "{$data['product_group_id']}-{$colorVariant['product_number']}-{$sizeVariant['itemNumber']}"))
                                    $this->updateAttribute($variant,$sizeVariant);
                            }
                        elseif($variant = $this->findOneByField('sku', "{$data['product_group_id']}-{$colorVariant['product_number']}"))
                        {
                            $this->updateAttribute($variant,$colorVariant);
                        }
                    }

                }

                if (!empty($data['size_variants'])){
                    foreach ($data['size_variants'] as $sizeVariant) {
                        if($variant = $this->findOneByField('sku', "{$data['product_group_id']}-{$data['product_number']}-{$sizeVariant['itemNumber']}"))
                        {
                            $this->updateAttribute($variant,$data);
                        }
                    }
                }
            }
            Event::dispatch('catalog.product.update.after', $product);
            DB::commit();
            return true;
        }
        catch(\Exception $ex){
            DB::rollBack();
            Log::error($ex);
//            Log::info($data);
            return false;
        }
    }
    private function updateAttribute($product,$data){
//        $flat = $product->
        if(isset($data['is_sellable']) && !$data['is_sellable']){
            //$attribute = $this->attributeRepository->findOneByField('code', 'status'); status id = 8
            $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>8],['boolean_value'=>0]);

        }else{
            $originalPrice = Arr::get($data, 'price.originalPrice.value');
            $discountedPrice = Arr::get($data, 'price.discountedPrice.value');

            if($discountedPrice >= $originalPrice){
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>11],['float_value'=>$discountedPrice]);// price id 11
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>13],['float_value'=>null]);//special price id 13
            }else{
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>11],['float_value'=>$originalPrice]);// price id 11
                $this->attributeValueRepository->updateOrCreate(['product_id'=>$product->id,'attribute_id'=>13],['float_value'=>$discountedPrice]);//special price id 13
            }
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
            if (isset($params['new'])){
                $qb->where('product_flat.new', $params['new']);
            }

            if (isset($params['featured'])){
                $qb->where('product_flat.featured', $params['featured']);
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
                'condition' => 'new',
            ]);
        }

        Event::dispatch('marketplace.catalog.product.create.after', $sellerProduct);

        return $sellerProduct;
    }

//    private function assignBrand($product, $brand_name){
//        $brand = $this->brandRepository->firstOrCreate([
//            'name' => $brand_name,
//            'code' => Str::slug($brand_name),
//        ]);
//        $product->brand()->associate($brand);
////        $product->save();
////        $brand->products()->attach($product);
////        $brand->save();
//    }

    private function assignImages($product,$images){
        foreach($images as $image){
            $this->imageRepository->create([
                'type' => 'cdn',
                'path' => $image,
                'product_id' => $product->id,
            ]);
        }
    }
    /**
     * Search product by attribute.
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchProductByAttribute($term)
    {
        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();

        if (config('scout.driver') == 'algolia') {
            $results = app(ProductFlatRepository::class)->getModel()::search('query', function ($searchDriver, string $query, array $options) use ($term, $channel, $locale) {
                $queries = explode('_', $term);

                $options['similarQuery'] = array_map('trim', $queries);

                $searchDriver->setSettings([
                    'attributesForFaceting' => [
                        'searchable(locale)',
                        'searchable(channel)',
                    ],
                ]);

                $options['facetFilters'] = ['locale:' . $locale, 'channel:' . $channel];

                return $searchDriver->search($query, $options);
            })
                ->where('status', 1)
                ->where('visible_individually', 1)
                ->orderBy('product_id', 'desc')
                ->paginate(request()->input('limit')??10);
        } else if (config('scout.driver') == 'elastic') {
            $queries = explode('_', $term);

            $results = app(ProductFlatRepository::class)->getModel()::search(implode(' OR ', $queries))
                ->where('status', 1)
                ->where('visible_individually', 1)
                ->where('channel', $channel)
                ->where('locale', $locale)
                ->orderBy('product_id', 'desc')
                ->paginate(request()->input('limit')??10);
        } else {
            $results = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($term, $channel, $locale) {

                $query = $query->distinct()
                    ->addSelect('product_flat.*')
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key');

                if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                    $query = $this->checkOutOfStockItem($query);
                }

                return $query->where('product_flat.status', 1)
                    ->where('product_flat.visible_individually', 1)
                    ->where(function ($subQuery) use ($term) {
                        $queries = explode('_', $term);

                        foreach (array_map('trim', $queries) as $value) {
                            $subQuery->orWhere('product_flat.name', 'like', '%' . urldecode($value) . '%')
                                ->orWhere('product_flat.short_description', 'like', '%' . urldecode($value) . '%');
                        }
                    })
                    ->orderBy('product_id', 'desc');
            })->paginate(request()->input('limit')??10);
        }

        return $results;
    }
    private function assignAttributes($product, $attributes){
        foreach($attributes as $code => $value){
            if(! $attribute = $this->attributeRepository->findOneByField('code', $code))
            {
                continue;
            }

            $attr = [
                'product_id'   => $product->id,
                'attribute_id' => $attribute->id,
                'value'        => $value
            ];

            if($attribute->value_per_channel){
                $attr['channel'] = config('app.channel');
            }

            try {
                if ($attribute->value_per_locale){
                    foreach (core()->getAllLocales() as $locale){
                        $attr['locale'] = $locale->code;
                        $this->attributeValueRepository->create($attr);
                    }

                }else{
                    $this->attributeValueRepository->create($attr);
                }
            }
            catch(\Exception $ex){
//                Log::info($attr);
                Log::error($ex->getMessage());
            }

        }
    }

    private function createVariant($product, $sku){
        try{
            return $this->getModel()->create([
                'parent_id'           => $product->id,
                'type'                => 'simple',
                'attribute_family_id' => $product->attribute_family_id,
                'sku'                 => $sku,
                'brand_id'            => $product->brand_id
            ]);
        }
        catch(\Exception $ex){
            Log::info($ex->getMessage());
            return false;
        }


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
                $option = $this->optionRepository->create([
                    'admin_name' => $new_option,
                    'sort_order' => $order,
                    'attribute_id' =>  $attribute->id
                ]);
                $this->optionTranslationRepository->create(['attribute_option_id'=>$option->id,'label'=> $new_option,'locale'=>'tm']);
            }
            $options = array_merge($options,$new_options);
        }

        return $options;
    }

    private function getAttributeOptionId($attr,$value){
        $attribute_id = $this->attributeRepository->getAttributeByCode($attr)->id;

        $option = $this->optionRepository->findOneWhere(['attribute_id'=>$attribute_id,'admin_name'=>$value]);

        if(! $option){
            $option =$this->optionRepository->create(['attribute_id'=>$attribute_id,'admin_name'=>$value,'sort_order'=>1000]);
            $this->optionTranslationRepository->create(['attribute_option_id'=>$option->id,'label'=>$value,'locale'=>'tm']);
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