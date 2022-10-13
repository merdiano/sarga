@extends('admin::layouts.content')

@section('page_title')
    {{ __('sarga::app.catalog.scrap.trendyol') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('sarga::app.catalog.scrap.trendyol') }}</h1>
            </div>
        </div>
        <div class="page-content">
            <div class="table">
                <div class="grid-container">
                    <a  target="_blank"
                        href="https://scraper.sarga.tk/init-scraper"
                        class="btn btn-lg btn-primary"
                    >
                        Start Scrap
                    </a>
                    <a  target="_blank"
                        href="https://importer.sarga.tk/init-importer"
                        class="btn btn-lg btn-primary"
                    >
                        Start import
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop