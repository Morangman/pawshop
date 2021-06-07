@extends('layouts.admin')

@section('title', Lang::get('admin/coupon.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/coupon.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.coupon.index') }}">
        @lang('admin/coupon.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/coupon.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <coupon-create
        :categories="{{ json_encode($categories) }}"
    ></coupon-create>
@endsection
