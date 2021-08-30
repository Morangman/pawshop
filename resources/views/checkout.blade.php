@extends('layouts.app')

@section('home-title', 'Checkout | Sell Used Device with Rapid Recycle')

@section('home-description', 'Checkout the Rapid Recycle website ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2575')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <checkout
            :user="{{ json_encode($user) }}"
            :states="{{ json_encode($states) }}"
        ></checkout>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection

@section('scripts')
<script src="{{ URL::asset('common/js/routes.js') }}"></script>
<script src="{{ URL::asset('js/app.js') }}"></script>
@endsection
