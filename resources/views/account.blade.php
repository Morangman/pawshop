@extends('layouts.app')

@section('home-title', 'Account | Sell Used Device with Rapid Recycle')

@section('home-description', 'Account the Rapid Recycle website ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2575')

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
