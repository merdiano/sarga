<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_brands',function (Blueprint $table) {
            $table->integer('menu_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('brand_id')->references('id')->on('brands');
        });

        Schema::create('menu_categories',function (Blueprint $table) {
            $table->integer('menu_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::create('menu_translations',function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->integer('menu_id')->unsigned();
            $table->string('locale');
            $table->unique(['menu_id', 'locale']);
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->integer('locale_id')->nullable()->unsigned();
            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_brands');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('menus_translations');
        Schema::dropIfExists('menus');
    }
}
