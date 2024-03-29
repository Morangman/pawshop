@extends('layouts.admin')

@section('title', Lang::get('admin/tip.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/tip.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/tip.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <tip-index></tip-index>
@endsection
