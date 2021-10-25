<?php
use Faker\Generator as Faker;

$factory->define(\Webkul\Product\Models\Product::class, function (Faker $faker) {
    $productName = $faker->userName;

    $sku = substr(strtolower(str_replace(array('a','e','i','o','u'), '', $productName)), 0, 6);

    $productSku = str_replace(' ', '', $sku) . "-". str_replace(' ', '', $sku) . "-" . rand(1,9999999) . "-" . rand(1,9999999);

    return [
        'sku' => $productSku,
        'attribute_family_id'=>1
    ];
});
