@extends('layouts.app')

@section('home-title', 'Password reset success | Sell Used Device with Rapid Recycle')

@section('home-description', 'Password reset success the Rapid Recycle website ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2575')

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
