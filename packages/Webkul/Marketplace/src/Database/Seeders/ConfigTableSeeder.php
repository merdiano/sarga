<?php

namespace Webkul\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('core_config')->insert([
            ['code' => 'marketplace.settings.general.status','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.featured','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.new','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.seller_approval_required','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.seller_approval_required','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.product_approval_required','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.commission_per_unit','value' => '10','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.general.can_create_invoice','value' => '0','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.general.can_create_shipment','value' => '0','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.page_title','value' => 'Turn Your Passion Into a Business','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.show_banner','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.layout','value' => 'layout1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.banner_content','value' => 'Shake hand with the most reported company known for eCommerce and the marketplace. We reached around all the corners of the world. We serve the customer with our best service experiences.','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.show_features','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_heading','value' => 'Attracting Features','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_info','value' => 'Want to start an online business? Before any decision, please check our unbeatable features.','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_1','value' => 'Generate Revenue','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_2','value' => 'Sell Unlimited Products','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_3','value' => 'Offer for Sellers','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_4','value' => 'Seller Dashboard','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_5','value' => 'Seller Order Managment','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_6','value' => 'Seller Branding','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_7','value' => 'Connect with Social','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.feature_icon_label_8','value' => 'Buyer Seller Communication','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.show_popular_sellers','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.open_shop_button_label','value' => 'Open Shop Now','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.show_open_shop_block','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.open_shop_info','value' => 'Open your online shop with us and get explore the new world with more then millions of shoppers.','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.landing_page.banner','value' => 'configuration/9OGztMGb6nKUCBbF58xpNA1EShskKjoj9iUvJCrD.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_1','value' => 'configuration/3npLBJCCEnvjtescuelWsENPEm0FzhvElzmFRWIe.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_2','value' => 'configuration/sGtL2WxTxjFypyRMioRth0y4FRJUW6pZEYKfQXq2.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_3','value' => 'configuration/kZZ5OSziGW3aQNVGkq4r4GL2VNTsQhVWLt62C0wb.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_4','value' => 'configuration/cN1NGisKLyVpsn1AldCEQg8ZZCJtSbbd5zTjZGwX.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_5','value' => 'configuration/eSHFNPfIWrw7gLffadeR4FgOgBMeQtxWWxfmB45o.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_6','value' => 'configuration/9Iggsyrd6OElGvYHg27LKfgvgLHx3hBKTXgESxYC.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_7','value' => 'configuration/YvJHOSJLldKpgi0MrgDNy0ookuAyXbYuAtQQI9am.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.feature_icon_8','value' => 'configuration/i7dgjt2Hw5xhUdmploHWMoV0aNml3W4GjEAyZm5e.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.landing_page.about_marketplace','value' => '<div style="width: 100%; display: inline-block; padding-bottom: 30px;"><h1 style="text-align: center; font-size: 24px; color: rgb(36, 36, 36); margin-bottom: 40px;">Why to sell with us</h1><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-customize-seller-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Easily Customize your seller profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Add Unlimited Products</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-add-unlimited-products.png" alt="" style="width: 100%;"></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-connect-to-your-social-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left;text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Connect to your social profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h3 style="color: rgb(99, 99, 99); margin: 0px;">Buyer can ask you a question</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-buyers-can-ask-a-question.png" alt="" style="width: 100%;"></div></div>','channel_code' => 'default','locale_code' => 'en'],

            // velocity configuartipon content

            ['code' => 'marketplace.settings.velocity.page_title','value' => 'Turn Your Passion Into a Business','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.show_banner','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.banner_content','value' => 'Shake hand with the most reported company known for eCommerce and the marketplace. We reached around all the corners of the world. We serve the customer with our best service experiences.','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.show_features','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_heading','value' => 'Attracting Features','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_info','value' => 'Want to start an online business? Before any decision, please check our unbeatable features.','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_1','value' => 'Generate Revenue','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_2','value' => 'Sell Unlimited Products','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_3','value' => 'Offer for Sellers','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_4','value' => 'Seller Dashboard','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_5','value' => 'Seller Order Managment','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_6','value' => 'Seller Branding','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_7','value' => 'Connect with Social','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.feature_icon_label_8','value' => 'Buyer Seller Communication','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.show_popular_sellers','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.open_shop_button_label','value' => 'Open Shop Now','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.show_open_shop_block','value' => '1','channel_code' => NULL,'locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.open_shop_info','value' => 'Open your online shop with us and get explore the new world with more then millions of shoppers.','channel_code' => 'default','locale_code' => 'en'],

            ['code' => 'marketplace.settings.velocity.banner','value' => 'configuration/ftpR2CDQNERkQHzY90Rty5B66WIIQyAkRIZLaxPh.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_1','value' => 'configuration/JKms7R5AeMr4xMpdYMFh6lY97O1c8uJxrOOYrJh2.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_2','value' => 'configuration/EiPYH1PP8tjqHZJAGqXwePS8sqrgQu44BAnLw7Hr.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_3','value' => 'configuration/XqCFcOKK5R7ldPWPUPaXYluQKYA63yXd9GXOAT8B.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_4','value' => 'configuration/PiAzZru2AJ31ahCPQUBmC0ubiBijVDqPLC4agxX0.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_5','value' => 'configuration/tY9AYRKXZaKyE1VMCpWlBALKcdzia7nCHcl3D7U0.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_6','value' => 'configuration/gDQelR5VoHfRL4WWznGYz4ppU2rgF6UWsNhCQGsi.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_7','value' => 'configuration/YdfbYPo8aIdup1UWaNBrQHwtOYupvknPM4UuhRDM.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.feature_icon_8','value' => 'configuration/ji3oMmcj5xenj5EKyCgmiUrkRwPkF5JG3oCrNqde.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.about_marketplace','value' => '<div style="width: 100%; display: inline-block; padding-bottom: 30px;"><h1 style="text-align: center; font-size: 24px; color: rgb(36, 36, 36); margin-bottom: 40px;">Why to sell with us</h1><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-customize-seller-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Easily Customize your seller profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Add Unlimited Products</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-add-unlimited-products.png" alt="" style="width: 100%;"></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 28%; float: left; padding-right: 20px;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-connect-to-your-social-profile.png" alt="" style="width: 100%;"></div> <div style="width: 70%; float: left;text-align: justify;"><h2 style="color: rgb(99, 99, 99); margin: 0px; font-size: 22px;">Connect to your social profile</h2> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div></div> <div style="width: 100%; display: inline-block; padding-bottom: 30px;"><div style="width: 70%; float: left; padding-right: 20px; text-align: justify;"><h3 style="color: rgb(99, 99, 99); margin: 0px;">Buyer can ask you a question</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div> <div style="width: 28%; float: left;"><img src="http://magento2.webkul.com/marketplace/pub/media/wysiwyg/landingpage/img-buyers-can-ask-a-question.png" alt="" style="width: 100%;"></div></div>','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.velocity.setup_icon_1','value' => 'configuration/HcNKzA9bPzfMtnN31ku98lvo42ijDfPGGkauO1Uv.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.setup_icon_2','value' => 'configuration/LoxpyCBvnFold4XeD8c4JGScwIGtxLwOjfiyyXxU.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.setup_icon_3','value' => 'configuration/7HOB5iER96qFtCpdP8JmWy6lw3QoeCY2jRKAzC6U.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.setup_icon_4','value' => 'configuration/KU4TBnxTcbME3ZAwfmVRSqM1mQWuMmIdyhS17toX.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.velocity.setup_icon_5','value' => 'configuration/lgj6ftKJCeCceXtjwK7k668CTnkjshKka0K5mO3w.png','channel_code' => 'default','locale_code' => NULL],
            ['code' => 'marketplace.settings.product_flag.enable','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.product_flag.text','value' => 'Report Product','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.product_flag.guest_can','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.product_flag.reason','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.product_flag.other_reason','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.product_flag.other_placeholder','value' => 'Other','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.seller_flag.enable','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.seller_flag.text','value' => 'Report Seller','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.seller_flag.guest_can','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.seller_flag.reason','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.seller_flag.other_reason','value' => '1','channel_code' => 'default','locale_code' => 'en'],
            ['code' => 'marketplace.settings.seller_flag.other_placeholder','value' => 'Other','channel_code' => 'default','locale_code' => 'en'],
        ]);
    }
}