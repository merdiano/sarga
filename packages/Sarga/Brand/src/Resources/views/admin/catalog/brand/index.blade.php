@extends('admin::layouts.content')
@section('page_title')
    {{ __('brand::app.brands') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('brand::app.brands') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.brand.create') }}" class="btn btn-lg btn-primary">
                    {{ __('brand::app.add_brand') }}
                </a>
            </div>
        </div>
        <div class="page-content">

            {!! app('Sarga\Brand\DataGrids\BrandDataGrid')->render() !!}

        </div>
    </div>
@stop