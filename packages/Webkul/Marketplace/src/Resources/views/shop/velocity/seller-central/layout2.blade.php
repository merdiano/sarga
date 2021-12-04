@if (core()->getConfigData('marketplace.settings.velocity.show_banner'))
<div class="banner-container layout2">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-sm-12 ">
                <div class="banner-content-layout-2">
                    <h1>
                        {{ core()->getConfigData('marketplace.settings.layout2.page_title') ?? __('marketplace::app.shop.marketplace.title') }}
                    </h1>

                    @if ($bannerContent = core()->getConfigData('marketplace.settings.layout2.banner_content'))
                        <p>
                            {!! $bannerContent !!}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">

                <form
                    method="post"
                    action="{{ route('customer.register.create') }}"
                    @submit.prevent="onSubmit"
                    class="seller-registration-form"
                >
                    <div class="reg-heading">
                        <h3> {{__('marketplace::app.shop.marketplace.layout2.become-seller')}}</h3>
                        <p>{{__('marketplace::app.shop.marketplace.layout2.become-seller-tag')}}</p>
                    </div>

                <carousel :per-page="1" :autoplay ="false" :navigation-enabled="true" pagination-color="#4D7EA8" pagination-active-color="#4D7EA8" navigation-next-label= "{{__('marketplace::app.shop.marketplace.layout2.proceed')}}" navigation-prev-label="{{__('marketplace::app.shop.marketplace.layout2.back')}}" :pagination-enabled="false" >
                    <slide>

                        <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                            <label for="first_name" class="required label-style">
                                {{ __('shop::app.customer.signup-form.firstname') }}
                            </label>

                            <input
                                type="text"
                                class="form-style"
                                name="first_name"
                                v-validate="'required'"
                                value="{{ old('first_name') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;" />

                            <span class="control-error" v-if="errors.has('first_name')" v-text="errors.first('first_name')"></span>
                        </div>


                        <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                            <label for="last_name" class="required label-style">
                                {{ __('shop::app.customer.signup-form.lastname') }}
                            </label>

                            <input
                                type="text"
                                class="form-style"
                                name="last_name"
                                v-validate="'required'"
                                value="{{ old('last_name') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;" />

                            <span class="control-error" v-if="errors.has('last_name')" v-text="errors.first('last_name')"></span>
                        </div>

                    </slide>
                    <slide>

                        <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                            <label for="password" class="required label-style">
                                {{ __('shop::app.customer.signup-form.password') }}
                            </label>

                            <input
                                type="password"
                                class="form-style"
                                name="password"
                                v-validate="'required|min:6'"
                                ref="password"
                                value="{{ old('password') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.password') }}&quot;" />

                            <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>
                        </div>

                        <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                            <label for="password_confirmation" class="required label-style">
                                {{ __('shop::app.customer.signup-form.confirm_pass') }}
                            </label>

                            <input
                                type="password"
                                class="form-style"
                                name="password_confirmation"
                                v-validate="'required|min:6|confirmed:password'"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.confirm_pass') }}&quot;" />

                            <span class="control-error" v-if="errors.has('password_confirmation')" v-text="errors.first('password_confirmation')"></span>
                        </div>
                    </slide>

                    <slide>
                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="required label-style">
                                {{ __('shop::app.customer.signup-form.email') }}
                            </label>

                            <input
                                type="email"
                                class="form-style"
                                name="email"
                                v-validate="'required|email'"
                                value="{{ old('email') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;" />

                            <span class="control-error" v-if="errors.has('email')" v-text="errors.first('email')"></span>
                        </div>

                        <input type="hidden" id="yes" name="want_to_be_seller" value="1" >

                        <seller-controls></seller-controls>
                    </slide>
                  </carousel>


                  <button class="registration-btn" type="submit"> {{__('marketplace::app.shop.marketplace.layout2.proceed')}}</button>


                </form>
            </div>
        </div>
    </div>


</div>
@endif

@inject('reviewRepository', 'Webkul\Marketplace\Repositories\ReviewRepository')

