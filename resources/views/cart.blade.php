@extends('layouts.app')

@section('home-title', 'Cart | Sell Used Device with Rapid Recycle')

@section('home-description', 'Cart the Rapid Recycle website ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2575')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="main-content">
        <cart></cart>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection

@section('scripts')
<script src="{{ URL::asset('common/js/routes.js') }}"></script>
<script src="{{ URL::asset('js/app.js') }}"></script>
@endsection
