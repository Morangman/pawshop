@extends('layouts.admin')

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/order.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.order.index') }}">
        @lang('admin/order.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/order.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <order-create
        :products="{{ json_encode($productByCategory, true) }}"
    ></order-create>
@endsection
