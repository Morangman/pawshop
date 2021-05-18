@extends('layouts.admin')

@section('title', Lang::get('admin/warehouse.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/warehouse.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.warehouse.index') }}">
        @lang('admin/warehouse.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/warehouse.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <warehouse-create
        :categories="{{ json_encode($categories) }}"
        :statuses="{{ json_encode($statuses, true) }}"
    ></warehouse-create>
@endsection
