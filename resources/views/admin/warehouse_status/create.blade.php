@extends('layouts.admin')

@section('title', Lang::get('admin/warehouse-status.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/warehouse-status.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.warehouse-status.index') }}">
        @lang('admin/warehouse-status.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/warehouse-status.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <warehouse-status-create></warehouse-status-create>
@endsection
