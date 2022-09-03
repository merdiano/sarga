@php
    $locale = core()->getRequestedLocaleCode();
@endphp

@extends('admin::layouts.content')

@section('page_title')
    {{ __('sarga_admin::app.catalog.menus.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('sarga_admin::app.catalog.menus.title') }}</h1>
            </div>

            <div class="page-action">
                <a
                    href="{{ route('admin.catalog.menus.create') }}"
                    class="btn btn-lg btn-primary"
                >
                    {{ __('sarga_admin::app.catalog.menus.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.catalog.menus.index') }}"></datagrid-plus>
        </div>

    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function() {
            $("input[type='checkbox']").change(deleteCategory);
        });

        /**
         * Delete category function. This function name is present in category datagrid.
         * So outside scope function should be loaded `onclick` rather than `v-on`.
         */
        let deleteCategory = function(e, type) {
            let indexes;

            if (type == 'delete') {
                indexes = $(e.target).parent().attr('id');
            } else {
                $("input[type='checkbox']").attr('disabled', true);

                let formData = {};
                $.each($('form').serializeArray(), function(i, field) {
                    formData[field.name] = field.value;
                });

                indexes = formData.indexes;
            }


        }

        /**
         * Reload page.
         */
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);

            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }
    </script>
@endpush
