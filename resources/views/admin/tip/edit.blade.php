@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/tip.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.tip.index') }}">
        @lang('admin/tip.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/tip.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <tip-edit
        :tip="{{ $tip }}"
    ></tip-edit>
@endsection
