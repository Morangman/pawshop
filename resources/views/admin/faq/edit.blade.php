@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/faq.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.faq.index') }}">
        @lang('admin/faq.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/faq.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <faq-edit
        :faq="{{ $faq }}"
    ></faq-edit>
@endsection
