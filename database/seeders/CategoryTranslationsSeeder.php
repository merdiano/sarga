<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Velocity\Database\Seeders\VelocityMetaDataSeeder;
use Webkul\Admin\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;
use Sarga\Shop\Database\Seeders\DatabaseSeeder as SargaDatabaseSeeder;

class CategoryTranslationsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $catTranslations = json_decode(file_get_contents(__DIR__ . '/../data/category-translations.json'), true);
        DB::beginTransaction();

        foreach ($catTranslations as $translation){
            DB::table('category_translations')
                ->where('category_id', $translation['id'])
                ->where('locale', 'tm')
                ->update(array('name'=>$translation['Tm'],$translation['Tm']));
            DB::table('category_translations')
                ->where('category_id', $translation['id'])
                ->where('locale', 'tm')
                ->update(array('name'=>$translation['Rus'],$translation['Rus']));
        }
    }
}
