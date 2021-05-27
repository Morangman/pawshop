<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('home-title')</title>

    <base href="{{ Config::get('app.url') }}">

    <meta property="og:title" content="@yield('home-title')">
    <meta name="description" property="og:description" content="{{ (array) $category ? View::getSection('home-title', $settings->getAttribute('general_settings')['seo_meta']) : $settings->getAttribute('general_settings')['seo_meta'] }}">
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

    <link rel="icon" type="image/x-icon" href="{{ asset('client/images/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('client/images/favicon/favicon.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--[if IE]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
    <![endif]-->

    <!-- Styles -->
    <link href="{{ asset('client/css/all.css') }}" rel="stylesheet" type="text/css">

    {!! $settings->getAttribute('code_insert') !!}
</head>
<body>
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
