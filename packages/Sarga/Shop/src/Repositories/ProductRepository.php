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
//            $channel = core()->getRequestedChannelCode();

            $locale = 'tm';//core()->getRequestedLocaleCode();

            $qb = $query->distinct()
                ->select('product_flat.*')
//                ->where('product_flat.channel', $channel)
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
//
            if (is_null(request()->input('visible_individually'))) {
                $qb->where('product_flat.visible_individually', 1);
            }

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
//                        'size'
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

    public function getDiscountedProducts($seller_id,$categoryId = null){

        $results = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($seller_id,$categoryId) {
            $channel = core()->getRequestedChannelCode();
            $locale = core()->getRequestedLocaleCode();

            $query->distinct()
                ->addSelect('product_flat.*')

                ->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1)
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->join('marketplace_products', 'product_flat.product_id', '=', 'marketplace_products.product_id')
                ->where('marketplace_products.marketplace_seller_id', $seller_id);
            if ($categoryId) {
                $query->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                    ->whereIn('product_categories.category_id', explode(',', $categoryId));
            }
            return $query->inRandomOrder();
        })->whereNotNull('product_flat.special_price')
            ->where('product_flat.special_price','>',0)->paginate(10);

        return null;
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
        } else if (config('scout.driver') == 'meilisearch') {
            $queries = explode('_', $term);

            $results = app(ProductFlatRepository::class)->getModel()::search(implode(' OR ', $queries))
                ->where('status', 1)
                ->where('visible_individually', 1)
                ->orderBy('name')
//                ->where('channel', $channel)
//                ->where('locale', $locale)

                ->paginate(request()->input('limit')??50);
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

    public function getAttributeOptionId($attr,$value){
        $attribute_id = $this->attributeRepository->getAttributeByCode($attr)->id;

        $option = $this->optionRepository->findOneWhere(['attribute_id'=>$attribute_id,'admin_name'=>$value]);

        if(! $option){
            $option =$this->optionRepository->create(['attribute_id'=>$attribute_id,'admin_name'=>$value,'sort_order'=>1000]);
            $this->optionTranslationRepository->create(['attribute_option_id'=>$option->id,'label'=>$value,'locale'=>'tm']);
        }

        return $option->id;
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