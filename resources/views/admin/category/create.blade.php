@extends('layouts.admin')

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/category.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.category.index') }}">
        @lang('admin/category.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/category.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <category-create
        :category="{{ json_encode($category) }}"
        :faqs="{{ json_encode($faqs) }}"
        :categories="{{ json_encode($categories) }}"
        :categorysteps="{{ json_encode($categorysteps) }}"
        :steps="{{ json_encode($steps) }}"
        :prices="{{ json_encode($prices) }}"
        :premiumprices="{{ json_encode($premiumPrices) }}"
    ></category-create>
@endsection
