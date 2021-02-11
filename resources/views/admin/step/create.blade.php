@extends('layouts.admin')

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/step.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.step.index') }}">
        @lang('admin/step.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/step.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <step-create
        :tips="{{ json_encode($tips) }}"
    ></step-create>
@endsection
