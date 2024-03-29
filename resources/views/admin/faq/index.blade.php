@extends('layouts.admin')

@section('title', Lang::get('admin/faq.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/faq.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/faq.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <faq-index></faq-index>
@endsection
