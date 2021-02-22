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
                            @foreach($categories as $category)
                                <li><a href="{{ URL::route('get-category', ['category' => $category->getKey()]) }}">{{ $category->getAttribute('name') }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{ URL::route('support') }}" data-title="Mobile Service">Support</a></li>
                </ul>
                <div class="header-login">
                    <ul class="header-menu">
                        @if(Auth::check())
                            <li>
                                <a href="" data-title="My account" class="has-drop">My account<img src="{{ asset('client/images/select_arrow.png') }}" alt="" /></a>
                                <ul class="drop-menu">
                                    <li><a href="{{ URL::route('account') }}">Account info</a></li>
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
                <search-header></search-header>
                <div class="header-close"><img src="{{ asset('client/images/close.png') }}" alt="" /></div>
            </div>
            <header-cart></header-cart>
        </div>
    </div>
</header>
