<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SCurrencyTableSeeder extends Seeder
{
    public function run(){
        DB::table('channels')->delete();

        DB::table('currencies')->delete();

        DB::table('currencies')->insert([
            [
                'id'     => 1,
                'code'   => 'TL',
                'name'   => 'Lira',
                'symbol' => 'TL',
            ], [
                'id'     => 2,
                'code'   => 'TMT',
                'name'   => 'Manat',
                'symbol' => 'TMT',
            ]
        ]);
    }
}