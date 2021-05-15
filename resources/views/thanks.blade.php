@extends('layouts.app')

@section('home-title', $settings->getAttribute('general_settings')['seo_title'])

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <thanks
            :order="{{ json_encode($order) }}"
            :status="{{ json_encode($status) }}"
            :states="{{ json_encode($states) }}"
        ></thanks>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
