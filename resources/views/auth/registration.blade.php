@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ URL::route('web.register.store') }}">
                            @csrf
                            <div class="checkout-content-step">
                                <div class="inner-block">
                                    <div class="checkout-customer checkout-login-form">
                                        <h4>{{ Lang::get('auth.register.form.buttons.submit.text') }}</h4> </br>
                                        @include('partial.alerts.block')
                                        <div class="input-block">
                                            <input type="text"
                                                   class="input-text with-border{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                   placeholder="{{ Lang::get('auth.register.form.fields.name.label') }}"
                                                   name="name"
                                                   value="{{ Request::old('name') }}"required/>

                                            @error('name')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="input-block">
                                            <input type="email"
                                                   class="input-text with-border{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                   placeholder="{{ Lang::get('auth.register.form.fields.email.label') }}"
                                                   name="email"
                                                   value="{{ Request::old('email') }}"
                                                   required/>

                                            @error('email')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="input-block">
                                            <input type="tel"
                                                   class="input-text with-border{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                   placeholder="{{ Lang::get('auth.register.form.fields.phone.label') }}"
                                                   name="phone"
                                                   value="{{ Request::old('phone') }}"
                                                   required/>

                                            @error('phone')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
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
                                                {{ Lang::get('auth.register.form.buttons.submit.text') }}
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
    @yield('footer', View::make('footer'))
@endsection
