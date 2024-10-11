@extends('layouts.mainlayout')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('statistics.ccu') }}">지표 관리</a></li>
            <li class="breadcrumb-item">교환소 이용현황</li>
        </ol>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="row x_title">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>교환소 제한 예외등록</h2>
                            </div>
                        </div>
                    </div>


                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_content" style="padding-bottom: 0;">
                                    <div class="alert alert-danger" id="search-error-bag" style="display: none;">
                                        <ul id="search-errors" style="padding-bottom: 0; margin-bottom: 0;">
                                        </ul>
                                    </div>

                                    <form id="searchForm">
                                        <div class="item form-group row">
                                            <label class="col-md-3 col-sm-3 ">
                                                <label class="col-form-label">
                                                    <input type="radio" class="flat" name="search_type" value="nickname" checked="" required /> 닉네임 &nbsp;
                                                </label>
                                                <label class="col-form-label">
                                                    <input type="radio" class="flat" name="search_type" value="userSeq" /> 회원번호 &nbsp;
                                                </label>
                                                <label class="col-form-label">
                                                    <input type="radio" class="flat" name="search_type" value="email" /> Email
                                                </label>
                                                <label class="col-form-label label-align ml-2">
                                                    <select class="form-control" name="login_type">
                                                        <option value="3">플랫폼</option>
                                                        <option value="1">구글</option>
                                                        <option value="4">애플로그인</option>
                                                    </select>
                                                </label>
                                            </label>
                                            <div class="col-md-3">
                                                <label class="col-form-label col-md-12">
                                                    <input type="text" class="form-control" id="search_input" name="keyword" placeholder="검색어" />
                                                    <input type="text" style="display:none" />
                                                    <input type="hidden" id="nickname">
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="col-form-label">
                                                    <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                </label>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>기본정보 <small></small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table class="table table-bordered info-table">
                                        <tr>
                                            <th>Email</th>
                                            <th>닉네임</th>
                                            <th>회원번호</th>
                                            <th>접속 IP</th>
                                            <th>가입일자</th>
                                            <th>최종로그인</th>
                                            <th>상태</th>
                                            <th>상태변경 메시지</th>
                                            <th rowspan="2" style="vertical-align: middle;">
                                                <input type="hidden" id="user_seq" value="" />
                                                <button type="button" id="add_pass" class="btn btn-round btn-success">예외등록</button>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td id="member_id_email"></td>
                                            <td id="member_nickname"></td>
                                            <td id="member_seq"></td>
                                            <td id="member_remote_address"></td>
                                            <td id="member_created_date"></td>
                                            <td id="member_last_login_date"></td>
                                            <td id="member_status"></td>
                                            <td id="member_status_message">-</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="x_panel">
                                <div class="x_content col-md-12">
                                    <div class="x_title">
                                        <h2>교환소 제한 예외 유저 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <table class="table table-bordered info-table top-margin"  id="pass_table">
                                        <thead>
                                        <tr>
                                            <th class="first_row">번호</th>
                                            <th class="first_row">userEmail</th>
                                            <th class="first_row">닉네임</th>
                                            <th class="first_row">등록일</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 0;
                                            @endphp
                                            @foreach ($passList as $pass)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ ($pass->platform_id !== "") ? $pass->platform_id : $pass->google_email }}</td>
                                                    <td>{{ $pass->nickname }}</td>
                                                    <td>{{ $pass->created_date }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Switchery -->
<link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<!-- bootstrap-daterangepicker -->
<link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<!-- SheetJS js-xlsx -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
<!-- FileSaver savaAs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
<style>
    .dataTables_length { display: none; }
    .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
    .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
    .dataTables_filter > label { display: none; }
    .paging_full_numbers { width: auto; }
    .switchery { width:32px;height:20px }
    .switchery>small { width:20px;height:20px }
    .custom-file {font-size: 16px;}

    .info-table { width: 100%; margin-bottom: 1rem; color: #212529; }
    .info-table th { text-align: center; vertical-align: middle !important; }
    .info-table td { text-align: center; vertical-align: middle; }
    .info-table td.number { text-align: right; }
    .first_row { background-color: #fff; color: #000; }
    .second_row { background-color: #c0c0c0; color: #fff; font-size: 11px;}

    .log_table_wrapper {}
</style>

<script>
    const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');

    $(document).ready(function () {
        let pass_table = $('#pass_table').DataTable({
            aaSorting: [],
            bSort: false,
            pageLength: 10,
            pagingType: 'full_numbers',
            language: {
                "emptyTable": "데이터가 없습니다."
            },
        });



        // search user
        $('#btn-search').off('click').on('click', function() {
            $('#search-errors').html('');

            let searchType = $(':input:radio[name=search_type]:checked').val();
            let searchKeyword = $(':input:text[name=keyword]').val();

            if (searchKeyword.length === 0) {
                alert('검색어를 입력하세요');
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/statistics/searchPassUser',
                data: {
                    type: searchType,
                    keyword: searchKeyword,
                    platform: $('select[name=login_type]').val(),
                    from: 'exchangePassUser'
                },
                dataType: 'json',
                success: function(data) {
                     console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }

                    $("#search-error-bag").hide();

                    let accountInfo = data.accountInfo;
                    let userInfo = data.userInfo;
                    let loginLog = data.loginLog;
                    let adminLog = data.adminLog;

                    let email_id = accountInfo.google_email;
                    if (accountInfo.google_email === '') {
                        email_id = accountInfo.platform_id;
                    }
                    // user info
                    $('#user_seq').val(accountInfo.user_seq);       // for force close

                    $('#member_id_email').text(email_id);
                    $('#member_nickname').text(accountInfo.nickname);
                    $('#member_seq').text(accountInfo.user_seq);
                    $('#member_created_date').text(getFormattedDatetime(userInfo.reg_date));
                    if (loginLog !== null) {
                        $('#member_last_login_date').text(getFormattedDatetime(loginLog.log_date));
                        $('#member_remote_address').text(loginLog.ip);
                    } else {
                        $('#member_last_login_date').text('');
                        $('#member_remote_address').text('');
                    }
                    $('#member_status').text(stateNames[accountInfo.user_state]);
                    if (adminLog !== null) {
                        $('#member_status_message').text(adminLog.reason);
                    } else {
                        $('#member_status_message').text('');
                    }
                },
                error: function(data) {
                    if (data.status === 419) {
                        alert('세션이 만료되었습니다.');
                        location.href = "/login";
                    }
                    let errors = $.parseJSON(data.responseText);
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();
                }
            });
        });

    });

    $('#add_pass').unbind('click').bind('click', function() {
        // check user select
        let userSeq = $('#user_seq').val();
        if (userSeq.length === 0) {
            let errors = {
                error: true,
                messages: ['먼저 사용자를 검색하세요.'],
            };
            // console.log(errors);
            $('#search-errors').html('');
            $.each(errors.messages, function(key, value) {
                $('#search-errors').append('<li>' + value + '</li>');
            });
            $("#search-error-bag").show();

            return false;
        } else {

            if ( !confirm('해당회원을 예외 유저로 등록하시겠습니까?') ) {
                return false;
            }


            // ajax call force close
            $('#search-errors').html('');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/statistics/addPassUser',
                data: {
                    user_seq: userSeq,
                    from: 'pass'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.error === true) {
                        $('#search-errors').html('');
                        $('#search-errors').append('<li>' + data.messages + '</li>');
                        $("#search-error-bag").show();
                        return;
                    }

                    $("#search-error-bag").hide();

                    alert('등록 되었습니다.');
                    document.location.reload();
                },
                error: function(data) {
                    if (data.status === 419) {
                        alert('세션이 만료되었습니다.');
                        location.href = "/login";
                    }
                    let errors = $.parseJSON(data.responseText);
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();
                }
            });
        }
    });

    // listing
    function loadList() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/statistics/limitPassList',
            data: {
                from: 'notice'
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.error === true) {
                    console.log('checked error');
                    return;
                }
                $("#search-error-bag").hide();

                drawTable(data);
            },
            error: function (data) {
                if (data.status === 419) {
                    alert('세션이 만료되었습니다.');
                    location.href = "/login";
                }
                let errors = $.parseJSON(data.responseText);
                // console.log(errors);
                $('#search-errors').html('');
                $.each(errors.messages, function (key, value) {
                    $('#search-errors').append('<li>' + value + '</li>');
                });
                $("#search-error-bag").show();
            }
        });
    }

    function drawTable(data) {
        let passList = data.passList;
        // load list
        let pass_table = $('#pass_table').DataTable();
        pass_table.clear();
        if (passList.length > 0) {
            let no = 0;
            $(passList).each(function(index, pass) {
                console.log('pass member', pass);
                let tr = $('<tr>' +
                    '<td>' + (++no) + '</td>'+
                    '<td>' + (pass.platform_id !== '') ? pass.platform_id : pass.google_email + '</td>'+
                    '<td>' + pass.nickname + '</td>'+
                    '<td>' + pass.created_date + '</td>'+
                    '</tr>');
                pass_table.row.add(tr);
            });
        }
        pass_table.draw();
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

<!-- bootstrap-daterangepicker -->
<script src="/vendors/moment/min/moment.min.js"></script>
<script src="/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
@endsection
