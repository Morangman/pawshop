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
        :category="{{ $category }}"
        :faqs="{{ json_encode($faqs) }}"
        :categories="{{ json_encode($categories) }}"
        :categorysteps="{{ json_encode($categorysteps) }}"
        :steps="{{ json_encode($steps) }}"
        :prices="{{ json_encode($prices) }}"
        :premiumprices="{{ json_encode($premiumPrices) }}"
    ></product-edit>
@endsection