<div class="sell-products">
    <div class="container-fluid">
        <div class="selling-header">
            <h3>{{__('marketplace::app.shop.marketplace.layout2.seller-product')}}</h3>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="sell-steps">
                    <div class="step">
                        <div class="step-number"><p>1</p></div>
                        <div class="step-bar"></div>
                    </div>
                    <div class="sell-image">
                        @if (core()->getConfigData('marketplace.settings.layout2.step_one_image'))
                             <img class="img-fluid" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.layout2.step_one_image')) }}"/>
                        @else
                        <img class="img-fluid" src="{{asset('vendor/webkul/marketplace/assets/images/one.png' )}}" alt="">
                        @endif
                    </div>
                    <div class="sell-content">
                        <h4>{{__('marketplace::app.shop.marketplace.layout2.step1-title')}}</h4>
                        <p>{{__('marketplace::app.shop.marketplace.layout2.step1-content')}}</p>
                    </div>
                </div>
                <div class="sell-steps">
                    <div class="step">
                        <div class="step-number"><p>2</p></div>
                        <div class="step-bar"></div>
                    </div>
                    <div class="sell-image">
                        @if (core()->getConfigData('marketplace.settings.layout2.step_two_image'))
                            <img class="img-fluid" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.layout2.step_two_image')) }}"/>
                        @else
                             <img class="img-fluid" src="{{asset('vendor/webkul/marketplace/assets/images/two.png' )}}" alt="">
                        @endif
                    </div>
                    <div class="sell-content">
                        <h4>{{__('marketplace::app.shop.marketplace.layout2.step2-title')}}</h4>
                        <p>{{__('marketplace::app.shop.marketplace.layout2.step2-content')}}</p>
                    </div>
                </div>
                <div class="sell-steps">
                    <div class="step">
                        <div class="step-number"><p>3</p></div>
                        <div class="step-bar"></div>
                    </div>
                    <div class="sell-image">
                        @if (core()->getConfigData('marketplace.settings.layout2.step_three_image'))
                            <img class="img-fluid" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.layout2.step_three_image')) }}"/>
                        @else
                            <img class="img-fluid" src="{{asset('vendor/webkul/marketplace/assets/images/three.png' )}}" alt="">
                   @endif
                    </div>
                    <div class="sell-content">
                        <h4>{{__('marketplace::app.shop.marketplace.layout2.step3-title')}}</h4>
                        <p>{{__('marketplace::app.shop.marketplace.layout2.step3-content')}}</p>
                    </div>
                </div>
                <div class="sell-steps">
                    <div class="step">
                        <div class="step-number"><p>4</p></div>
                        <div class="step-bar"></div>
                    </div>
                    <div class="sell-image">
                        @if (core()->getConfigData('marketplace.settings.layout2.step_four_image'))
                        <img class="img-fluid" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.layout2.step_four_image')) }}"/>
                        @else
                        <img class="img-fluid" src="{{asset('vendor/webkul/marketplace/assets/images/four.png' )}}" alt="">
                        @endif
                    </div>
                    <div class="sell-content">
                        <h4>{{__('marketplace::app.shop.marketplace.layout2.step4-title')}}</h4>
                        <p>{{__('marketplace::app.shop.marketplace.layout2.step4-content')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (core()->getConfigData('marketplace.settings.velocity.show_features'))
<div class="feature-container container">
    <div class="feature-heading">
        <h2>{{ core()->getConfigData('marketplace.settings.velocity.feature_heading') ?? __('marketplace::app.shop.marketplace.features') }}</h2>

        <p>{{ core()->getConfigData('marketplace.settings.velocity.feature_info') ?? __('marketplace::app.shop.marketplace.features-info') }}</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <ul type="none" class="feature-list-layout-2">
                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_1') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_1'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_1')) }}"/>
                            <div>
                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_1') }}
                                </div>
                                <div class="content">
                                    <ul>
                                        <li>
                                            <p>
                                                Increase the number of customers
                                            </p>
                                        </li>
                                        <li>
                                            <p>

                                                Increase the average transaction size
                                            </p>
                                        </li>
                                        <li>
                                            <p>

                                                Increase the frequency of transactions per customer
                                            </p>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_2') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_2'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_2')) }}"/>

                            <div >
                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_2') }}
                                </div>
                                <div class="content">
                                    <ul>
                                        <li>
                                            <p>
                                                Increase the number of customers
                                            </p>
                                        </li>
                                        <li>
                                            <p>

                                                Increase the average transaction size
                                            </p>
                                        </li>
                                        <li>
                                            <p>

                                                Increase the frequency of transactions per customer
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_3') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_3'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_3')) }}"/>
                            <div>

                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_3') }}
                                </div>
                                  <div class="content">
                                        <ul>
                                            <li>
                                                <p>
                                                    Increase the number of customers
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the average transaction size
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the frequency of transactions per customer
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_4') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_4'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_4')) }}"/>
                            <div>

                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_4') }}
                                </div>
                                  <div class="content">
                                        <ul>
                                            <li>
                                                <p>
                                                    Increase the number of customers
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the average transaction size
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the frequency of transactions per customer
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    @endif


                </ul>
            </div>
            <div class="col-lg-6 col-sm-12">
                <ul type="none" class="feature-list-layout-2">

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_5') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_5'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_5')) }}"/>
                            <div>

                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_5') }}
                                </div>
                                  <div class="content">
                                        <ul>
                                            <li>
                                                <p>
                                                    Increase the number of customers
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the average transaction size
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the frequency of transactions per customer
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_6') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_6'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_6')) }}"/>
                            <div>

                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_6') }}
                                </div>
                                  <div class="content">
                                        <ul>
                                            <li>
                                                <p>
                                                    Increase the number of customers
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the average transaction size
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the frequency of transactions per customer
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_7') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_7'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_7')) }}"/>
                            <div>

                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_7') }}
                                </div>
                                  <div class="content">
                                        <ul>
                                            <li>
                                                <p>
                                                    Increase the number of customers
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the average transaction size
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the frequency of transactions per customer
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.velocity.feature_icon_8') && core()->getConfigData('marketplace.settings.velocity.feature_icon_label_8'))
                        <li class="feature-list-li-layout-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.velocity.feature_icon_8')) }}"/>
                            <div>

                                <div class="feature-label">
                                    {{ core()->getConfigData('marketplace.settings.velocity.feature_icon_label_8') }}
                                </div>
                                  <div class="content">
                                        <ul>
                                            <li>
                                                <p>
                                                    Increase the number of customers
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the average transaction size
                                                </p>
                                            </li>
                                            <li>
                                                <p>

                                                    Increase the frequency of transactions per customer
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>


</div>
@endif

@if (core()->getConfigData('marketplace.settings.velocity.show_popular_sellers'))
<?php $popularSellers = app('Webkul\Marketplace\Repositories\SellerRepository')->getPopularSellers(); ?>

@if ($popularSellers->count())
    <div class="velocity-popular-sellers-container" style="display: inline-block; width: inherit;">
        <div class="popular-sellers-heading layout-2">
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

<div class="query-section">
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
            </div>
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



@push('scripts')

    <script type="text/x-template" id="seller-controls-template">

        <div class="seller-form-controls">

            <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']" >
                <label for="url" class="required">{{ __('marketplace::app.shop.sellers.account.signup.shop_url') }}</label>

                <input type="text" class="control form-style" name="url" v-validate="'required|min:3'" min="3" value="{{ old('url') }}" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.signup.shop_url') }}&quot;" v-on:keyup="checkShopUrl($event.target.value)">

                <span class="control-info text-success" v-if="isShopUrlAvailable != null && isShopUrlAvailable">{{ __('marketplace::app.shop.sellers.account.signup.shop_url_available') }}</span>

                <span class="control-info text-danger" v-if="isShopUrlAvailable != null && !isShopUrlAvailable">{{ __('marketplace::app.shop.sellers.account.signup.shop_url_not_available') }}</span>

                <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
            </div>
        </div>

      </div>

    </script>

    <script>

        Vue.component('seller-controls', {
            template: "#seller-controls-template",

            data: () => ({
                isShopUrlAvailable: null
            }),


            methods: {
                checkShopUrl (shopUrl) {
                    this_this = this;

                    if (shopUrl.length < 3) {
                        return;
                    }

                    this.$http.post("{{ route('marketplace.seller.url') }}", {'url': shopUrl})
                        .then(function(response) {
                            if (response.data.available) {
                                this_this.isShopUrlAvailable = true;

                                document.querySelectorAll(".btn-primary")[0].disabled = false;
                            } else {
                                this_this.isShopUrlAvailable = false;
                                document.querySelectorAll(".btn-primary")[0].disabled = true;
                            }
                        })
                        .catch(function (error) {
                            document.querySelectorAll(".btn-primary")[0].disabled = true;
                        })
                },

                toggleButtonDisable (value) {
                    var buttons = document.querySelectorAll(".btn-primary");

                    if (value == 1) {
                        buttons[0].disabled = true;
                    } else {
                        buttons[0].disabled = false;
                    }
                },
            }
        });

        let stateCheck = setInterval(() => {
            if (document.readyState === 'complete') {
                clearInterval(stateCheck);
                document.querySelectorAll('.VueCarousel-navigation-button').forEach(function(button){

                    button.addEventListener('click', function(btn) {

                        if (btn.target.classList.contains('VueCarousel-navigation--disabled') && btn.target.classList.contains('VueCarousel-navigation-next') ) {

                            document.querySelector('.registration-btn').style.height="36px"
                        } else {
                            document.querySelector('.registration-btn').style.height="0px"
                        }
                    } )
                    })
            }
        }, 100);

    </script>

    <style>
        .text-success {
            color: #4CAF50 !important;
        }

        .text-danger {
            color: #FC6868 !important;
        }
    </style>

@endpush