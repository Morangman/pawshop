@extends('layouts.admin')

@php
    $count = \App\Order::query()->where('ordered_status', '=', \App\Order::STATUS_ORDER_DELIVERED)->count();
@endphp

@section('title', "($count) " . Lang::get('admin/order.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/order.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/order.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <order-index
        :searched="{{ json_encode($orders, true) }}"
        :statuses="{{ json_encode($statuses, true) }}"
        :isnew="{{ json_encode($is_new, true) }}"
        :istransit="{{ json_encode($is_transit, true) }}"
        :isdelivered="{{ json_encode($is_delivered, true) }}"
        :ordersstatus="{{ json_encode($orders_status, true) }}"
        :ispayed="{{ json_encode($is_payed, true) }}"
        :isreceived="{{ json_encode($is_received, true) }}"
        :iscancelled="{{ json_encode($is_cancelled, true) }}"
    ></order-index>
@endsection
