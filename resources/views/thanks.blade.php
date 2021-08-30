@extends('layouts.app')

@section('home-title', 'Thanks! | Sell Used Device with Rapid Recycle')

@section('home-description', 'Thanks! The Rapid Recycle website ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2575')

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

@section('scripts')
<script src="{{ URL::asset('common/js/routes.js') }}"></script>
<script src="{{ URL::asset('js/app.js') }}"></script>
@endsection
