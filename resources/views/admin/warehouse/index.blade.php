@extends('layouts.admin')

@section('title', Lang::get('admin/warehouse.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/warehouse.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/warehouse.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <warehouse-index
        :searched="{{ json_encode($products, true) }}"
        :statuses="{{ json_encode($statuses, true) }}"
        :status="{{ json_encode($status, true) }}"
    ></warehouse-index>
@endsection
