@extends('layouts.app')

@section('home-title', 'User Agreement')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <section class="sprt-section">
            <div class="container">
                <h1>User Agreement</h1>
                <div>
                    {!! $settings['user_agreement'] ?? '' !!}
                </div>
            </div>
        </section>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
