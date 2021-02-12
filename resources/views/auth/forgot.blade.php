@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if (Session::has('errors'))
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ URL::route('web.password.email') }}">
                            @csrf
                            <div class="checkout-content-step">
                                <div class="inner-block">
                                    <div class="checkout-customer checkout-login-form">
                                        <h4>{{ Lang::get('login.title') }}</h4> </br>
                                        @include('partial.alerts.block')
                                        <div class="input-block">
                                            <input
                                                type="email"
                                                class="input-text with-border{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                placeholder="{{ Lang::get('auth.password_request.fields.email.label') }}"
                                                name="email"
                                                required/>

                                            @error('email')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="bottom-links">
                                            <button type="submit" class="btn red-btn">
                                                {{ Lang::get('auth.password_request.buttons.send.text') }}
                                            </button>
                                            <div class="divider">or</div>
                                            <a href="{{ URL::route('web.register') }}" class="forgot-password">{{ Lang::get('auth.register.form.buttons.submit.text') }}</a>
                                            <ul class="socials">
                                                <li>
                                                    <a href=""><img src="{{ asset('client/images/google.svg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a href=""><img src="{{ asset('client/images/facebook.svg') }}" alt=""></a>
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

