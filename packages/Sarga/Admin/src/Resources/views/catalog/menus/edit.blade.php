@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.edit-title') }}
@stop

@push('css')
    <style>
        @media only screen and (max-width: 768px){
            .content-container .content .page-header .page-title .control-group .control{
                width: 100% !important;
                margin-top:-25px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode();
        @endphp

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.catalog.menus.index') }}'"></i>

                        {{ __('admin::app.catalog.categories.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.catalog.menus.update', $category->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.categories.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian title="{{ __('admin::app.catalog.categories.general') }}" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('{{$locale}}[name]') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.categories.name') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="{{$locale}}[name]" value="{{ old($locale)['name'] ?? ($category->translate($locale)['name'] ?? '') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.name') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[name]')">@{{ errors.first('{!!$locale!!}[name]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.catalog.categories.visible-in-menu') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.categories.visible-in-menu') }}&quot;">
                                    <option value="1" {{ $category->status ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.categories.yes') }}
                                    </option>
                                    <option value="0" {{ $category->status ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.categories.no') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') ?: $category->position }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian title="{{ __('admin::app.catalog.categories.description-and-images') }}" :active="true">
                        <div slot="body">
                            <description></description>
                        </div>
                    </accordian>

                    @if ($categories->count())

                        <accordian title="{{ __('admin::app.catalog.categories.parent-category') }}" :active="true">
                            <div slot="body">

                                <tree-view value-field="id" name-field="parent_id" input-type="radio" items='@json($categories)' value='@json($category->parent_id)' fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>

                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.after', ['category' => $category]) !!}
                    @endif

                </div>
            </div>
        </form>

    </div>
@stop

@push('scripts')
    @include('admin::layouts.tinymce')

    <script type="text/x-template" id="description-template">
        <div class="control-group" :class="[errors.has('{{$locale}}[description]') ? 'has-error' : '']">
            <label for="description" :class="isRequired ? 'required' : ''">{{ __('admin::app.catalog.categories.description') }}
                <span class="locale">[{{ $locale }}]</span>
            </label>
            <textarea v-validate="isRequired ? 'required' : ''" class="control" id="description" name="{{$locale}}[description]" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old($locale)['description'] ?? ($category->translate($locale)['description'] ?? '') }}</textarea>
            <span class="control-error" v-if="errors.has('{{$locale}}[description]')">@{{ errors.first('{!!$locale!!}[description]') }}</span>
        </div>
    </script>

    <script>
        Vue.component('description', {
            template: '#description-template',

            inject: ['$validator'],

            data: function() {
                return {
                    isRequired: true,
                }
            },

            created: function () {
                let self = this;

                $(document).ready(function () {
                    $('#display_mode').on('change', function (e) {
                        if ($('#display_mode').val() != 'products_only') {
                            self.isRequired = true;
                        } else {
                            self.isRequired = false;
                        }
                    })

                    if ($('#display_mode').val() != 'products_only') {
                        self.isRequired = true;
                    } else {
                        self.isRequired = false;
                    }

                    tinyMCEHelper.initTinyMCE({
                        selector: 'textarea#description',
                        height: 200,
                        width: "100%",
                        plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                    });
                });
            }
        });
    </script>
@endpush