<?php

namespace Sarga\Shop\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('core_config')->delete();

        $now = Carbon::now();

        DB::table('core_config')->insert([
            'id'           => 1,
            'code'         => 'catalog.products.guest-checkout.allow-guest-checkout',
            'value'        => '0',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 2,
            'code'         => 'emails.general.notifications.emails.general.notifications.verification',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 3,
            'code'         => 'emails.general.notifications.emails.general.notifications.registration',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 4,
            'code'         => 'emails.general.notifications.emails.general.notifications.customer',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 5,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-order',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 6,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-admin',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 7,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-invoice',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 8,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-refund',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 9,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-shipment',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 10,
            'code'         => 'emails.general.notifications.emails.general.notifications.new-inventory-source',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 11,
            'code'         => 'emails.general.notifications.emails.general.notifications.cancel-order',
            'value'        => '1',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('core_config')->insert([
            'id'           => 12,
            'code'         => 'catalog.products.homepage.out_of_stock_items',
            'value'        => '0',
            'channel_code' => null,
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        DB::table('core_config')->insert([
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'tm',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.compare_option',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'ru',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            //Wishlist show config data
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'tm',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.wishlist_option',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => 'ru',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            /* Image search core config data starts here */
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'en',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'tr',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'tm',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'code'         => 'general.content.shop.image_search',
                'value'        => '0',
                'channel_code' => 'default',
                'locale_code'  => 'ru',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ]);

        DB::table('core_config')->insert(
            [
                'code'         => 'customer.settings.social_login.enable_facebook',
                'value'         => '0',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );

        DB::table('core_config')->insert(
            [
                'code'         => 'customer.settings.social_login.enable_twitter',
                'value'         => '0',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );

        DB::table('core_config')->insert(
            [
                'code'         => 'customer.settings.social_login.enable_google',
                'value'         => '0',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );

        DB::table('core_config')->insert(
            [
                'code'         => 'customer.settings.social_login.enable_linkedin',
                'value'         => '0',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );

        DB::table('core_config')->insert(
            [
                'code'         => 'customer.settings.social_login.enable_github',
                'value'         => '0',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );
    }
}