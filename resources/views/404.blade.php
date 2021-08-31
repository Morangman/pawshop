@extends('layouts.app')

@section('home-title', '404 - Not Found')

@section('home-description', '')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="main-content">
        <div class="main-content">
            <section class="sprt-section">
                <div class="container" style="text-align: center;">
                    <h1>404 - Not Found</h1>
                    <a href="/" class="btn red-btn">GO HOME</a>
                </div>
            </section>
        </div>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection

@section('scripts')
<script src="{{ URL::asset('common/js/routes.js') }}"></script>
<script src="{{ URL::asset('js/app.js') }}"></script>
@endsection