@extends('layouts.admin')

@section('title', Lang::get('admin/category.edit.title'))

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/category.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.category.index') }}">
        @lang('admin/category.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/category.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <category-edit
        :category="{{ $category }}"
        :faqs="{{ json_encode($faqs) }}"
        :categories="{{ json_encode($categories) }}"
    ></category-edit>
@endsection
