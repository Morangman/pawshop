@extends('layouts.admin')

@section('title', 'Daily ' . Lang::get('admin/statistics.breadcrumbs.index'))

@section('page_title')
    <i class="icon-list"></i>
    By day @lang('admin/statistics.breadcrumbs.index')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        By day @lang('admin/statistics.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <by-day-statistics-index
        :views="{{ $viewsCount }}"
    ></by-day-statistics-index>
@endsection
