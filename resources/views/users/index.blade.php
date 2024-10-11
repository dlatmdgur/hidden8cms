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
                                    <h2>사용자 목록</h2>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                <!--
                                <div class="pull-left">
                                    <div class="form-group row">
                                        <form class="form-horizontal form-label-left">
                                            <label class="control-label col-md-2 col-sm-2 ">사용자</label>
                                            <div class="col-md-4 col-sm-4 ">
                                                <select name="type" class="form-control">
                                                    <option value="user_id">ID</option>
                                                    <option value="username">이름</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-sm-6 ">
                                                <div class="input-group">
                                                    <input type="text" name="searchValue" class="form-control">
                                                    &nbsp;<span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary">검색</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                -->
                                <div class="pull-right">
                                    <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>이름</th>
                                <th>Email</th>
                                <th>권한</th>
                                <th style="border-right: 1px solid #fff !important">Action</th>
                                <th style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important"></th>
                                <th style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important"></th>
                                <th style="border-left: 1px solid #fff !important"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($data as $key => $user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($permissions as $permission)
                                            @if (in_array($permission, ['user', 'master']))
                                                @continue
                                            @endif

                                            @php ($checked = ($user->hasPermissionTo($permission))? "checked" : "")
                                            <label style="margin-right: 20px;">
                                                <input type="checkbox" name="permission" value="{{ $permission }}" class="js-switch" {{ $checked }} readonly /> {{ $names[$permission] }}
                                            </label>
                                        @endforeach
                                    </td>
                                    <td style="border-right: 1px solid #fff !important">
                                        <a class="btn btn-info btn-round btn-sm" href="{{ route('users.show',$user->id) }}">Show</a>
                                    </td>
                                    <td style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important">
                                        <a class="btn btn-primary btn-round btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                    </td>
                                    <td style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important">
                                        {!! Form::open(['method' => 'POST','route' => ['reset', $user->id],'style'=>'display:inline','onsubmit' => 'return ConfirmReset()']) !!}
                                        {!! Form::submit('Reset', ['class' => 'btn btn-success btn-round btn-sm btn-reset']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                    <td style="border-left: 1px solid #fff !important">
                                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-round btn-sm btn-delete']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>

@include('layouts.partials.master_message')
        </div>
    </div>

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter > label { display: none }
        .dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }
        .paging_full_numbers { width: auto; }
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }
    </style>

    <script>
        function ConfirmReset() {
            if (!confirm('해당 유저의 패스워드를 초기화 하시겠습니까?')) {
                return false;
            } else {
                return true;
            }
        }

        function ConfirmDelete() {
            if (!confirm('해당 유저를 삭제 하시겠습니까?')) {
                return false;
            } else {
                return true;
            }
        }
    </script>

    <!-- Datatables -->
    <script src="/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="/vendors/jszip/dist/jszip.min.js"></script>
    <script src="/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Switchery -->
    <script src="/vendors/switchery/dist/switchery.min.js"></script>

@endsection
