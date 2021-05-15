@extends('layouts.app')

@section('home-title', $settings->getAttribute('general_settings')['seo_title'])

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="main-content">
        <cart></cart>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
