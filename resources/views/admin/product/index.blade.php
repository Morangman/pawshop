@extends('layouts.admin')

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/product.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/product.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <product-index></product-index>
@endsection
