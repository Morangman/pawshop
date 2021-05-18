@extends('layouts.admin')

@section('title', Lang::get('admin/warehouse-status.edit.title'))

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/warehouse-status.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.warehouse-status.index') }}">
        @lang('admin/warehouse-status.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/warehouse-status.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <warehouse-status-edit
        :status="{{ $status }}"
    ></warehouse-status-edit>
@endsection
