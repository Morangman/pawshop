@extends('layouts.admin')

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/user.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/user.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <user-index></user-index>
@endsection
