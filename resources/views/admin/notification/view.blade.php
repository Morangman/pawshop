@extends('layouts.admin')

@section('page_title')
    <i class="icon-eye"></i>
    @lang('admin/notification.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.notification.index') }}">
        @lang('admin/notification.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/notification.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <notification-view
            :notification="{{ json_encode($notification) }}"
    ></notification-view>
@endsection
