@extends('layouts.admin')

@section('page_title')
    <i class="icon-list"></i>
    Daily @lang('admin/statistics.breadcrumbs.index')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        Daily @lang('admin/statistics.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <daily-statistics-index
        :views="{{ $viewsCount }}"
        :boxs="{{ $boxCount }}"
    ></daily-statistics-index>
@endsection
