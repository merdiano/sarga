<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\MpProductRepository as Product;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;
use Webkul\Marketplace\Repositories\ProductRepository as SellerProduct;
use Webkul\Marketplace\Repositories\SellerRepository as Seller;
use Webkul\Marketplace\Repositories\ProductDownloadableLinkRepository;
use Webkul\Marketplace\Repositories\ProductDownloadableSampleRepository;

/**
 * Assign Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AssignProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * InventorySourceRepository object
     *
     * @var array
     */
    protected $inventorySource;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $sellerProduct;

    /**
     * productDownloadableSampleRepository object
     *
     * @var array
     */
    protected $productDownloadableSampleRepository;

    /**
     * productDownloadableLinkRepository object
     *
     * @var array
     */
    protected $productDownloadableLinkRepository;

    /**
     * SellerRepository object
     *
     * @var array
     */
    protected $seller;

    public function __construct(
        InventorySource $inventorySource,
        Product $product,
        SellerProduct $sellerProduct,
        Seller $seller,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        ProductDownloadableSampleRepository $productDownloadableSampleRepository
    )
    {
        $this->inventorySource = $inventorySource;

        $this->product = $product;

        $this->sellerProduct = $sellerProduct;

        $this->seller = $seller;

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        $this->productDownloadableSampleRepository = $productDownloadableSampleRepository;

        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller = $this->seller->findOneWhere(['customer_id' => auth()->guard('customer')->user()->id])->toArray();

        foreach ($seller as $key => $sellerInput) {
            if ($key == 'logo' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.logo'));
            }
            if ($key == 'shop_title' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.shop_title'));
            }
            if ($key == 'address1' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.shop_title'));
            }
            if ($key == 'phone' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.phone'));
            }
            if ($key == 'state' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.state'));
            }
            if ($key == 'city' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.city'));
            }
            if ($key == 'country' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.country'));
            }
            if ($key == 'postcode' && $sellerInput == null) {
                return redirect()->back()->with('warning', __('marketplace::app.shop.sellers.account.profile.validation.postcode'));
            }

        }

        if (request()->input('query')) {
            $results = [];

            foreach ($this->sellerProduct->searchProducts(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->product_id,
                        'sku' => $row->sku,
                        'name' => $row->name,
                        'price' => core()->convertPrice($row->price),
                        'formated_price' => core()->currency($row->price),
                        'base_image' => $row->product->base_image_url,
                    ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $seller = $this->seller->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $product = $this->sellerProduct->findOneWhere([
                        'product_id' => $id,
                        'marketplace_seller_id' => $seller->id,
                    ]);


        if ($product) {
            session()->flash('error', 'You are already selling this product..');

            return redirect()->route('marketplace.account.products.search');
        }

        $baseProduct = $this->product->find($id);

        if ($baseProduct->type != "simple" && $baseProduct->type != "configurable" && $baseProduct->type != "virtual" && $baseProduct->type != "downloadable") {
            session()->flash('error', $baseProduct->type.' product is not allowed to sell');

            return redirect()->route('marketplace.account.products.search');
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('baseProduct', 'inventorySources'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $this->validate(request(), [
            'condition' => 'required',
            'description' => 'required'
        ]);

        $data = array_merge(request()->all(), [
                'product_id' => $id,
                'is_owner' => 0,
            ]);

        $product = $this->sellerProduct->createAssign($data);

        if ($product->product->type == 'downloadable') {

            session()->flash('warning', 'Please fill downloadable fields');

            return redirect()->route('marketplace.account.products.edit-assign', $product->id);
        }

        session()->flash('success', 'Product created successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->sellerProduct->findorFail($id);

        if ($product->parent) {
            return redirect()->route('marketplace.account.products.edit-assign', ['id' => $product->parent->id]);
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('product', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'condition' => 'required',
            'description' => 'required'
        ]);

        $data = request()->all();

        $this->sellerProduct->updateAssign($data, $id);

        session()->flash('success', 'Product updated successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Uploads downloadable file
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadLink($id)
    {
        return response()->json(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }


    /**
     * Uploads downloadable sample file
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadSample($id)
    {
        return response()->json(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }
}