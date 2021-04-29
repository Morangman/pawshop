@extends('layouts.admin')

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
    ></order-index>
@endsection
