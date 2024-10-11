@extends('layouts.loginlayout')

@section('content')
    <a class="hiddenanchor" id="signup" href="/register"></a>
    <a class="hiddenanchor" id="signin" href="/login"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <h1>{{ __('Reset Password') }}</h1>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group row">
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

                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-info btn-xs">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
