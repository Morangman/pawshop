@extends('layouts.app')

@section('home-title', 'Account')

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
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection

@section('scripts')
<script src="{{ URL::asset('common/js/routes.js') }}"></script>
<script src="{{ URL::asset('js/app.js') }}"></script>
@endsection
