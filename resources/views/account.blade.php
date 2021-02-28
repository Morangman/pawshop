@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="main-content">
        <account
            :user="{{ json_encode($user) }}"
            :states="{{ json_encode($states) }}"
            :statuses="{{ json_encode($statuses) }}"
            :orders="{{ json_encode($orders) }}"
            :tab="{{ json_encode($tab) }}"
        ></account>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection