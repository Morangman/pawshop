@extends('layouts.admin')

@section('title', Lang::get('admin/warehouse.edit.title'))

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/warehouse.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.warehouse.index') }}">
        @lang('admin/warehouse.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/warehouse.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <warehouse-edit
        :warehouse="{{ $warehouse }}"
        :categories="{{ json_encode($categories) }}"
        :statuses="{{ json_encode($statuses, true) }}"
    ></warehouse-edit>
@endsection
