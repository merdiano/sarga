<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SLocalesTableSeeder extends Seeder
{
    public function run(){
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        DB::table('locales')->insert([
            [
                'id'   => 1,
                'code' => 'tr',
                'name' => 'Türkçe',
            ], [
                'id'   => 2,
                'code' => 'TM',
                'name' => 'Türkmençe',
            ], [
                'id'   => 3,
                'code' => 'ru',
                'name' => 'Russian',
            ], [
                'id'   => 4,
                'code' => 'en',
                'name' => 'English',
            ]]);
    }
}