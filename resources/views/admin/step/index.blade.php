@extends('layouts.admin')

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/step.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/step.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <step-index></step-index>
@endsection
