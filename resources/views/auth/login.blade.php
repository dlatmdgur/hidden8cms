@extends('layouts.loginlayout')

@section('content')
    <a class="hiddenanchor" id="signup" href="{{ route('register') }}"></a>
    <a class="hiddenanchor" id="signin" href="{{ route('login') }}"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <h1>{{ __('Login') }}</h1>
                    <div>
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-info btn-xs">
                            {{ __('Login') }}
                        </button>

                        <!--
                        @if (Route::has('password.request'))
                            <a class="reset_pass btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                        -->
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <!--
                        <p class="change_link">New to site?
                            <a href="#signup" class="to_register"> Create Account </a>
                        </p>
                        -->
                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-dashboard"></i>>{{ (count(explode(config('hosting.app_host'), $_SERVER['HTTP_HOST'])) > 1 ? config('hosting.header_name') : config('hosting.another_name')) }} CMS</h1>

                            <h6><b><span style="margin-right: 10px;">YOUR IP :</span> {{ $_SERVER['REMOTE_ADDR'] }}</b></h6>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
