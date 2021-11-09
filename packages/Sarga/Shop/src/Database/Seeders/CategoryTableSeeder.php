<?php

namespace Sarga\Shop\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Category table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Category\\Database\\Seeders\\CategoryTableSeeder
 */
class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->delete();

        DB::table('category_translations')->delete();

        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'id'         => '1',
                'position'   => '1',
                'image'      => NULL,
                'status'     => '1',
                '_lft'       => '1',
                '_rgt'       => '14',
                'parent_id'  => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '2',
                'position'   => '2',
                'image'      => NULL,
                'status'     => '1',
                '_lft'       => '1',
                '_rgt'       => '14',
                'parent_id'  => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '3',
                'position'   => '3',
                'image'      => NULL,
                'status'     => '1',
                '_lft'       => '1',
                '_rgt'       => '14',
                'parent_id'  => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        DB::table('category_translations')->insert([
            [
                'name'             => 'Trendyol',
                'slug'             => 'trendyol',
                'description'      => 'Trendyol',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '1',
                'locale'           => 'en',
            ],
            [
                'name'             => 'Trendyol',
                'slug'             => 'trendyol',
                'description'      => 'Trendyol',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '1',
                'locale'           => 'ru',
            ],
            [
                'name'             => 'Trendyol',
                'slug'             => 'trendyol',
                'description'      => 'Trendyol',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '1',
                'locale'           => 'tm',
            ],
            [
                'name'             => 'Trendyol',
                'slug'             => 'trendyol',
                'description'      => 'Trendyol',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '1',
                'locale'           => 'tr',
            ],
            //lcw
            [
                'name'             => 'LCW',
                'slug'             => 'lcw',
                'description'      => 'LCW',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '2',
                'locale'           => 'en',
            ],
            [
                'name'             => 'LCW',
                'slug'             => 'lcw',
                'description'      => 'LCW',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '2',
                'locale'           => 'ru',
            ],
            [
                'name'             => 'LCW',
                'slug'             => 'lcw',
                'description'      => 'LCW',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '2',
                'locale'           => 'tm',
            ],
            [
                'name'             => 'LCW',
                'slug'             => 'lcw',
                'description'      => 'LCW',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '2',
                'locale'           => 'tr',
            ],
            //outlet
            [
                'name'             => 'Outlet',
                'slug'             => 'outlet',
                'description'      => 'Outlet',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '3',
                'locale'           => 'en',
            ],
            [
                'name'             => 'Outlet',
                'slug'             => 'outlet',
                'description'      => 'Outlet',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '3',
                'locale'           => 'ru',
            ],
            [
                'name'             => 'Outlet',
                'slug'             => 'outlet',
                'description'      => 'Outlet',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '3',
                'locale'           => 'tm',
            ],
            [
                'name'             => 'Outlet',
                'slug'             => 'outlet',
                'description'      => 'Outlet',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '3',
                'locale'           => 'tr',
            ]
        ]);
    }
}
