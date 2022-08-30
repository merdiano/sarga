<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('app-ads.txt',function(){
    return 'facebook.com, 333307141073441, RESELLER, c3e20eee3f780d68';
});
Route::get('/.well-known/pki-validation/CC905B7DBFC9820D09FF9B24B5F8782C.txt',function (){
    $view = \Illuminate\Support\Facades\Blade::render("{{asset('ssl.txt')}}");
    return $view;
});