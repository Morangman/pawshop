@extends('layouts.admin')

@section('title', Lang::get('admin/user.edit.title'))

@section('page_title')
    <i class="icon-pencil"></i>
    @lang('admin/user.edit.title', ['name' => $user->getAttribute('name')])
@endsection

@section('breadcrumbs')
    <a class="breadcrumb-item" href="{{ URL::route('admin.user.index') }}">
        @lang('admin/user.breadcrumbs.index')
    </a>
    <span class="breadcrumb-item active">
        @lang('admin/user.breadcrumbs.edit')
    </span>
@endsection

@section('content')
    <admin-edit
        :user="{{ $user }}"
        :role="{{ $role }}"
    ></admin-edit>
@endsection
