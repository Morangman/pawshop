@extends('layouts.app')

@section('home-title', $settings->getAttribute('general_settings')['seo_title'])

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <!-- content-area -->
    <section class="white-section section-block">
        <div class="container">
            <div class="section-space"></div>
                <h1>{{ Lang::get('auth.password_request_success.title') }}</h1>
                <h4>{{ Lang::get('auth.password_request_success.text.thank_you') }}</h4> </br>
                <a href="{{ URL::route('home') }}">{{ Lang::get('auth.password_request_success.links.home') }}</a>
            <div class="section-space"></div>
        </div>
    </section>
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
