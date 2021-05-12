@extends('layouts.admin')

@section('title', Lang::get('admin/category.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/category.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/category.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <category-index></category-index>
@endsection
