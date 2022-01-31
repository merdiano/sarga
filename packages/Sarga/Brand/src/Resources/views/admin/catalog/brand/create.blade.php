@extends('admin::layouts.content')

@section('page_title')
    {{ __('brand::app.add_brand') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.brand.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.catalog.brand.index') }}'"></i>

                        {{ __('brand::app.add_brand') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('brand::app.save_brand') }}
                    </button>
                </div>
            </div>
            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <accordian :title="'{{ __('admin::app.catalog.attributes.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('brand::app.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}"
                                       data-vv-as="&quot;{{ __('brand::app.code') }}&quot;" v-code/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('brand::app.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}"
                                       data-vv-as="&quot;{{ __('brand::app.name') }}&quot;" v-code/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.catalog.categories.visible-in-menu') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.categories.visible-in-menu') }}&quot;">
                                    <option value="1">
                                        {{ __('admin::app.catalog.categories.yes') }}
                                    </option>
                                    <option value="0">
                                        {{ __('admin::app.catalog.categories.no') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.categories.description-and-images') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('admin::app.catalog.categories.image') }}</label>

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="image" :multiple="false"></image-wrapper>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.controls.after') !!}
                        </div>
                    </accordian>
                    <accordian :title="'{{ __('marketplace::app.admin.layouts.sellers') }}'" :active="true">
                        <div slot="body">
                                <div class="control-group" :class="[errors.has('sellers[]') ? 'has-error' : '']">
                                    <label for="sellers" class="required">{{ __('marketplace::app.admin.layouts.sellers') }}</label>
                                    <select class="control" name="sellers[]" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.admin.layouts.sellers') }}&quot;" multiple>

                                        @foreach ($sellers as $seller)
                                            <option value="{{ $seller->id }}" {{ old('sellers') && in_array($seller->id, old('sellers')) ? 'selected' : ''}}>
                                                {{ $seller->shop_title}}
                                            </option>
                                        @endforeach

                                    </select>
                                    <span class="control-error" v-if="errors.has('sellers[]')">
                                    @{{ errors.first('sellers[]') }}
                                </span>
                                </div>
                        </div>
                    </accordian>
                    @if ($categories->count())

                        <accordian :title="'{{ __('admin::app.catalog.products.categories') }}'" :active="false">
                            <div slot="body">

                                <tree-view behavior="normal" value-field="id" name-field="categories" input-type="checkbox" items='@json($categories)'
                                            fallback-locale="{{ config('app.fallback_locale') }}">

                                </tree-view>
                            </div>
                        </accordian>
                    @endif
                </div>
            </div>
        </form>
    </div>
@stop