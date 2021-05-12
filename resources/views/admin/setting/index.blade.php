@extends('layouts.admin')

@section('title', Lang::get('admin/setting.breadcrumbs.index'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/setting.breadcrumbs.index')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/setting.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <setting-index
        :settings="{{ json_encode($settings) }}"
    ></setting-index>
@endsection
