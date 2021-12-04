<?php

namespace Webkul\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SellerFlagReasonTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('marketplace_seller_flag_reasons')->delete();

        DB::table('marketplace_seller_flag_reasons')->insert([
            [
                'id' => 1,
                'reason' => 'duplicate product sold by seller',
                'status' => true
            ],
            [
                'id' => 2,
                'reason' => 'damaged product sold by seller',
                'status' => true
            ],
            [
                'id' => 3,
                'reason' => 'poor product quality sold by seller',
                'status' => true
            ],
            [
                'id' => 4,
                'reason' => 'over price product sold by seller',
                'status' => true
            ],
            [
                'id' => 5,
                'reason' => 'wrong product sold by seller',
                'status' => true
            ]
        ]);
    }
}