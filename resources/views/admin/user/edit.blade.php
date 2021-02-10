@extends('layouts.admin')

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
    <user-edit
        :user="{{ $user }}"
        :role="{{ $role }}"
    ></user-edit>
@endsection
