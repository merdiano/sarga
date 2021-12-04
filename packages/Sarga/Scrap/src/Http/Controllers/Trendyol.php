<?php
namespace Sarga\Scrap\Http\Controllers;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Product\Repositories\ProductFlatRepository;

class Trendyol extends Controller
{

    protected $productRepository;
    public function __construct(ProductFlatRepository $productFlatRepository)
    {
        $this->productRepository = $productFlatRepository;
    }

    public function index(){
        if($url = request('url')){

        }
        return [];
    }

    private function scrapProduct($url){

    }
}