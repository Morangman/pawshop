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
                                    <div class="image"><img width="130" height="130" alt="{{ $subCategory->getAttribute('name') }}" src="{{ $subCategory->getAttribute('compressed_image') ? $subCategory->getAttribute('compressed_image') : $subCategory->getAttribute('image') }}" /></div>
                                    <h5>{{ $subCategory->getAttribute('name') }}</h5>
                                    @if($subCategory->getAttribute('custom_text'))
                                        <div class="price">Cash in up to ${{ $subCategory->getAttribute('custom_text') }}</div>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @if((array) $category && $category->getAttribute('text'))
                    <section class="sprt-section category-text">
                        <div class="container">
                            {!! html_entity_decode($category->getAttribute('text')) !!}
                        </div>
                    </section>
                    @else
                        <section class="sprt-section category-text">
                            <div class="container">
                                <p class="ql-align-center"><span style="background-color: transparent; color: rgb(0, 0, 0);"></span></p><h2>Where can I sell my phone for cash?</h2><p></p><p class="ql-align-justify"><span style="background-color: transparent; color: rgb(0, 0, 0);">The rapid development of electronics has a significant impact on our lives. Without modern gadgets and electronics, we can no longer imagine our existence, work and leisure. One of the important criteria when buying such new products and hi-tech electronics is their high cost, which leads to the choice to buy used equipment. This is a significant opportunity to save on purchases and save your family budget. This factor has led to the active growth of the second-hand market in goods, the emergence of radio markets, commission shops and in our time the development of the Internet, the creation of commission online stores. The main danger is that when you buy used equipment, defects may be invisible or the seller hides them, especially if the buyer is poorly versed in this. But now all these risks can be avoided: just use the trade-in system. Trade-in is a system of purchase in which the buyer gives the seller the old product to offset the value of the new. Under the trade-in system you can buy a car, industrial equipment, appliances, smartphone and even an apartment. Rapid Recycle trade-in service offers you a wide range of used products at affordable prices: smartphones, tablets, laptops, computers, cameras, TVs, appliances and other appliances.</span></p><p class="ql-align-justify"><span style="background-color: transparent; color: rgb(0, 0, 0);">Advantages of buying in the Rapid Recycle trade-in service:</span></p><p class="ql-align-justify"><span style="background-color: transparent;">- regular promotions and discounts are held;</span></p><p class="ql-align-justify"><span style="background-color: transparent;">- technical staff conducts full pre-sales training and testing of each unit of equipment;</span></p><p class="ql-align-justify"><span style="background-color: transparent;">- fast delivery to any settlement of the USA.</span></p><p class="ql-align-justify"><span style="background-color: transparent; color: rgb(0, 0, 0);">The trade-in system is not just about buying cheaper. Trade-in is the exchange of old equipment for new with a surcharge. In fact, you are selling a mobile phone for cash (any other technics), which you use as a discount when you buy a new model. This proposal has become more and more popular in recent years, especially when it comes to mobile phones. It is not surprising: the trade-in system significantly simplifies the procedure for changing a car, reduces the risk of fraud when selling an old phone, reduces the time spent on its implementation, and even promises additional financial benefits in the form of discounts. We propose to consider the possibility of selling a mobile phone through the trade-in system. We are sure that this way of selling your phone online will be a great way to save money for you.</span></p><p><br></p><p class="ql-align-center"><span style="background-color: transparent; color: rgb(0, 0, 0);"></span></p><h3>How to sell mobile phones?</h3><p></p><p class="ql-align-justify"><span style="background-color: transparent; color: rgb(0, 0, 0);">To sell the old mobile for cash is a really promising niche in the modern market, since almost everyone has a cell phone at home, which no one has used for a long time. The trade-in system is a great way to turn junk into money quickly and profitably. You give your old phone for evaluation at the Rapid Recycle trade-in service. The expert examines the gadget, checks its functional state and in advance, before signing any documents, informs you of the amount at which the leased device is estimated. If it suits you, then you immediately proceed to the registration of selling your phone online&nbsp; and the purchase of a new gadget, from which the price of your old mobile for cash is deducted. The number of leased devices is not limited. Bring at least a dozen old smartphones if these models participate in the promotion. The discount will be available for the full amount at which they are priced. You can not only donate several devices at a time, but also participate in the promotion several times. The trade-in discount can be used when buying a device on credit or by installments. The final conditions of the purchase will be communicated to you by the expert of the Rapid Recycle trade-in service after evaluating your old phone. Even broken cell phones are accepted. True, one must understand that the discount for them will be quite small.</span></p><p class="ql-align-justify"><span style="background-color: transparent; color: rgb(0, 0, 0);">Selling old mobile phones for cash is possible in almost any condition. Depending on how well the phone looks and works, the amount at which it is priced changes. Therefore, it is best to prepare your old phone for sale in advance. Before you sell old phones online for cash, you need to make a backup so as not to lose forever important personal data: phone book, favorite applications, photos, banking information, work documents, correspondence. The second important step is to remove traces of your activity on your phone. All possible information can be stored either directly in the device's memory, or on removable media, which are not only an SD card, but also a SIM card. Finally, important data is stored in accounts associated with a device or applications. As a rule, removable media are never left without attention, but everything else is rarely taken care of. Many people consider resetting to factory settings as a panacea, but this is not so — not all information will be erased, and if the new owner wants, he will be able to extract important confidential data. There is only one way — to run all applications and sites where you need to log in with a username/password and press the "Logout" button. Even if there are many such applications and web resources, extra precaution does not hurt.</span></p>
                            </div>
                        </section>
                    @endif
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
