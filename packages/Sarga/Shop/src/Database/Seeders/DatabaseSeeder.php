<?php

namespace Sarga\Shop\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder  extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SLocalesTableSeeder::class);
        $this->call(SCurrencyTableSeeder::class);
        $this->call(ChannelTableSeeder::class);
        $this->call(CountryStatesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CountryStateTranslationSeeder::class);

    }
}