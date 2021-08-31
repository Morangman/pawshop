<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('home-title')</title>

    <base href="{{ Config::get('app.url') }}">

    <meta property="og:title" content="@yield('home-title')">
    <meta name="description" property="og:description" content="@yield('home-description')">
    <meta name="keywords" property="og:keywords" content="{{ (array) $category ? 'sell ' . strtolower(str_replace('Sell ', '', $category->getAttribute('name'))) : $settings->getAttribute('general_settings')['seo_keywords'] }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:image" content="{{ (array) $category ? $category->getAttribute('image') : $settings->getAttribute('general_settings')['seo_image'] }}">
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">
	<meta property="og:site_name" content="RapidRecycle">

    @if ($steps)
    <meta property="product:price:amount" content="{{ $category->getAttribute('custom_text') }}">
    <meta property="product:price:currency" content="USD">
    <meta property="og:type" content="product">
    @else
    <meta property="og:type" content="website" />
    @endif

    <script type="application/ld+json" data-seo="Organization">
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": "RapidRecycle",
            "url": "http://rapid-recycle.com/",
            "telephone": "+16027062575",
            "email": "support@rapid-recycle.com"
        }
    </script>

    <script type="application/ld+json">{
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "https://rapid-recycle.com/",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://rapid-recycle.com/header-search?name={query}",
            "query-input": {
                "@type": "PropertyValueSpecification",
                "valueName": "query"
            }
        }
    }</script>

    <link rel="icon" type="image/x-icon" href="{{ asset('client/images/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('client/images/favicon/favicon.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--[if IE]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
    <![endif]-->

    <!-- Styles -->
    <style>
        @font-face {
            font-display: swap;
            font-family: "GothamPro";
            src: url(client/fonts/GothamPro-Regular/GothamPro-Regular.woff2) format("woff2"),
            url("client/fonts/GothamPro-Regular/GothamPro-Regular.woff2") format("woff");
            font-style: normal;
            font-weight: 400;
        }

        @font-face {
            font-display: swap;
            font-family: "GothamPro";
            src: url(client/fonts/GothamPro-Light/GothamPro-Light.woff2) format("woff2"),
            url("client/fonts/GothamPro-Light/GothamPro-Light.woff") format("woff");
            font-style: normal;
            font-weight: 300;
        }

        @font-face {
            font-display: swap;
            font-family: "GothamPro";
            src: url(client/fonts/GothamPro-Medium/GothamPro-Medium.woff2) format("woff2"),
            url("client/fonts/GothamPro-Medium/GothamPro-Medium.woff") format("woff");
            font-style: normal;
            font-weight: 500;
        }

        @font-face {
            font-display: swap;
            font-family: "GothamPro";
            src: url(client/fonts/GothamPro-Bold/GothamPro-Bold.woff2) format("woff2"),
            url("client/fonts/GothamPro-Bold/GothamPro-Bold.woff") format("woff");
            font-style: normal;
            font-weight: 700;
        }

        @font-face {
            font-display: swap;
            font-family: "HeliosCondBold";
            src: url("client/fonts/HeliosCondBold/HeliosCondBold.eot");
            src: url("client/fonts/HeliosCondBold/HeliosCondBold.eot?#iefix")format("embedded-opentype"),
            url("client/fonts/HeliosCondBold/HeliosCondBold.woff") format("woff"),
            url("client/fonts/HeliosCondBold/HeliosCondBold.ttf") format("truetype");
            font-style: normal;
            font-weight: normal;
        }
    </style>

    <link href="{{ asset('client/css/all.css') }}" rel="stylesheet" type="text/css">

    {!! $settings->getAttribute('code_insert') !!}

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script>
             grecaptcha.ready(function() {
                 grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
                    if (token) {
                      document.getElementById('recaptcha').value = token;
                    }
                 });
             });
    </script>
</head>
<body>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "url": "https://rapid-recycle.com/",
            "logo ": "https://rapid-recycle.com/client/images/mail_logo.png"
        }
    </script>

    <div id="app">
        <main class="py-4">
            <div class="content">
            @yield('content')
            </div>
        </main>
    </div>

    @if($steps)
        <script src="{{ URL::asset('common/js/routes.js') }}"></script>
        <script src="{{ URL::asset('js/app.js') }}"></script>
    @endif
    @yield('scripts')
    <script src="{{ URL::asset('client/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ URL::asset('client/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ URL::asset('client/js/common.js') }}"></script>
</body>
</html>
