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
        :order="{{ json_encode($order) }}"
        :bcode="{{ json_encode($barcodeSrc, true) }}"
        :products="{{ json_encode($productByCategory, true) }}"
        :suspect="{{ json_encode($suspectIp, true) }}"
        :states="{{ json_encode($states, true) }}"
        :statuses="{{ json_encode($statuses, true) }}"
        :steps="{{ json_encode($steps, true) }}"
    ></order-edit>
@endsection
