<?php

namespace Webkul\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductFlagReasonTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('marketplace_product_flag_reasons')->delete();

        DB::table('marketplace_product_flag_reasons')->insert([
            [
                'id' => 1,
                'reason' => 'duplicate product',
                'status' => true
            ],
            [
                'id' => 2,
                'reason' => 'damaged product',
                'status' => true
            ],
            [
                'id' => 3,
                'reason' => 'poor product quality',
                'status' => true
            ],
            [
                'id' => 4,
                'reason' => 'over price product',
                'status' => true
            ],
            [
                'id' => 5,
                'reason' => 'missing product parts',
                'status' => true
            ],
            [
                'id' => 6,
                'reason' => 'recieve wrong product',
                'status' => true
            ]
        ]);
    }
}