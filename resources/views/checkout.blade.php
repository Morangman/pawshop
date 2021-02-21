@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <checkout></checkout>
    </div>

    @yield('footer', View::make('footer'))
@endsection
