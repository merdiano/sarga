<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SCurrencyTableSeeder extends Seeder
{
    public function run(){
        DB::table('currencies')->insert([
            [
                'id'     => 3,
                'code'   => 'TL',
                'name'   => 'Lira',
                'symbol' => 't',
            ], [
                'id'     => 4,
                'code'   => 'TMT',
                'name'   => 'Manat',
                'symbol' => 'm',
            ]
        ]);
    }
}