@extends('layouts.admin')

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/product.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.product.index') }}">
        @lang('admin/product.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/product.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <product-create
        :categories="{{ $categories }}"
        :recommended="{{ $recommended }}"
    ></product-create>
@endsection
