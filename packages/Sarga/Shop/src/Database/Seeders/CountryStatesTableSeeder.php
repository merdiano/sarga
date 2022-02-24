<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CountryStatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('countries')->delete();
        DB::table('country_states')->delete();

        $countries = json_decode(file_get_contents(__DIR__ . '/../../Data/countries.json'), true);
        $states = json_decode(file_get_contents(__DIR__ . '/../../Data/states.json'), true);

        DB::table('countries')->insert($countries);
        DB::table('country_states')->insert($states);
    }
}