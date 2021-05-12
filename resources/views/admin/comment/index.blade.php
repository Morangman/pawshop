@extends('layouts.admin')

@section('title', Lang::get('admin/comment.index.title'))

@section('page_title')
    <i class="icon-list"></i>
    @lang('admin/comment.index.title')
@endsection

@section('breadcrumbs')
    <span class="breadcrumb-item active">
        @lang('admin/comment.breadcrumbs.index')
    </span>
@endsection

@section('content')
    <comment-index></comment-index>
@endsection
