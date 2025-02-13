<div class="profile-left-block">

    <div class="content">
        <div class="profile-logo-block">
            @if ($logo = $seller->logo_url)
                <img src="{{ $logo }}" />
            @else
                <img src="{{ bagisto_asset('vendor/webkul/marketplace/assets/images/default-velocity-logo.png') }}" />
            @endif
        </div>

        <div class="profile-information-block">

            <div class="row">

                @if (request()->route()->getName() != 'marketplace.seller.show')

                    <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="shop-title">{{ $seller->shop_title }}</a>

                    @if (! is_null($seller->shop_title))
                        <label class="shop-address">{{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}</label>
                    @endif

                @else

                    <h2 class="shop-title">{{ $seller->shop_title }}</h2>

                    @if ($seller->country)
                        <a target="_blank" href="https://www.google.com/maps/place/{{ $seller->city . ', '. $seller->state . ', ' . core()->country_name($seller->country) }}" class="shop-address">{{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}</a>
                    @endif

                @endif

            </div>

            <div class="row social-links" style="margin-bottom: 5px;">
                @if ($seller->facebook)
                    <a href="https://www.facebook.com/{{$seller->facebook}}" target="_blank">
                        <i class="icon social-icon mp-facebook-icon"></i>
                    </a>
                @endif

                @if ($seller->twitter)
                    <a href="https://www.twitter.com/{{$seller->twitter}}" target="_blank">
                        <i class="icon social-icon mp-twitter-icon"></i>
                    </a>
                @endif

                @if ($seller->instagram)
                    <a href="https://www.instagram.com/{{$seller->instagram}}" target="_blank"><i class="icon social-icon mp-instagram-icon"></i></a>
                @endif

                @if ($seller->pinterest)
                    <a href="https://www.pinterest.com/{{$seller->pinterest}}" target="_blank"><i class="icon social-icon mp-pinterest-icon"></i></a>
                @endif

                @if ($seller->skype)
                    <a href="https://www.skype.com/{{$seller->skype}}" target="_blank">
                        <i class="icon social-icon mp-skype-icon"></i>
                    </a>
                @endif

                @if ($seller->linked_in)
                    <a href="https://www.linkedin.com/{{$seller->linked_in}}" target="_blank">
                        <i class="icon social-icon mp-linked-in-icon"></i>
                    </a>
                @endif

                @if ($seller->youtube)
                    <a href="https://www.youtube.com/{{$seller->youtube}}" target="_blank">
                        <i class="icon social-icon mp-youtube-icon"></i>
                    </a>
                @endif
            </div>

            <div class="row">

                <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

                <?php $productRepository = app('Webkul\Marketplace\Repositories\ProductRepository') ?>

                <?php $sellerFlags = app('Webkul\Marketplace\Repositories\SellerFlagReasonRepository')->findWhere(['status'=>1]) ?>

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

                    <div class="total-reviews">
                        <a href="{{ route('marketplace.reviews.index', $seller->url) }}">
                            {{
                                __('marketplace::app.shop.sellers.profile.total-rating', [
                                        'total_rating' => $reviewRepository->getTotalRating($seller),
                                        'total_reviews' => $reviewRepository->getTotalReviews($seller),
                                    ])
                            }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">

                <a href="{{ route('marketplace.products.index', $seller->url) }}">
                    {{ __('marketplace::app.shop.sellers.profile.count-products', [
                            'count' => $productRepository->getTotalProducts($seller)
                        ])
                    }}
                </a>

            </div>

            <div class="row">

                <a href="#" @click="showModal('contactForm')">{{ __('marketplace::app.shop.sellers.profile.contact-seller') }}</a>

            </div>

            <div class="row seller-flag">
                @if (core()->getConfigData('marketplace.settings.seller_flag.enable') )
                <a href="#" @click="showModal('reportForm')">{{ core()->getConfigData('marketplace.settings.seller_flag.text') ?: 'Report Seller' }}</a>
                @endif

                <modal id="reportForm" :is-open="modalIds.reportForm">
                    <h3 slot="header">
                        {{ __('marketplace::app.shop.flag.title') }}
                    </h3>

                    <div slot="body">
                        <seller-flag-form></seller-flag-form>
                    </div>
                </modal>

            </div>

        </div>

    </div>

</div>

<modal id="contactForm" :is-open="modalIds.contactForm">
    <h3 style="margin-left: 80px;" slot="header">{{ __('marketplace::app.shop.sellers.profile.contact-seller') }}</h3>

    <i class="icon remove-icon "></i>

    <div slot="body">
        <contact-seller-form></contact-seller-form>
        {!! Captcha::renderJS() !!}
    </div>
</modal>

@push('scripts')

    <script type="text/x-template" id="seller-flag-form-template">

        <form method="POST"  action="{{route('marketplace.flag.seller.store')}}"  @submit.prevent="onSubmit"  style ="text-align:left">
            @csrf()

            <input type="hidden" name="seller_id" value="{{ $seller->id }}">

            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                <label for="name" class="required label-style">{{ __('marketplace::app.shop.flag.name') }}</label>
                <input v-validate="'required'" type="text" class="form-style" id="name" name="name" data-vv-as="&quot;{{ __('marketplace::app.shop.flag.name') }}&quot;" value="{{ old('name') }}"/>
                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <label for="type" class="required label-style">{{ __('marketplace::app.shop.flag.email') }}</label>
                <input type="email" class="form-style" v-validate="'required'" id="email" name="email" data-vv-as="&quot;{{ __('marketplace::app.shop.flag.email') }}&quot;" value="{{ old('email') }}" />
                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('reason') ? 'has-error' : '']">
                <label for="reason" class="label-style required">{{ __('marketplace::app.shop.flag.reason') }}</label>
                <select name="reason" id="reason"  class="form-style" v-model="reason"  >
                    @foreach ($sellerFlags as $flag)
                        <option value="{{$flag->reason}}">{{$flag->reason}}</option>
                    @endforeach
                    <option value="other">Other</option>
                </select>
                @if (core()->getConfigData('marketplace.settings.seller_flag.other_reason'))
                <textarea class="form-style mt-3" v-validate="'required'"  id="other-reason"  v-if="reason == 'other'" placeholder="{{core()->getConfigData('marketplace.settings.seller_flag.other_placeholder')}}"name="reason" data-vv-as="&quot;{{ __('marketplace::app.shop.flag.reason') }}&quot;" value="{{ old('reason') }}"
                ></textarea>
                @endif

                <span class="control-error" v-if="errors.has('reason')">@{{ errors.first('reason') }}</span>
            </div>

            <div class="mt-5">
                @if (core()->getConfigData('marketplace.settings.seller_flag.guest_can'))
                <button type="submit" class="btn btn-lg btn-primary theme-btn">
                    {{ __('marketplace::app.shop.flag.submit') }}
                </button>
                @else
                    <a href="{{route('customer.session.index')}}" class="btn btn-lg btn-primary theme-btn"> {{ __('marketplace::app.shop.flag.submit') }}</a>
                @endif
            </div>

        </form>
    </script>

    <script type="text/x-template" id="contact-form-template">

        <form action="" class="account-table-content" id="contact-form" method="POST" data-vv-scope="contact-form" @submit.prevent="contactSeller('contact-form')">

            @csrf

            <div class="form-container">

                <div class="form-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
                    <label for="name" class="required mandatory">{{ __('marketplace::app.shop.sellers.profile.name') }}</label>
                    <input type="text" v-model="contact.name" class="form-style control" name="name" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.name') }}&quot;">
                    <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
                </div>

                <div class="form-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
                    <label for="email" class="required mandatory">{{ __('marketplace::app.shop.sellers.profile.email') }}</label>
                    <input type="text" v-model="contact.email" class="form-style control" name="email" v-validate="'required|email'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.email') }}&quot;">
                    <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
                </div>

                <div class="form-group" :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
                    <label for="subject" class="required mandatory">{{ __('marketplace::app.shop.sellers.profile.subject') }}</label>
                    <input type="text" v-model="contact.subject" class="control form-style" name="subject" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.subject') }}&quot;">
                    <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
                </div>

                <div class="form-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
                    <label for="query" class="required mandatory">{{ __('marketplace::app.shop.sellers.profile.query') }}</label>
                    <textarea class="control form-style" v-model="contact.query" name="query" v-validate="'required'"  data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.query') }}&quot;">
                    </textarea>
                    <span class="control-error" v-if="errors.has('contact-form.query')">@{{ errors.first('contact-form.query') }}</span>
                </div>

                    {!! Captcha::render() !!}


                <button type="submit" class="btn btn-lg btn-primary theme-btn" :disabled="disable_button"

                >
                    {{ __('marketplace::app.shop.sellers.profile.submit') }}
                </button>

            </div>

        </form>

    </script>

    <script>
        Vue.component('contact-seller-form', {

            data: () => ({
                contact: {
                    'name': '',
                    'email': '',
                    'subject': '',
                    'query': ''
                },

                disable_button: false,
            }),

            template: '#contact-form-template',

            created () {

                @auth('customer')
                    @if(auth('customer')->user())
                        this.contact.email = "{{ auth('customer')->user()->email }}";
                        this.contact.name = "{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}";
                    @endif
                @endauth

            },

            methods: {
                contactSeller (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post ("{{ route('marketplace.seller.contact', $seller->url) }}", this.contact)
                                .then (function(response) {
                                    this_this.disable_button = false;

                                    this_this.$parent.closeModal();

                                    window.flashMessages = [{
                                        'type': 'alert-success',
                                        'message': response.data.message
                                    }];

                                    this_this.$root.addFlashMessages()
                                })

                                .catch (function (error) {
                                    this_this.disable_button = false;

                                    this_this.handleErrorResponse(error.response, 'contact-form')
                                })
                        } else {
                            this_this.disable_button = false;
                        }
                    });
                },

                handleErrorResponse (response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    }
                }
            }
        });

    </script>



    <script>

        Vue.component('seller-flag-form', {

        data: () => ({
            reason: ''
        }),

        template: '#seller-flag-form-template',
        });
    </script>


@endpush