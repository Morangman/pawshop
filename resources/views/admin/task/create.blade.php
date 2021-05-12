@extends('layouts.admin')

@section('title', Lang::get('admin/task.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/task.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.task.index') }}">
        @lang('admin/task.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/task.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <task-create></task-create>
@endsection
