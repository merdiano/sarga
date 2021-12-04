@extends('marketplace::shop.layouts.master')

@section('page_title')
    {{ __('marketplace::app.shop.marketplace.title') }}
@stop

@section('content-wrapper')
    <div class="main seller-central-container">
        @php
            $theme_template = core()->getConfigData('marketplace.settings.landing_page.layout') ?? 'layout1';
        @endphp
        @include('marketplace::shop.seller-central.'. $theme_template)
    </div>

@endsection