@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">권한관리</a></li>
                <li class="breadcrumb-item">사용자 등록/정보</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>사용자 정보 수정</h2>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-right">
                                    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                                </div>
                            </div>
                        </div>

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong> 오류 !! </strong> 입력 항목에 오류가 있습니다..<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Password:</strong>
                                    {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Confirm Password:</strong>
                                    {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Phone:</strong>
                                    {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Permissions:</strong><br>
                                    @foreach($permissions as $permission)
                                        @php ($checked = ($user->hasPermissionTo($permission))? "checked" : "")

                                        @if (in_array($permission, ['user', 'master']))
                                            <input type="checkbox" name="permission[]" value="{{ $permission }}" style="display: none;" {{ $checked }} />
                                            @continue
                                        @endif

                                        <label style="margin-right: 20px;">
                                            <input type="checkbox" name="permission[]" value="{{ $permission }}" class="js-switch" {{ $checked }} /> {{ $names[$permission] }}
                                        </label>
                                    @endforeach

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        {!! Form::hidden('from', 'edit') !!}
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
@include('layouts.partials.master_message')
        </div>
    </div>
    <!-- page content -->

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <style>
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }
    </style>
    <!-- Switchery -->
    <script src="/vendors/switchery/dist/switchery.min.js"></script>
@endsection
