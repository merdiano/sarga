<?php

namespace Webkul\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigTableSeeder::class);
        $this->call(ProductFlagReasonTableSeeder::class);
        $this->call(SellerFlagReasonTableSeeder::class);
    }
}
