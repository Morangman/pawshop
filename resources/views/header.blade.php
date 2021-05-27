<header>
    <div class="container">
        <div class="header-content">
            <div class="mobile-buter">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <a class="main-logo" href="/">
                <img width="28" height="32" src="{{ asset('client/images/mail_logo.png') }}" alt="" />
                <span>Rapid <i>Recycle</i></span>
            </a>
            <div class="header-flex">
                <ul class="header-menu">
                    <li>
                        <a href="" data-title="Choose Device" class="has-drop">Choose Device <img src="{{ asset('client/images/select_arrow.png') }}" alt="" /></a>
                        <ul class="drop-menu">
                            @foreach($categories as $category)
                                <li><a href="{{ URL::route('get-category', ['slug' => $category->getAttribute('slug')]) }}">{{ $category->getAttribute('name') }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="text-centered"><a href="{{ URL::route('support') }}" data-title="Mobile Service">Support</a></li>
                </ul>
                <div class="header-login">
                    <ul class="header-menu">
                        @if(Auth::check())
                            @auth
                                @role('admin')
                                <li class="nav-item">
                                    <a href="{{ URL::route('admin.order.index') }}" class="nav-link">
                                        <i class="icon-vcard"></i>
                                        <span>{{ Lang::get('common.title') }}</span>
                                    </a>
                                </li>
                                @endrole
                            @endauth
                            <li>
                                <a href="" data-title="My account" class="has-drop">My account<img src="{{ asset('client/images/select_arrow.png') }}" alt="" /></a>
                                <ul class="drop-menu">
                                    <li><a href="{{ URL::route('account', ['tab' => 'account']) }}">Account info</a></li>
                                    <li><a href="{{ URL::route('account', ['tab' => 'trade']) }}">Trade-ins</a></li>
                                    <li><a href="{{ URL::route('account', ['tab' => 'address']) }}">Addresses</a></li>
                                    @auth
                                        @role('admin')
                                        <li class="nav-item">
                                            <a href="{{ URL::route('admin.order.index') }}" class="nav-link">
                                                <i class="icon-vcard"></i>
                                                <span>{{ Lang::get('common.title') }}</span>
                                            </a>
                                        </li>
                                        @endrole
                                    @endauth
                                    <li><a href="{{ URL::route('web.logout') }}">@lang('common.navbar.logout')</a></li>
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ URL::route('web.login.show') }}">Login</a></li>
                        @endif
                    </ul>
                </div>
                <div class="header-search">
                    <div class="header-search-form">
                        <input id="header-search-input" type="text" placeholder="Write text">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="header-search-popup">
                        <ul class="header-search-popup-list" id="header-search-popup-list"></ul>
                    </div>
                    <div class="header-search-toggle"><img src="../../client/images/icon_search.svg" alt=""></div>
                </div>
                <div class="header-close"><img src="{{ asset('client/images/close.png') }}" alt="" /></div>
            </div>
            <a href="{{ Url::route('cart') }}" class="header-cart">
                <div id="header-cart-count" class="count">0</div>
                <span>My box</span>
            </a>
        </div>
    </div>
</header>
