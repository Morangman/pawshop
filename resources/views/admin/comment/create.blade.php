@extends('layouts.admin')

@section('page_title')
    <i class="icon-add"></i>
    @lang('admin/comment.create.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.comment.index') }}">
        @lang('admin/comment.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/comment.breadcrumbs.create')
    </span>
@endsection

@section('content')
    <comment-create></comment-create>
@endsection
