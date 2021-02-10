@extends('layouts.admin')

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/comment.edit.title')
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.comment.index') }}">
        @lang('admin/comment.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/comment.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <comment-edit
        :comment="{{ $comment }}"
    ></comment-edit>
@endsection
