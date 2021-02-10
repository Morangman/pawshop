<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
    <head>
        <title>@lang('common.title')</title>

        <meta name="csrf-token" content="{!! csrf_token() !!}">

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ URL::asset('admin/css/app.css') }}">

        @yield('styles')
    </head>
    <body class="navbar-top">
        <div id="app">
            <!-- Main navbar -->
             @yield('navbar', View::make('admin.partial.navbar'))
            <!-- /main navbar -->

            <!-- Page container -->
            <div class="@yield('container-class')">

                <!-- Page content -->
                <div class="page-content">

                    @if(Auth::check())
                        @yield('sidebar', View::make('admin.partial.sidebar'))
                    @endif
                    <!-- Main content -->
                    <div class="content-wrapper">

                    <!-- Page header -->
                    @yield('header', View::make('admin.partial.header'))
                    <!-- /page header -->

                        <!-- Content area -->
                        <div class="content">
                            @include('partial.alerts')
                            @yield('content')
                        </div>
                        <!-- /content area -->

                        <!-- Footer -->
                    @include('admin.partial.footer')
                    <!-- /footer -->

                    </div>
                    <!-- /main content -->
                </div>
                <!-- /page content -->
            </div>
            <!-- /page container -->
        </div>

        <script src="{{ URL::asset('admin/js/main/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('admin/js/main/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ URL::asset('common/js/routes.js') }}"></script>
        <script src="{{ URL::asset('admin/js/main.js') }}"></script>
        @yield('scripts')
    </body>
</html>
