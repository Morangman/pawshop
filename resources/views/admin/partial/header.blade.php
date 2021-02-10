<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4>
                @yield('page_title')
            </h4>
        </div>
        <div class="heading-elements">
            @yield('heading_button')
        </div>
    </div>
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="/" class="breadcrumb-item">
                    <i class="icon-home2 position-left"></i>
                    @lang('common.header.breadcrumbs.home')
                </a>
                @yield('breadcrumbs')
            </div>
        </div>
        <div class="header-elements d-none">
            <div class="breadcrumb justify-content-center">
                @yield('search')
            </div>
        </div>
    </div>
</div>
