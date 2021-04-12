@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/callback.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.callback.index') }}">
        @lang('admin/callback.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/callback.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <callback-edit
        :callback="{{ $callback }}"
    ></callback-edit>
@endsection
