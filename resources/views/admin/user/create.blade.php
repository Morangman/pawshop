@extends('layouts.admin')

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
    <user-create></user-create>
@endsection
