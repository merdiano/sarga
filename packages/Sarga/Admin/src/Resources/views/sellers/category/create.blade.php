@extends('admin::layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.sellers.category.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.marketplace.seller.category.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ route('admin.dashboard.index') }}';"></i>

                        {{ __('marketplace::app.admin.sellers.category.add-title') }}

                        {{-- {{ Config::get('carrier.social.facebook.url') }} --}}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('marketplace::app.admin.sellers.category.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                            <div class="control-group" :class="[errors.has('seller_id') ? 'has-error' : '']">
                                <label for="seller_id" class="required">{{ __('marketplace::app.admin.sellers.category.seller') }}</label>
                                <select name="seller_id" id="seller_id" class="control"
                                data-vv-as="&quot;{{ __('marketplace::app.admin.sellers.category.seller') }}&quot;"
                                >
                                    @foreach ($sellers as $seller)
                                        <option value="{{$seller->id}}"> {{$seller->customer->name}}</option>
                                    @endforeach

                                </select>

                                <span class="control-error" v-if="errors.has('seller_id')">@{{ errors.first('seller_id') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                                <label for="type" class="required">Type</label>
                                <select name="type" id="type" class="control"  data-vv-as="&quot;Type&quot;">
                                    <option value="main">Main</option>
                                    <option value="promo">Promo</option>
                                    <option value="featured">Featured</option>
                                </select>
                            </div>

                            @if ($categories->count())
                                <tree-view behavior="normal" value-field="id" name-field="categories" input-type="checkbox" items='@json($categories)' value='' fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>

                            @endif
                </div>
            </div>
        </form>
    </div>
@stop