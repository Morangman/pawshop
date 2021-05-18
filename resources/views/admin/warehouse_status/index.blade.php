@extends('layouts.admin')

@section('title', Lang::get('admin/warehouse-status.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/warehouse-status.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/warehouse-status.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <warehouse-status-index></warehouse-status-index>
@endsection
