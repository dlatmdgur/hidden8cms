@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('monitor.ban') }}">모니터링</a></li>
                <li class="breadcrumb-item">대량 ID</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>대량 ID 검색</h2>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="x_content">
                            <table class="table table-bordered info-table">
                                <tr>
                                    <th>날짜 선택</th>
                                    <td>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker">
                                                <input type="text" class="form-control" id="search_start_date1" />
                                                <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker2">
                                                <input type="text" class="form-control" id="search_end_date1" />
                                                <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-1 col-sm-5 label-align">
                                            <button type="button" id="btn-search-log" class="btn btn-secondary">검색</button>
                                        </label>
                                        <label class="col-form-label col-md-5 col-sm-5 label-align">
                                            <button type="button" class="btn btn-primary period-selector" period="1" target="1">1일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="3" target="1">3일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="7" target="1">7일</button>
                                            <button type="button" class="btn btn-success period-selector" period="30" target="1">1개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="90" target="1">3개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="180" target="1">6개월</button>
                                            <button type="button" class="btn btn-warning period-selector" period="365" target="1">1년</button>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>IP 당 접속 유저 수 <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                    <table class="table table-bordered info-table" id="ip_table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>IP</th>
                                            <th>유저수</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="x_panel">
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>IP <span id="search_ip"></span> 접속 유저</h4>
                                    <table class="table table-bordered info-table" id="users_table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>유저번호</th>
                                            <th>닉네임</th>
                                            <th>구글ID / 플랫폼 ID</th>
                                            <th>상태</th>
                                            <th>유료보석</th>
                                            <th>무료보석</th>
                                            <th>칩</th>
                                            <th>금고 칩</th>
                                            <th>골드</th>
                                            <th>금고골드</th>
                                            <th>최근 로그인 날짜</th>
                                        </tr>
                                        </thead>
                                        <tbody>

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

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter > label > input{ display: inline-block; width: 250px; margin-left: 10px; }
        .paging_full_numbers { width: auto; }
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script>

        $(document).ready(function () {
            let ip_table = $('#ip_table').DataTable({
                aaSorting: [],
                bSort: false,
                pageLength: 10,
                pagingType: 'full_numbers',
                language: {
                    "emptyTable": "데이터가 없습니다."
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: function() {
                            return '모니터링_IP별_유저수_' + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            let users_table = $('#users_table').DataTable({
                aaSorting: [],
                bSort: false,
                pageLength: 10,
                pagingType: 'full_numbers',
                language: {
                    "emptyTable": "데이터가 없습니다."
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: function() {
                            return '모니터링_IP별_유저정보_' + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            $('#datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker2').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            // date search make period
            $('.period-selector').off('click').on('click', function () {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                fillSearchDate(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
            });

            // user log
            $('#btn-search-log').off('click').on('click', function() {
                $('#search-errors').html('');

                let startDate = $('#search_start_date1').val();
                let endDate = $('#search_end_date1').val();

                if (startDate.length === 0 || endDate.length === 0) {
                    alert('검색 기간을 선택하세요');
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/monitor/searchIp',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        from: 'ipUsers'
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        $('#search_period').text(startDate + ' ~ ' + endDate);

                        let ipList = data.ipList;
                        ip_table.clear();
                        $('#ip_table tbody').empty();
                        if (ipList.length > 0) {
                            let no = 0;
                            $(ipList).each(function(index, list) {
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + list.ip + '</td>' +
                                    '<td>' + numberFormat(list.user_count) + '</td>' +
                                    '<td><button class="btn btn-info btn-sm btn_user_list" data-ip="'+list.ip+'" data-start="'+startDate+'" data-end="'+endDate+'">유저 리스트</button></td>' +
                                    '</tr>');
                                ip_table.row.add(tr);
                            });
                        }
                        ip_table.draw();

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

            // user log
            $(document).on('click', '.btn_user_list', function() {
                $('#search-errors').html('');

                let searchData = $(this).data();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/monitor/searchIpUsers',
                    data: {
                        ip: searchData.ip,
                        startDate: searchData.start,
                        endDate: searchData.end,
                        from: 'ipUsers'
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        $('#search_ip').text('['+ searchData.ip +']');

                        let ipUserList = data.ipUserList;
                        users_table.clear();
                        $('#users_table tbody').empty();
                        if (ipUserList.length > 0) {
                            let no = 0;
                            $(ipUserList).each(function(index, list) {
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + list.user_seq + '</td>' +
                                    '<td>' + list.nickname + '</td>' +
                                    '<td>' + ( (list.google_email.length > 0)? list.google_email : list.platform_id ) + '</td>' +
                                    '<td>' + list.user_state + '</td>' +
                                    '<td>' + list.gem + '</td>' +
                                    '<td>' + list.gem_event + '</td>' +
                                    '<td>' + numberToKorean(list.chip) + '</td>' +
                                    '<td>' + numberToKorean(list.safe_chip) + '</td>' +
                                    '<td>' + numberToKorean(list.gold) + '</td>' +
                                    '<td>' + numberToKorean(list.safe_gold) + '</td>' +
                                    '<td>' + list.log_date + '</td>' +
                                    '</tr>');
                                users_table.row.add(tr);
                            });
                        }
                        users_table.draw();

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
