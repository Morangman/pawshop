@extends('layouts.admin')

@section('title', Lang::get('admin/callback.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/callback.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/callback.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <callback-index></callback-index>
@endsection
