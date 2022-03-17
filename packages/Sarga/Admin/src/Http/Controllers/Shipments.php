<?php

namespace Sarga\Admin\src\Http\Controllers;

use Webkul\Admin\Http\Controllers\Sales\ShipmentController;

class Shipments extends ShipmentController
{
    public function isInventoryValidate(&$data){
        return true;
    }
}