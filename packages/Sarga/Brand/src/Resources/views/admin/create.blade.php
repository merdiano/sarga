@extends('admin::layouts.content')

@section('page_title')
    Add Brand
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.brand.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
        </form>
    </div>
@stop