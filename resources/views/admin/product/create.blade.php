@extends('layouts.admin')

@section('title', Lang::get('admin/product.create.title'))

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
        :category="{{ json_encode($category) }}"
        :faqs="{{ json_encode($faqs) }}"
        :categories="{{ json_encode($categories) }}"
        :categorysteps="{{ json_encode($categorysteps) }}"
        :steps="{{ json_encode($steps) }}"
        :prices="{{ json_encode($prices) }}"
        :premiumprices="{{ json_encode($premiumPrices) }}"
        :subcategory="{{ json_encode($subcategory) }}"
        :catimage="{{ json_encode($catImage) }}"
        :productimages="{{ json_encode($productImages) }}"
        :productyears="{{ json_encode($productYears) }}"
    ></product-create>
@endsection
