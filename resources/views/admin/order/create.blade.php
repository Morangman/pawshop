@extends('layouts.admin')

@php
    $count = \App\Order::query()->where('ordered_status', '=', \App\Order::STATUS_ORDER_DELIVERED)->count();
@endphp

@section('title', "($count) " . Lang::get('admin/order.create.title'))

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
