@extends('layouts.app')

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

    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection
