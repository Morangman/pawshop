@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/product.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.product.index') }}">
        @lang('admin/product.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/product.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <product-edit
        :product="{{ $product }}"
        :categories="{{ $categories }}"
        :recommended="{{ $recommended }}"
    ></product-edit>
@endsection
