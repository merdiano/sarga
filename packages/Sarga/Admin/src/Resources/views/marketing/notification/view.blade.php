@extends('admin::layouts.content')

@section('page_title')
    {{ __('Edit notification Message') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.push.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.push.index') }}'"></i>

                        {{ __('Edit notification') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Send') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.marketing.templates.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                                <label for="title" class="required">{{ __('Title max: 500') }}</label>
                                <input v-validate="'required'" class="control" id="title" name="title" value="{{ old('title')?:$notification->title }}" data-vv-as="&quot;{{ __('Title') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                            </div>


                            <div class="control-group" :class="[errors.has('content') ? 'has-error' : '']">
                                <label for="content" class="required">{{ __('admin::app.marketing.templates.content') }} (max:3000)</label>
                                <textarea v-validate="'required'" class="control" id="content" name="content" data-vv-as="&quot;{{ __('admin::app.marketing.templates.content') }}&quot;">{{ old('content')?:$notification->content }}</textarea>
                                <span class="control-error" v-if="errors.has('content')">@{{ errors.first('content') }}</span>
                            </div>
                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@endsection