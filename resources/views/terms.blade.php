@extends('layouts.app')

@section('home-title', 'Terms and Conditions | Sell Used Device with Rapid Recycle')

@section('home-description', 'Terms and Conditions the Rapid Recycle website ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2575')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <section class="sprt-section">
            <div class="container">
                <h1>Terms and Conditions</h1>
                <div>
                    {!! $settings['terms'] ?? '' !!}
                </div>
            </div>
        </section>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
