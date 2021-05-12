@extends('layouts.admin')

@section('title', Lang::get('admin/user.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/user.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.user.index') }}">
        @lang('admin/user.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/user.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <admin-create></admin-create>
@endsection
