@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ URL::route('web.login.post') }}">
                            @csrf
                            <div class="checkout-content-step">
                                <div class="inner-block auth-form-centered">
                                    <div class="checkout-customer checkout-login-form auth-form">
                                        <h4>{{ Lang::get('login.title') }}</h4> </br>
                                        @include('partial.alerts.block')
                                        <div class="input-block">
                                            <input id="email" placeholder="{{ Lang::get('auth.register.form.fields.email.label') }}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Request::old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="input-block">
                                            <input id="password" placeholder="{{ Lang::get('auth.register.form.fields.password.label') }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="password-forget">
                                            <a href="{{ URL::route('web.password.request') }}" class="forgot-password">{{ Lang::get('auth.login.form.links.forgot_password') }}</a>
                                        </div>
                                        <div class="bottom-links">
                                            <button type="submit" class="btn red-btn">
                                                {{ Lang::get('login.title') }}
                                            </button>
                                            <div class="divider">or</div>
                                            <a href="{{ URL::route('web.register') }}" class="forgot-password">{{ Lang::get('auth.register.form.buttons.submit.text') }}</a>
                                            <ul class="socials">
                                                <li>
                                                    <a href="{{ URL::route('redirect-google') }}"><img src="{{ asset('client/images/google.svg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::route('redirect-facebook') }}"><img src="{{ asset('client/images/facebook.svg') }}" alt=""></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
