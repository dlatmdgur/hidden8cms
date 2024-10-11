@extends('layouts.mainlayout')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Log</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('logs.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('logs.store') }}" method="POST">
        @csrf


        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Type:</strong>
                    <input type="text" name="type" class="form-control" placeholder="Type">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Menu:</strong>
                    <input type="text" name="menu" class="form-control" placeholder="Menu">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Action:</strong>
                    <input type="text" name="action" class="form-control" placeholder="Action">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Params:</strong>
                    <textarea class="form-control" style="height:150px" name="params" placeholder="Params"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Reason:</strong>
                    <input type="text" name="reason" class="form-control" placeholder="Reason">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Extra:</strong>
                    <input type="text" name="extra" class="form-control" placeholder="Extra">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>User ID:</strong>
                    <input type="text" name="user_id" class="form-control" placeholder="User ID">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>User Name:</strong>
                    <input type="text" name="username" class="form-control" placeholder="User Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>


    </form>

@endsection
