<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SLocalesTableSeeder extends Seeder
{
    public function run(){
        DB::table('locales')->insert([

            [
                'id'   => 6,
                'code' => 'tm',
                'name' => 'Türkmençe',
            ], [
                'id'   => 7,
                'code' => 'ru',
                'name' => 'Rusça',
            ]]);
    }
}