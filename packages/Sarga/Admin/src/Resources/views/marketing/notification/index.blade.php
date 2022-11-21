@extends('admin::layouts.content')

@section('page_title')
    {{ __('Notification Messages') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('Notification Messages') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.push.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add notification') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('notificationGrid','Sarga\Admin\DataGrids\NotificationDataGrid')
            {!! $notificationGrid->render() !!}
        </div>
    </div>
@endsection