@extends('marketplace::shop.layouts.master')

@section('content-wrapper')
    <div class="account-content">
        <div class="sidebar left">
            @include('shop::customers.account.partials.sidemenu')
        </div>

        <div class="account-layout">
            @yield('content')
        </div>

    </div>
@stop