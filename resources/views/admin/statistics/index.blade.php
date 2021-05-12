@extends('layouts.admin')

@section('title', Lang::get('admin/statistics.breadcrumbs.index'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/statistics.breadcrumbs.index')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/statistics.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <statistics-index
        :views="{{ $viewsCount }}"
        :boxs="{{ $boxCount }}"
    ></statistics-index>
@endsection
