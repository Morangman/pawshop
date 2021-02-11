@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/step.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.step.index') }}">
        @lang('admin/step.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/step.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <step-edit
        :step="{{ $step }}"
        :tips="{{ json_encode($tips) }}"
    ></step-edit>
@endsection
