@extends('layouts.app')

@section('home-title', (array) $category ? 'Sell ' . str_replace('Sell ', '', $category->getAttribute('name')) . ' - Trade in and get cash | Rapid-Recycle' : $settings->getAttribute('general_settings')['seo_title'])

@section('home-description', (array) $category ? 'Sell your Used ' . str_replace('Sell ', '', $category->getAttribute('name')) . ' with Rapid Recycle ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2576' : $settings->getAttribute('general_settings')['seo_meta'])

@section('content')
    @yield('header',
        View::make(
            'header',
            [
                'cat' => $category,
                'categories' => $categories,
                'settings' => $settings
            ]
        )
    )
    @if($isMainPage)
    <section class="hero">
        <div class="flex-column">
            <p class="hero-title pt-3">TRADE-IN <br class="show-for-small-only"> YOUR USED</p>
            <p class="hero-title">ELECTRONICS <br class="show-for-small-only"> for <span class="hero-cash">CASH</span></p>
            <p class="hero-text">The simplest and safest <br class="show-for-small-only"> way to sell your old phone <br class="show-for-small-only">  or tablet online</p>
            <a href="#sell-device-section" class="btn red-btn go-sell" data-effect="mfp-zoom-in">SELL YOUR DEVICE</a>
            <a class="hero-arrow-down go-sell" href="#sell-device-section"><img width="46" height="24" alt="arrow-down" src="{{ asset('client/images/arrow-down.png') }}"/></a>
        </div>
    </section>
    <section class="header-steps">
        <p class="header-steps_title">Get <span class="hero-cash">Cash</span> in Three <br class="show-for-small-only"> Simple Steps</p>

        <div class="container header-steps_items">
            <div class="header-steps_step">
                <img width="95" height="127" alt="phone-icon-22" src="{{ asset('client/images/phone_icons-22.png') }}"/>
                <p class="header-steps_step-title">1. Get an Instant Quote</p>
                <p class="header-steps_step-text">Find your used electronics and get an instant quote based on the condition.</p>
            </div>
            <div class="header-steps_step">
                <img width="95" height="127" alt="phone_icons-23" src="{{ asset('client/images/phone_icons-23.png') }}"/>
                <p class="header-steps_step-title">2. Ship For Free</p>
                <p class="header-steps_step-text">We provide you with a free, trackable pre-paid shipping label for sending us your item(s).</p>
            </div>
            <div class="header-steps_step">
                <img width="95" height="127" alt="phone_icons-24" src="{{ asset('client/images/phone_icons-24.png') }}"/>
                <p class="header-steps_step-title">3. Get Cash Fast</p>
                <p class="header-steps_step-text">No need to wait for a buyer, get cash fast through Rapid-Recycle.com.</p>
            </div>
        </div>
    </section>
    @endif
    @if((array) $category && $category->getAttribute('coupon'))
        <div class="padding-block"></div>
    @endif
    <!--main-content-->
    <div id="sell-device-section" class="main-content">
        <!-- ========= order-section ============ -->
        <section class="order-section">
            <div class="container">
                <h1 class="center-text">{{ (array) $category ? 'Sell ' . str_replace('Sell ', '', $category->getAttribute('name')) : 'TRADE-IN YOUR USED ELECTRONICS FOR CASH' }}</h1>
                <div class="description center-text">Choose the option you'd like to trade-in for cash</div>
                @if(empty($steps))
                    <div class="order-search-outer">
                        <h5>Search the device:</h5>
                        <div class="order-search">
                            <div class="order-search-form">
                                <input id="main-search-input" type="text" placeholder="Write text for search">
                                <a href="javascript:void(0)" class="btn red-btn">Search</a>
                            </div>
                            <div class="order-search-popup">
                                <ul id="order-search-popup-list" class="order-search-popup-list"></ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if($breadcrumbs)
                <div class="page-header page-header-light">
                    <div class="breadcrumb-line breadcrumb-line-light breadcrumb-line-component header-elements-md-inline">
                        <div class="d-flex">
                            <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                                <script type="application/ld+json">
                                    {
                                        "@context": "http://schema.org",
                                        "@type": "BreadcrumbList",
                                        "ItemListElement": [
                                            {
                                                "@type": "ListItem",
                                                "position": 1,
                                                "item": {
                                                    "@id": "{{ Url::route('home') }}",
                                                    "name": "Home"
                                                }
                                            },
                                            @foreach ($breadcrumbs as $key => $breadcrumb)
                                                {
                                                    "@type": "ListItem",
                                                    "position": {{ $key + 2 }},
                                                    "item": {
                                                        "@id": "{{ Url::route('get-category', ['slug' => $breadcrumb['slug']]) }}",
                                                        "name": "{{ $breadcrumb['name'] }}"
                                                    }
                                                } {{ $key + 1 < count ($breadcrumbs) ? ',' : '' }}
                                            @endforeach
                                        ]
                                    }
                                </script>

                                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="/#sell-device-section" itemprop="item"><img width="12" height="12" alt="home" src="../../client/images/home.svg"/> <span itemprop="name">Home</span></a>
                                    <meta itemprop="position" content="1" />
                                </li>
                                @foreach ($breadcrumbs as $key => $breadcrumb)
                                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                        <a href="{{ Url::route('get-category', ['slug' => $breadcrumb['slug']]) }}" itemprop="item">
                                            <span itemprop="name">{{ $breadcrumb['name'] }}</span>
                                        </a>
                                        <meta itemprop="position" content="{{ $key + 2 }}" />
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                @if(empty($steps))
                    <div class="order-content">
                        <h4>Or choose the device for sell:</h4>
                        <ul class="order-list">
                            @foreach($relatedCategories as $subCategory)
                            <li>
                                <a href="{{ URL::route('get-category', ['slug' => $subCategory->getAttribute('url') ]) }}">
                                    <div class="image"><img width="130" height="130" alt="{{ $subCategory->getAttribute('name') }}" src="{{ $subCategory->getAttribute('compressed_image') ? $subCategory->getAttribute('compressed_image') : $subCategory->getAttribute('image') }}" alt="" /></div>
                                    <h5>{{ $subCategory->getAttribute('name') }}</h5>
                                    @if($subCategory->getAttribute('custom_text'))
                                        <div class="price">Cash in up to ${{ $subCategory->getAttribute('custom_text') }}</div>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <section class="sprt-section category-text">
                        <div class="container">
                            {!! html_entity_decode($category->getAttribute('text')) !!}
                        </div>
                    </section>
                @else
                <script type="application/ld+json">
                    {
                        "@context": "http://schema.org",
                        "@type": "Product",
                        "sku": "{{ $category->getKey() }}",
                        "url": "{{ Request::url() }}",
                        "name": "{{ $category->getAttribute('name') }}",
                        "color": "",
                        "image": "{{ $category->getAttribute('image') }}",
                        "description": "{{ 'Sell your Used ' . str_replace('Sell ', '', $category->getAttribute('name')) . ' with Rapid Recycle ✓ Free shipping and fast payout ✓ Get paid for your device today! ☎ +1 (602) 706-2576' }}",
                        "itemCondition": "https://schema.org/NewCondition",
                        "offers": {
                            "@type": "Offer",
                            "availability": "http://schema.org/InStock",
                            "url": "{{ Request::url() }}",
                            "price": "{{ $category->getAttribute('custom_text') }}",
                            "priceCurrency": "USD",
                            "priceValidUntil": "{{ $category->getAttribute('updated_at') }}"
                        }
                    }
                </script>
                <div v-cloak>
                    <sell-device
                        :category="{{ json_encode($category) }}"
                        :steps="{{ json_encode($steps) }}"
                        :faqs="{{ json_encode($faqs) }}"
                    ></sell-device>
                </div>

                <div v-if="!$data">
                    <div class="device-preloader">
                        <p>LOADING...</p>
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
    <!--//main-content-->
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
