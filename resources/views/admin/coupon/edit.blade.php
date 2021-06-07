@extends('layouts.admin')

@section('title', Lang::get('admin/coupon.edit.title'))

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/coupon.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.coupon.index') }}">
        @lang('admin/coupon.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/coupon.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <coupon-edit
        :coupon="{{ json_encode($coupon) }}"
        :categories="{{ json_encode($categories) }}"
    ></coupon-edit>
@endsection
