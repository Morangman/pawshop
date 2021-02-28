@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="main-content">
        <cart></cart>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection