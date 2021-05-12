@extends('layouts.admin')

@section('title', Lang::get('admin/order-status.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/order-status.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.order-status.index') }}">
        @lang('admin/order-status.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/order-status.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <status-create></status-create>
@endsection
