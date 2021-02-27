@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <thanks
            :order="{{ json_encode($order) }}"
            :statuses="{{ json_encode($statuses) }}"
        ></thanks>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection
