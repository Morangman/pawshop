@extends('layouts.admin')

@section('title', Lang::get('admin/task.edit.title'))

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/task.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.task.index') }}">
        @lang('admin/task.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/task.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <task-edit
        :task="{{ $task }}"
    ></task-edit>
@endsection
