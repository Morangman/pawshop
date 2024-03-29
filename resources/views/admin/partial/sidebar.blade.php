<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->
    <div class="sidebar-content">
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                @php
                    $user = Auth::user();

                    $chatMessageCount = \App\Message::query()->where('viewed', '=', \App\Callback::NOT_VIEWED)->count();

                    $statuses = [];

                    $warehouseStatuses = [];

                    foreach (\App\OrderStatus::query()->orderBy('order')->get() as $status) {
                        $ordersCount = \App\Order::query()->where('ordered_status', '=', $status->getKey())->count();

                        $statuses[$status->getAttribute('name')] = [
                            'url' => URL::route('admin.order.index', ['status' => $status->getKey()]),
                            'count' => $ordersCount,
                            'color' => $status->getAttribute('color'),
                        ];
                    }
                    
                    foreach (\App\WarehouseStatus::query()->orderBy('order')->get() as $status) {
                        $ordersCount = \App\Warehouse::query()->where('status', '=', $status->getKey())->count();

                        $warehouseStatuses[$status->getAttribute('name')] = [
                            'url' => URL::route('admin.warehouse.index', ['status' => $status->getKey()]),
                            'count' => $ordersCount,
                            'color' => $status->getAttribute('color'),
                        ];
                    }

                    $statuses['All'] = [
                        'url' => URL::route('admin.order.index'),
                        'count' => \App\Order::query()->get()->count(),
                        'color' => '',
                    ];

                    $warehouseStatuses['All'] = [
                        'url' => URL::route('admin.warehouse.index'),
                        'count' => \App\Warehouse::query()->get()->count(),
                        'color' => '',
                    ];
                @endphp
                <li class="nav-item">
                    <a href="{{ URL::route('admin.notification.index') }}" class="nav-link sidebar-link @active_menu_class('admin.notification')">
                        <i class="icon-bell3"></i>
                        <span>@lang('common.sidebar.notifications')</span>
                        @if($user && $user->unreadNotifications->count())
                        <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0" style="margin-left: auto!important;">{{ $user->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item nav-item-submenu nav-item-open">
                    <a href="#" id="orders-nav" class="nav-link"><i class="icon-filter4"></i> <span>@lang('common.sidebar.orders')</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Pickers" style="display: block;">
                        @foreach($statuses as $key => $status)
                        <li class="nav-item">
                            <a href="{{ $status['url'] }}" class="nav-link sidebar-link order-status_nav @active($status['url'])">
                                <b>{{ $key }}</b> <span style="background-color:{{ $status['color'] }}!important; color: #ffffff;" class="badge badge-primary">{{ $status['count'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @role('admin')
                <li class="nav-item">
                    <a href="{{ URL::route('admin.user.index') }}" class="nav-link sidebar-link @active_menu_class('admin.user')">
                        <i class="icon-users2"></i>
                        <span>@lang('common.sidebar.users')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ URL::route('admin.callback.index') }}" class="nav-link sidebar-link @active_menu_class('admin.callback')">
                        <i class="icon-bubbles3"></i>
                        <span>@lang('common.sidebar.callbacks')</span>
                        @if($chatMessageCount > 0)
                            <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0" style="margin-left: auto!important;">{{ $chatMessageCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" id="warehouse-nav" class="nav-link"><i class="icon-home7"></i> <span>@lang('common.sidebar.warehouse')</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Pickers">
                        @foreach($warehouseStatuses as $key => $status)
                        <li class="nav-item">
                            <a href="{{ $status['url'] }}" class="nav-link order-status_nav @active($status['url'])">
                                <b>{{ $key }}</b> <span style="background-color:{{ $status['color'] }}!important; color: #ffffff;" class="badge badge-primary">{{ $status['count'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" id="products-nav" class="nav-link"><i class="icon-drawer"></i><span>@lang('common.sidebar.products')</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Pickers" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.product.index') }}" class="nav-link @active_menu_class('admin.product')">
                                <i class="icon-drawer"></i>
                                <span>@lang('common.sidebar.products')</span>
                                <span class="badge badge-primary badge-pill ml-auto ml-md-0" style="margin-left: auto!important;">{{  \App\Category::query()->whereNotNull('custom_text')->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.category.index') }}" class="nav-link @active_menu_class('admin.category')">
                                <i class="icon-drawer"></i>
                                <span>@lang('common.sidebar.categories')</span>
                                <span class="badge badge-primary badge-pill ml-auto ml-md-0" style="margin-left: auto!important;">{{  \App\Category::query()->whereNull('custom_text')->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" id="statistics-nav" class="nav-link"><i class="icon-file-stats"></i><span>@lang('common.sidebar.statistics')</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Pickers" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.statistics.index') }}" class="nav-link @active_menu_class('admin.statistics')">
                                <i class="icon-file-stats"></i>
                                <span>@lang('common.sidebar.statistics')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.daily-statistics.index') }}" class="nav-link @active_menu_class('admin.daily-statistics')">
                                <i class="icon-file-stats"></i>
                                <span>@lang('common.sidebar.daily_statistics')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.all-daily-statistics.index') }}" class="nav-link @active_menu_class('admin.all-daily-statistics')">
                                <i class="icon-file-stats"></i>
                                <span>By day statistics</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Data editing</div> <i class="icon-menu" title="Components"></i></li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" id="settings-nav" class="nav-link"><i class="icon-cog"></i> <span>@lang('common.sidebar.settings')</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Pickers" style="display: none;">
                        @role('admin')
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.admin.index') }}" class="nav-link @active_menu_class('admin.admin')">
                                <i class="icon-users2"></i>
                                <span>@lang('common.sidebar.admins')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.step.index') }}" class="nav-link @active_menu_class('admin.step')">
                                <i class="icon-footprint"></i>
                                <span>@lang('common.sidebar.steps')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.faq.index') }}" class="nav-link @active_menu_class('admin.faq')">
                                <i class="icon-lifebuoy"></i>
                                <span>@lang('common.sidebar.faqs')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.tip.index') }}" class="nav-link @active_menu_class('admin.tip')">
                                <i class="icon-help"></i>
                                <span>@lang('common.sidebar.tips')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.order-status.index') }}" class="nav-link @active_menu_class('admin.order-status')">
                                <i class="icon-checkmark"></i>
                                <span>@lang('common.sidebar.statuses')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.warehouse-status.index') }}" class="nav-link @active_menu_class('admin.warehouse-status')">
                                <i class="icon-checkmark"></i>
                                <span>@lang('common.sidebar.warehouse_statuses')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.coupon.index') }}" class="nav-link @active_menu_class('admin.coupon')">
                                <i class="icon-ticket"></i>
                                <span>@lang('common.sidebar.coupons')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.task.index') }}" class="nav-link @active_menu_class('admin.task')">
                                <i class="icon-task"></i>
                                <span>@lang('common.sidebar.tasks')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.comment.index') }}" class="nav-link @active_menu_class('admin.comment')">
                                <i class="icon-bubble"></i>
                                <span>@lang('common.sidebar.comments')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::route('admin.setting.index') }}" class="nav-link @active_menu_class('admin.setting')">
                                <i class="icon-cog"></i>
                                <span>@lang('common.sidebar.settings')</span>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
