@extends('layouts.admin')

@section('title', Lang::get('admin/tip.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/tip.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.tip.index') }}">
        @lang('admin/tip.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/tip.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <tip-create></tip-create>
@endsection
