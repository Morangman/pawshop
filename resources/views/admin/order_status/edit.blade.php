@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/order-status.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.order-status.index') }}">
        @lang('admin/order-status.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/order-status.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <status-edit
        :status="{{ $status }}"
    ></status-edit>
@endsection
