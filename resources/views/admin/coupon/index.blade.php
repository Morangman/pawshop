@extends('layouts.admin')

@section('title', Lang::get('admin/coupon.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/coupon.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/coupon.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <coupon-index></coupon-index>
@endsection
