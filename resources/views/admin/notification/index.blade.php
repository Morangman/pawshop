@extends('layouts.admin')

@section('title', Lang::get('admin/notification.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/notification.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/notification.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <notification-index></notification-index>
@endsection
