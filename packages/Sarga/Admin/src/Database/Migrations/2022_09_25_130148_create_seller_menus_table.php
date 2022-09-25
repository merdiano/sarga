<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('filter')->nullable();

        });
        Schema::create('seller_menus',function (Blueprint $table) {
            $table->integer('menu_id')->unsigned();
            $table->integer('seller_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('seller_id')->references('id')->on('brands');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_menus');
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('filter');
        });
    }
}
