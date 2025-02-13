@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.profile.edit-title') }}
@endsection

@section('content')
    <div class="account-layout right mt10">

        <form method="post" action="{{ route('marketplace.account.seller.update', $seller->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data" class="account-table-content">
            <div class="account-head seller-profile-edit mb-10">

                <span class="account-heading">{{ __('marketplace::app.shop.sellers.account.profile.edit-title') }}</span>

                <div class="account-action">
                    <a href="{{ route('marketplace.products.index', $seller->url) }}" target="_blank" class="btn btn-black btn-sm theme-btn">
                        {{ __('marketplace::app.shop.sellers.account.profile.view-collection-page') }}
                    </a>

                    <a href="{{ route('marketplace.seller.show', $seller->url) }}" target="_blank" class="btn btn-black btn-sm theme-btn">
                        {{ __('marketplace::app.shop.sellers.account.profile.view-seller-page') }}
                    </a>

                    <button type="submit" class="btn btn-sm theme-btn">
                        {{ __('marketplace::app.shop.sellers.account.profile.save-btn-title') }}
                    </button>

                </div>

                <div class="horizontal-rule"></div>

            </div>

            {!! view_render_event('marketplace.sellers.account.profile.edit.before', ['seller' => $seller]) !!}

            <div class="account-table-content">

                @csrf()

                <input type="hidden" name="_method" value="PUT">

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.general') }}'" :active="true">
                    <div slot="body">

                        <div class="form-group" :class="[errors.has('shop_title') ? 'has-error' : '']">
                            <label for="shop_title" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.shop_title') }}</label>
                            <input type="text" class="form-style" name="shop_title" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.shop_title') }}&quot;" value="{{ old('shop_title') ?: $seller->shop_title }}">
                            <span class="control-error" v-if="errors.has('shop_title')">@{{ errors.first('shop_title') }}</span>
                        </div>

                        <div class="form-group" :class="[errors.has('profile_background') ? 'has-error' : '']">
                            <label for="profile_background" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.profile-background') }}</label>
                            <input type="color" class="form-style" name="profile_background" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.profile-background') }}&quot;" value="{{ old('profile_background') ?: $seller->profile_background }}">
                            <span class="control-error" v-if="errors.has('profile_background')">@{{ errors.first('profile_background') }}</span>
                        </div>

                        <div class="form-group" :class="[errors.has('url') ? 'has-error' : '']">
                            <label for="url" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.url') }}</label>
                            <input type="text" class="form-style" name="url" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.url') }}&quot;" value="{{ old('url') ?: $seller->url }}">
                            <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="tax_vat">{{ __('marketplace::app.shop.sellers.account.profile.tax_vat') }}</label>
                            <input type="text" class="form-style" name="tax_vat" value="{{ old('tax_vat') ?: $seller->tax_vat }}">
                        </div>

                        <div class="form-group" :class="[errors.has('phone') ? 'has-error' : '']">
                            <label for="phone" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.phone') }}</label>
                            <input type="text" class="form-style" name="phone" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.phone') }}&quot;" value="{{ old('phone') ?: $seller->phone }}">
                            <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                        </div>

                        <div class="form-group" :class="[errors.has('address1') ? 'has-error' : '']">
                            <label for="address1" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.address1') }}</label>
                            <input type="text" class="form-style" name="address1" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.address1') }}&quot;" value="{{ old('address1') ?: $seller->address1 }}">
                            <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="address2">{{ __('marketplace::app.shop.sellers.account.profile.address2') }}</label>
                            <input type="text" class="form-style" name="address2" value="{{ old('address2') ?: $seller->address2 }}">
                        </div>

                        <div class="form-group" :class="[errors.has('city') ? 'has-error' : '']">
                            <label for="city" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.city') }}</label>
                            <input type="text" class="form-style" name="city" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.city') }}&quot;" value="{{ old('city') ?: $seller->city }}">
                            <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                        </div>

                        @include ('shop::customers.account.address.country-state', ['countryCode' => old('country') ?? $seller->country, 'stateCode' => old('state') ?? $seller->state])

                        <div class="form-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                            <label for="postcode" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.profile.postcode') }}</label>
                            <input type="text" class="form-style" name="postcode" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.postcode') }}&quot;" value="{{ old('postcode') ?: $seller->postcode }}">
                            <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                        </div>

                        <i class="icon accordian-up-icon"></i>
                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.media') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group">
                            <label>{{ __('marketplace::app.shop.sellers.account.profile.logo') }}

                            <image-wrapper :button-label="'{{ __('marketplace::app.shop.sellers.account.profile.add-image-btn-title') }}'" input-name="logo" :multiple="false" :images='"{{ $seller->logo_url }}"'></image-wrapper>
                        </div>

                        <div class="form-group">
                            <label>{{ __('marketplace::app.shop.sellers.account.profile.banner') }}

                            <image-wrapper :button-label="'{{ __('marketplace::app.shop.sellers.account.profile.add-image-btn-title') }}'" input-name="banner" :multiple="false" :images='"{{ $seller->banner_url }}"'></image-wrapper>
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.about') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group">
                            <label for="description">{{ __('marketplace::app.shop.sellers.account.profile.about') }}</label>
                            <textarea class="form-style" id="description" name="description">{{ old('description') ?: $seller->description }}</textarea>
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.social_links') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group">
                            <label for="twitter">{{ __('marketplace::app.shop.sellers.account.profile.twitter') }}</label>
                            <input type="text" class="form-style" name="twitter" value="{{ old('twitter') ?: $seller->twitter }}">
                        </div>

                        <div class="form-group">
                            <label for="facebook">{{ __('marketplace::app.shop.sellers.account.profile.facebook') }}</label>
                            <input type="text" class="form-style" name="facebook" value="{{ old('facebook') ?: $seller->facebook }}">
                        </div>

                        <div class="form-group">
                            <label for="youtube">{{ __('marketplace::app.shop.sellers.account.profile.youtube') }}</label>
                            <input type="text" class="form-style" name="youtube" value="{{ old('youtube') ?: $seller->youtube }}">
                        </div>

                        <div class="form-group">
                            <label for="instagram">{{ __('marketplace::app.shop.sellers.account.profile.instagram') }}</label>
                            <input type="text" class="form-style" name="instagram" value="{{ old('instagram') ?: $seller->instagram }}">
                        </div>

                        <div class="form-group">
                            <label for="skype">{{ __('marketplace::app.shop.sellers.account.profile.skype') }}</label>
                            <input type="text" class="form-style" name="skype" value="{{ old('skype') ?: $seller->skype }}">
                        </div>

                        <div class="form-group">
                            <label for="linked_in">{{ __('marketplace::app.shop.sellers.account.profile.linked_in') }}</label>
                            <input type="text" class="form-style" name="linked_in" value="{{ old('linked_in') ?: $seller->linked_in }}">
                        </div>

                        <div class="form-group">
                            <label for="pinterest">{{ __('marketplace::app.shop.sellers.account.profile.pinterest') }}</label>
                            <input type="text" class="form-style" name="pinterest" value="{{ old('pinterest') ?: $seller->pinterest }}">
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.policies') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group">
                            <label for="return_policy">{{ __('marketplace::app.shop.sellers.account.profile.return_policy') }}</label>
                            <textarea class="form-style" id="return_policy" name="return_policy">{{ old('return_policy') ?: $seller->return_policy }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="shipping_policy">{{ __('marketplace::app.shop.sellers.account.profile.shipping_policy') }}</label>
                            <textarea class="form-style" id="shipping_policy" name="shipping_policy">{{ old('shipping_policy') ?: $seller->shipping_policy }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="privacy_policy">{{ __('marketplace::app.shop.sellers.account.profile.privacy_policy') }}</label>
                            <textarea class="form-style" id="privacy_policy" name="privacy_policy">{{ old('privacy_policy') ?: $seller->privacy_policy }}</textarea>
                        </div>

                    </div>
                </accordian>

                @if ($seller->commission_enable)
                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.admin-commission') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group">
                            <label for="commision">{{ __('marketplace::app.shop.sellers.account.profile.admin-commission-percent') }}</label>
                            <input class="form-style" id="commision" value="{{$seller->commission_percentage}}" readonly/>
                        </div>

                    </div>
                </accordian>
                @endif

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.seo') }}'" :active="false">
                    <div slot="body">

                        <div class="form-group">
                            <label for="meta_description">{{ __('marketplace::app.shop.sellers.account.profile.meta_description') }}</label>
                            <textarea class="form-style" id="meta_description" name="meta_description">{{ old('meta_description') ?: $seller->meta_description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">{{ __('marketplace::app.shop.sellers.account.profile.meta_keywords') }}</label>
                            <textarea class="form-style" id="meta_keywords" name="meta_keywords">{{ old('meta_keywords') ?: $seller->meta_keywords }}</textarea>
                        </div>

                    </div>
                </accordian>

                @if (core()->getConfigData('marketplace.settings.minimum_order_amount.enable'))
                    <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.minimum_amount') }}'" :active="false">
                        <div slot="body">

                            <div class="form-group">
                                <label for="min_order_amount">{{ __('marketplace::app.shop.sellers.account.profile.min_order_amount') }}</label>
                                <input class="form-style" v-validate="'decimal:3'" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') ?: $seller->min_order_amount }}"/>
                                <span class="control-error" v-if="errors.has('min_order_amount')">@{{ errors.first('min_order_amount') }}</span>
                            </div>

                        </div>
                    </accordian>
                @endif

                @if (core()->getConfigData('marketplace.settings.google_analytics.enable'))
                    <accordian :title="'{{ __('marketplace::app.shop.sellers.account.profile.google_analytics') }}'" :active="false">
                        <div slot="body">

                            <div class="form-group">
                                <label for="analytics_id">{{ __('marketplace::app.shop.sellers.account.profile.google_analytics_id') }}</label>
                                <input class="form-style"  id="google_analytics_id" name="google_analytics_id" value="{{ old('google_analytics_id') ?: $seller->google_analytics_id }}"/>
                                <span class="control-error" v-if="errors.has('google_analytics_id')">@{{ errors.first('google_analytics_id') }}</span>
                            </div>

                        </div>
                    </accordian>
                @endif

            </div>

            {!! view_render_event('marketplace.sellers.account.profile.edit.after', ['seller' => $seller]) !!}

        </form>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>

        let stateCheck = setInterval(() => {
            if (document.readyState === 'complete') {
                clearInterval(stateCheck);
                tinymce.init({
                selector: 'textarea#description,textarea#return_policy,textarea#shipping_policy,textarea#privacy_policy',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]',
                templates: [
                    { title: 'Test template 1', content: 'Test 1' },
                    { title: 'Test template 2', content: 'Test 2' }
                ],
            });
            }
        }, 100);
    </script>
@endpush