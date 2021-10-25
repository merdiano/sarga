<?php
use Faker\Generator as Faker;

$factory->define(\Webkul\Product\Models\ProductInventory::class, function (Faker $faker,$data) {

    return [
        'product_id' => $data['product_id'],
        'inventory_source_id' => $data['inventory_source_id'],
        'qty' =>rand(1,999)
    ];
});