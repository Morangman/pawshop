@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="main-content">
        <account
            :user="{{ json_encode($user) }}"
        ></account>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection
