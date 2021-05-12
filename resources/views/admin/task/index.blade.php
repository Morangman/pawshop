@extends('layouts.admin')

@section('title', Lang::get('admin/task.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/task.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/task.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <task-index></task-index>
@endsection
