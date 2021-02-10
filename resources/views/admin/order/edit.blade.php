@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/order.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.order.index') }}">
        @lang('admin/order.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/order.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <order-edit
        :order="{{ $order }}"
        :products="{{ json_encode($productByCategory, true) }}"
        :suspect="{{ json_encode($suspectIp, true) }}"
    ></order-edit>
@endsection
