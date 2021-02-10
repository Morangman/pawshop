<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @meta_tags
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--[if IE]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
    <![endif]-->
    <title>Phone Repairs</title>

    <!-- Fonts -->
    <link href="{{ asset('client/css/app.css') }}" rel="stylesheet" type="text/css">

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

    <script src="{{ URL::asset('common/js/routes.js') }}"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('client/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ URL::asset('client/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ URL::asset('client/js/common.js') }}"></script>
    @yield('scripts')
</body>
</html>
