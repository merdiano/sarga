<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Core\Vendor;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;

class Vendors extends Controller
{

    protected $vendorRepository;

    public function __construct(SellerRepository $sellerRepository)
    {
        $this->vendorRepository = $sellerRepository;
    }

    public function index()
    {
        $vendors = $this->vendorRepository->select('marketplace_sellers.id','url','logo','banner','shop_title','categories')
            ->where('is_approved',true)
            ->leftJoin('seller_categories','marketplace_sellers.id','=','seller_categories.seller_id')
            ->get();

        return Vendor::collection($vendors);
    }
}