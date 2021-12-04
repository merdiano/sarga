@inject('reviewRepository', 'Webkul\Marketplace\Repositories\ReviewRepository')

@if (core()->getConfigData('marketplace.settings.velocity.show_banner'))
<div class="banner-container-layout-3">

    {{-- @if (core()->getConfigData('marketplace.settings.velocity.banner'))
        <img class="banner" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.banner')) }}"/>
    @else
    <img class="banner" src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/banner-3.png" />
    @endif --}}

    <div class="banner-content">
        <h1>
            {{ core()->getConfigData('marketplace.settings.layout3.page_title') ?? __('marketplace::app.shop.marketplace.title') }}
        </h1>

        @if ($bannerContent = core()->getConfigData('marketplace.settings.layout3.banner_content'))
        <p>
            {!! $bannerContent !!}
        </p>
        @endif

        <div class="account-action">
            <a href="{{ route('customer.register.index') }}" class="btn btn-lg theme-btn">
                {{  __('marketplace::app.shop.marketplace.layout3.register') }}
            </a>
        </div>

    </div>
    <div class="banner-image">
        <img class="img-fluid" src="{{asset('vendor/webkul/marketplace/assets/images/layout-3-banner.png')}}" alt="">
    </div>
</div>
@endif

@if (core()->getConfigData('marketplace.settings.velocity.show_features'))
<div class="feature-container container layout-3">
    <div class="feature-heading">
        <h2>{{ core()->getConfigData('marketplace.settings.velocity.feature_heading') ?? __('marketplace::app.shop.marketplace.features') }}
        </h2>

        <p>{{ core()->getConfigData('marketplace.settings.velocity.feature_info') ?? __('marketplace::app.shop.marketplace.features-info') }}
        </p>
    </div>

    <div class="container feature-list-layout-3">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <img class="img-fluid"
                        src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_1')) }}" />
                    <div class="card-body">
                        <h5 class="card-title">{{core()->getConfigData('marketplace.settings.layout3.feature_icon_1_title')}}</h5>
                        <p class="card-text">{!! core()->getConfigData('marketplace.settings.layout3.feature_icon_1_content') !!}</p>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <img class="img-fluid"
                        src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_1')) }}" />
                    <div class="card-body">
                        <h5 class="card-title">{{core()->getConfigData('marketplace.settings.layout3.feature_icon_2_title')}}</h5>
                        <p class="card-text">{!! core()->getConfigData('marketplace.settings.layout3.feature_icon_2_content') !!}</p>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <img class="img-fluid"
                        src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_1')) }}" />
                    <div class="card-body">
                        <h5 class="card-title">{{core()->getConfigData('marketplace.settings.layout3.feature_icon_3_title')}}</h5>
                        <p class="card-text">{!! core()->getConfigData('marketplace.settings.layout3.feature_icon_3_content') !!}</p>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endif
<div class="setup-step-container">
    <div class="setup-heading">
        <h2>{{ __('marketplace::app.shop.marketplace.setup-title') }}</h2>

        <p>{{ __('marketplace::app.shop.marketplace.setup-info') }}</p>
    </div>

    <ul class="velocity-setup-step-list">
        <li>
            <img
                src="{{ asset('vendor/webkul/marketplace/assets/images/l3-create-an-account.png') }}" />

            <span>{{ __('marketplace::app.shop.marketplace.setup-1') }}</span>
        </li>

        <li>
            <img
                src="{{ asset('vendor/webkul/marketplace/assets/images/l3-add-shop.png') }}" />

            <span>{{ __('marketplace::app.shop.marketplace.setup-2') }}</span>
        </li>

        <li>
            <img
                src="{{ asset('vendor/webkul/marketplace/assets/images/l3-customize-profile.png') }}" />

            <span>{{ __('marketplace::app.shop.marketplace.setup-3') }}</span>
        </li>

        <li>
            <img
                src="{{ asset('vendor/webkul/marketplace/assets/images/l3-add-products.png') }}" />

            <span>{{ __('marketplace::app.shop.marketplace.setup-4') }}</span>
        </li>

        <li>
            <img
                src="{{ asset('vendor/webkul/marketplace/assets/images/l3-start-selling.png') }}" />

            <span>{{ __('marketplace::app.shop.marketplace.setup-5') }}</span>
        </li>
    </ul>
</div>
@if (core()->getConfigData('marketplace.settings.velocity.show_popular_sellers'))
<?php $popularSellers = app('Webkul\Marketplace\Repositories\SellerRepository')->getPopularSellers(); ?>

@if ($popularSellers->count())
    <div class="velocity-popular-sellers-container" style="display: inline-block; width: inherit;">
        <div class="popular-sellers-heading">
            {{ __('marketplace::app.shop.marketplace.popular-sellers') }}
        </div>

        @foreach ($popularSellers as $seller)
            <div class="wrapper mb20">
                <div class="velocity-card-layout-2">
                    <div class="profile-upper-layout-2">
                        <div class="profile-image center-info">
                            @if ($logo = $seller->logo_url)
                                <img class="img-fluid" src="{{ $logo }}"/>
                            @else
                                <img class="img-fluid" src="{{ asset('vendor/webkul/marketplace/assets/images/default-velocity-logo.png') }}" />
                            @endif
                        </div>
                    </div>

                    <div class="profile-information">
                        <div class="center-info">
                            <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="shop-title">{{ $seller->shop_title }}</a>

                            @if ($seller->country)
                                <label class="shop-address">
                                    {{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}
                                </label>
                            @endif

                            <div class="review-info">
                                <span class="number">
                                    {{ $reviewRepository->getAverageRating($seller) }}
                                </span>

                                <div class="star-review">
                                    <star-ratings
                                        ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                                    push-class="mr5"
                                    ></star-ratings>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endif
<div class="query-section layout-3">
    <div class="query-container container">

            <div class="query-icon">
                <img class="img-fluid" src="{{asset('vendor/webkul/marketplace/assets/images/query.png')}}" alt="">
            </div>
            <div class="query-content">
                <h3>{{__('marketplace::app.shop.marketplace.layout2.query-help')}}</h3>
                <ul>
                    <li> <p>{{__('marketplace::app.shop.marketplace.layout2.query-help-tag-1')}}</p> </li>
                    <li> <p>{{__('marketplace::app.shop.marketplace.layout2.query-help-tag-2')}}</p> </li>
                    <li> <p>{{__('marketplace::app.shop.marketplace.layout2.query-help-tag-3')}}</p> </li>
                </ul>

                <div class="query-button">
                    <a class="btn theme-btn" href="#" @click="showModal('sellerQueryForm')">{{__('marketplace::app.shop.marketplace.layout2.query-btn')}}</a>

                    <modal id="sellerQueryForm" :is-open="modalIds.sellerQueryForm">
                        <h3  slot="header">{{__('marketplace::app.shop.marketplace.layout2.query-btn')}}</h3>

                        <i class="icon remove-icon "></i>

                        <div slot="body">
                           @include('marketplace::shop.seller-central.queryform')
                        </div>
                    </modal>
                </div>
            </div>


    </div>
</div>