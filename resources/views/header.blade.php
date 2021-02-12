<header>
    <div class="container">
        <div class="header-content">
            <div class="mobile-buter">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <a class="main-logo" href="/">
                <img src="{{ asset('client/images/mail_logo.png') }}" alt="" />
                <span>Rapid iPhone <i>Repair</i></span>
            </a>
            <div class="header-flex">
                <ul class="header-menu">
                    <li>
                        <a href="" data-title="Device Repairs" class="has-drop">Device Repairs <img src="{{ asset('client/images/select_arrow.png') }}" alt="" /></a>
                        <ul class="drop-menu">
                            <li><a href="">Phone Repairs</a></li>
                            <li><a href="">iPads and Tablets</a></li>
                            <li><a href="">Macbooks and Computers</a></li>
                        </ul>
                    </li>
                    <li><a href="" data-title="Mobile Service">Mobile Service</a></li>
                    <li><a href="" data-title="FAQ">FAQ</a></li>
                    <li><a href="" data-title="About us">About us</a></li>
                    <li><a href="" data-title="Financing">Financing</a></li>
                </ul>
                <div class="header-login">
                    <ul class="header-menu">
                        @if(Auth::check())
                            <li>
                                <a href="" data-title="My account" class="has-drop">My account<img src="{{ asset('client/images/select_arrow.png') }}" alt="" /></a>
                                <ul class="drop-menu">
                                    <li><a href="">Account info</a></li>
                                    <li><a href="">Trade-ins</a></li>
                                    <li><a href="">Addresses</a></li>
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
                    <form class="header-search-form">
                        <input type="text" placeholder="Write text">
                        <button type="submit"><img src="{{ asset('client/images/icon_search.svg') }}" alt=""></button>
                    </form>
                    <div class="header-search-toggle"><img src="{{ asset('client/images/icon_search.svg') }}" alt=""></div>
                </div>
                <div class="header-close"><img src="{{ asset('client/images/close.png') }}" alt="" /></div>
            </div>
            <a href="" class="header-cart">
                <div class="count">2</div>
                <span>My box</span>
            </a>
        </div>
    </div>
</header>
