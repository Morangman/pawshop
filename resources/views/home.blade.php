@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    @if($isMainPage)
    <section class="hero">
        <div class="flex-column">
            <p class="hero-title pt-3">TRADE-IN <br class="show-for-small-only"> YOUR USED</p>
            <p class="hero-title">ELECTRONICS <br class="show-for-small-only"> for <span class="hero-cash">CASH</span></p>
            <p class="hero-text">The simplest and safest <br class="show-for-small-only"> way to sell your old phone <br class="show-for-small-only">  or tablet online</p>
            <a href="#sell-device-section" class="btn red-btn go-sell" data-effect="mfp-zoom-in">SELL YOUR DEVICE</a>
            <a class="hero-arrow-down go-sell" href="#sell-device-section"><img src="{{ asset('client/images/arrow-down.png') }}"/></a>
        </div>
    </section>
    <section class="header-steps">
        <p class="header-steps_title">Get <span class="hero-cash">Cash</span> in Three <br class="show-for-small-only"> Simple Steps</p>

        <div class="container header-steps_items">
            <div class="header-steps_step">
                <img src="{{ asset('client/images/phone_icons-22.png') }}"/>
                <p class="header-steps_step-title">1. Get an Instant Quote</p>
                <p class="header-steps_step-text">Find your used electronics and get an instant quote based on the condition.</p>
            </div>
            <div class="header-steps_step">
                <img src="{{ asset('client/images/phone_icons-23.png') }}"/>
                <p class="header-steps_step-title">2. Ship For Free</p>
                <p class="header-steps_step-text">We provide you with a free, trackable pre-paid shipping label for sending us your item(s).</p>
            </div>
            <div class="header-steps_step">
                <img src="{{ asset('client/images/phone_icons-24.png') }}"/>
                <p class="header-steps_step-title">3. Get Cash Fast</p>
                <p class="header-steps_step-text">No need to wait for a buyer, get cash fast through Rapid-Recycle.com.</p>
            </div>
        </div>
    </section>
    @endif
    <!--main-content-->
    <div id="sell-device-section" class="main-content">
        <!-- ========= order-section ============ -->
        <section class="order-section">
            <div class="container">
                <div v-cloak>
                    <sell-device
                        :category="{{ json_encode($category) }}"
                        :steps="{{ json_encode($steps) }}"
                        :categories="{{ json_encode($relatedCategories) }}"
                        :faqs="{{ json_encode($faqs) }}"
                    ></sell-device>
                  </div>
                  
                  <div v-if="!$data">
                    <div class="device-preloader">
                        <p>LOADING...</p>
                    </div>
                  </div>
            </div>
        </section>
    </div>
    <!--//main-content-->
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
