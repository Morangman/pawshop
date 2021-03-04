@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ URL::route('web.password.reset.send') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">


                            <div class="checkout-content-step">
                                <div class="inner-block auth-form-centered">
                                    <div class="checkout-customer checkout-login-form auth-form">
                                        <h4>{{ Lang::get('auth.password_reset.title') }}</h4> </br>
                                        @include('partial.alerts.block')
                                        @if (Session::has('errors'))
                                                @foreach ($errors->all() as $error)
                                                    <h4>{{ $error }}</h4>
                                                @endforeach
                                        @endif
                                        <div class="input-block">
                                            <input
                                                id="password"
                                                type="password"
                                                class="input-text with-border{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                placeholder="{{ Lang::get('auth.register.form.fields.password.label') }}"
                                                name="password"
                                                required/>

                                            @error('password')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="input-block">
                                            <input
                                                id="password"
                                                type="password"
                                                class="input-text with-border"
                                                placeholder="{{ Lang::get('auth.register.form.fields.confirm_password.label') }}"
                                                name="password_confirmation"
                                                required/>

                                            @error('password_confirmation')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="password-forget">
                                            <a href="{{ URL::route('web.password.request') }}" class="forgot-password">{{ Lang::get('auth.login.form.links.forgot_password') }}</a>
                                        </div>
                                        <div class="bottom-links">
                                            <button type="submit" class="btn red-btn">
                                                {{ Lang::get('auth.password_reset.form.buttons.reset.text') }}
                                            </button>
                                            <div class="divider">or</div>
                                            <a href="{{ URL::route('web.login.show') }}" class="forgot-password">{{ Lang::get('login.title') }}</a>
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
    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection

