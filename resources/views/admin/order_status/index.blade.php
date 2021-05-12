@extends('layouts.admin')

@section('title', Lang::get('admin/order-status.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/order-status.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/order-status.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <status-index></status-index>
@endsection
