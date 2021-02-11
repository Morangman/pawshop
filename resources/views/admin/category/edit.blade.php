@extends('layouts.admin')

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
        :categories="{{ json_encode($categories) }}"
        :steps="{{ json_encode($steps) }}"
    ></category-edit>
@endsection
