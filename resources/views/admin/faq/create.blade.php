@extends('layouts.admin')

@section('title', Lang::get('admin/faq.create.title'))

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/faq.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.faq.index') }}">
        @lang('admin/faq.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/faq.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <faq-create></faq-create>
@endsection
