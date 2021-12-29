<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 12/29/2021
 * Time: 10:23
 */

namespace Sarga\Admin\Listeners;


use Illuminate\Support\Facades\Log;
use Webkul\Attribute\Repositories\AttributeOptionRepository;

class Sellers
{
    protected $optionRepository;
    public function __construct(AttributeOptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    public function edit($seller){
        Log::info(json_encode($seller));
        return view('sarga_admin::sellers.seller-brand');
    }
}