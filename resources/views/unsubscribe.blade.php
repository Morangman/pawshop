@extends('layouts.app')

@section('home-title', 'Unsubscribed')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <section id="unsubscribed">
            <div class="container">
                <div class="unsubscribed">
                    <h1>You are now unsubscribed</h1>
                </div>
                <a href="/#sell-device-section" class="btn">Sell Your Device</a>
            </div>
        </section>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
