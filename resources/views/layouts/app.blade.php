<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @meta_tags

    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="315">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--[if IE]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
    <![endif]-->
    <title>@yield('home-title')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('client/images/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('client/images/favicon/favicon.png') }}">

    <meta name="image" property="image" content="{{ isset($settings->getAttribute('general_settings')['seo_image']) ? $settings->getAttribute('general_settings')['seo_image'] : '' }}">

    <meta name="theme-color" content="#399fdb">

    <!-- Fonts -->
    <link href="{{ asset('client/css/app.css') }}" rel="stylesheet" type="text/css">

    {!! $settings->getAttribute('code_insert') !!}

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
</head>
<body>
    <div id="app">
        <main class="py-4">
            <div class="content">
            @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ URL::asset('common/js/routes.js') }}"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('client/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ URL::asset('client/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ URL::asset('client/js/common.js') }}"></script>
    <script>
        $('.go-sell').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('href');
            let top = $(id).offset().top - 30;
            $('body, html').animate({scrollTop: top}, 600);
        });
        
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
            if (token) {
                document.getElementById('recaptcha').value = token;
            }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
