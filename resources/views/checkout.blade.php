@extends('layouts.app')

@section('home-title', 'Checkout')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <checkout
            :user="{{ json_encode($user) }}"
            :states="{{ json_encode($states) }}"
        ></checkout>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
