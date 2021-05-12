@extends('layouts.admin')

@section('title', Lang::get('admin/callback.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/callback.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.callback.index') }}">
        @lang('admin/callback.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/callback.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <callback-create></callback-create>
@endsection
