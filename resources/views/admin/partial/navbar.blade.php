<div class="navbar navbar-expand-md navbar-dark navbar-slide-top fixed-top" style="padding-left: 0!important; padding-right: 0!important;">
    <div class="d-md-none" style="padding-left: 20px;">
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                <i class="icon-paragraph-justify3"></i>
            </a>
        </li>
    </ul>
    <div class="navbar-brand" class="logo-title">
        <a href="{{ URL::route('home')}}" target="_blank" class="d-inline-block">
            <img src="{{ URL::asset('client/images/white-main-logo.png') }}" alt="">
        </a>
    </div>
    <div class="nav-items">
        <div class="col col-md-4">
            <form method="post" action="{{ URL::route('admin.order.search')}}">
                {{csrf_field()}}
                <input name="search" type="text" placeholder="Search orders by name, id or tracking number" class="form-control">
            </form>
        </div>
        <div class="col col-md-2">
            <p style="margin: 0;">Paid amount: {{ \App\Warehouse::query()->where('status', 1)->sum('price') }} $</p>
        </div>
        <div class="col col-md-2">
            <p style="margin: 0;">Sold amount: {{ \App\Warehouse::query()->where('status', 4)->sum('price') }} $</p>
        </div>
        <div class="d-md-none" style="padding-right: 10px;">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>
        </div>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <span class="ml-md-3 mr-md-auto hidden"></span>
        <ul class="navbar-nav">
            @if($user = Auth::user())
            <li class="nav-item dropdown">
                @if($user->unreadNotifications->count() > 0)
                <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-bubbles4"></i>
                    <span class="d-md-none ml-2">@lang('common.navbar.notifications')</span>
                    <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">{{ $user->unreadNotifications->count() }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                    <div class="dropdown-content-header">
                        <span class="font-weight-semibold">@lang('common.navbar.notifications')</span>
                    </div>

                    <div class="dropdown-content-body dropdown-scrollable">
                        <ul class="media-list">
                            @foreach($user->unreadNotifications as $notification)
                                <li class="media">
                                    <div class="media-body">
                                        <div class="media-title">
                                            <a href="{{ URL::route('admin.notification.view', ['notification' => $notification['id']]) }}">
                                                <span class="font-weight-semibold">{{ $notification['data']['title'] }}</span>
                                                <span class="text-muted float-right font-size-sm">{{ \Carbon\Carbon::parse($notification['created_at'])->format('d.m.Y H:i') }}</span>
                                            </a>
                                        </div>

                                        <span class="text-muted">{{ isset($notification['data']['text']) ? \Illuminate\Support\Str::limit($notification['data']['text'], 20) : '' }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="dropdown-content-footer justify-content-center p-0">
                        <a href="{{ URL::route('admin.notification.index') }}" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="" data-original-title="Show All"><i class="icon-menu7 d-block top-0"></i></a>
                    </div>
                </div>
                @endif
            </li>
            @endif
            <li class="nav-item dropdown dropdown-user">
                @if($user = Auth::user())
                    <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                        <span>{{ $user->getAttribute('name') }}</span>
                    </a>
                @endif
                <div class="dropdown-menu dropdown-menu-right">
                    <a
                        class="dropdown-item"
                        href="{{ route('web.logout') }}"
                    >
                        <i class="icon-switch2"></i>
                        @lang('common.navbar.logout')
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>
