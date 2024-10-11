@extends('layouts.loginlayout')

@section('content')
    <a class="hiddenanchor" id="signup" href="{{ route('register') }}"></a>
    <a class="hiddenanchor" id="signin" href="{{ route('login') }}"></a>

    <!--<div id="register" class="animate form registration_form">-->
    <div class="login_wrapper">
        <section class="login_content">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <h1>{{ __('Register') }}</h1>
                <div>
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

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
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                <div>
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-info btn-xs">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="separator">
                    <p class="change_link">Already a member ?
                        <a href="{{ route('login') }}" class="to_register"> Log in </a>
                    </p>

                    <div class="clearfix"></div>
                    <br />

                    <div>
                        <h1><i class="fa fa-dashboard"></i>{{ (count(explode(env('APP_HOST'), $_SERVER['HTTP_HOST'])) > 1 ? env('HEADER_NAME') : env('ANOTHER_NAME')) }} CMS</h1>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
