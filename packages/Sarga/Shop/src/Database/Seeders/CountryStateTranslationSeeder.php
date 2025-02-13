<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CountryStateTranslationSeeder extends Seeder
{
    public function run()
    {

        foreach (['ru', 'tm'] as $code) {
            DB::table('country_translations')->where('locale', $code)->delete();

            DB::table('country_state_translations')->where('locale', $code)->delete();

            $countryTranslations = json_decode(file_get_contents(__DIR__ . '/../../Data/countries_' . $code . '.json'), true);

            data_fill($countryTranslations, '*.locale', $code);

            $stateTranslations = json_decode(file_get_contents(__DIR__ . '/../../Data/states_' . $code . '.json'), true);

            data_fill($stateTranslations, '*.locale', $code);

            DB::table('country_translations')->insert($countryTranslations);

            DB::table('country_state_translations')->insert($stateTranslations);
        }

    }
}
