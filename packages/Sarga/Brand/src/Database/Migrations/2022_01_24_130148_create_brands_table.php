<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->string('image')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('category_brands',function (Blueprint $table) {
            $table->integer('category_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });

        Schema::create('product_brands',function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });

        Schema::create('seller_brands',function (Blueprint $table) {
            $table->integer('seller_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->foreign('seller_id')->references('id')->on('marketplace_sellers')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
        Schema::dropIfExists('category_brands');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('seller_brands');
    }
}
