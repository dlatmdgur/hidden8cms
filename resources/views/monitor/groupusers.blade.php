@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('monitor.ban') }}">모니터링</a></li>
                <li class="breadcrumb-item">그룹 유저</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>그룹 유저 접속 기록</h2>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif

                        <div class="x_content">
                            <div class="alert alert-danger" id="search-error-bag" style="display: none;">
                                <ul id="search-errors" style="padding-bottom: 0; margin-bottom: 0;">
                                </ul>
                            </div>
                            <table class="table table-bordered info-table">
                                <tr>
                                    <th>날짜 선택</th>
                                    <td colspan="5">
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
                                <tr>
                                    <th>그룹 선택</th>
                                    <td>
                                        <label class="col-md-12 col-sm-12 text-left">
                                            @foreach($groups as $index => $group)
                                                <label class="col-form-label mr-3">
                                                    <input type="radio" class="flat" name="search_group" value="{{ $group->group }}" {{ ($index == 0)? "checked" : "" }} required /> {{ $group->group }}&nbsp;
                                                </label>
                                            @endforeach
                                        </label>
                                    </td>
                                    <th>정렬 기준</th>
                                    <td>
                                        <label class="col-md-12 col-sm-12 text-left">
                                            <label class="col-form-label mr-3">
                                                <input type="radio" class="flat" name="search_orderby" value="log_date" checked required /> 날짜
                                            </label>
                                            <label class="col-form-label">
                                                <input type="radio" class="flat" name="search_orderby" value="user_seq"  required /> 회원번호
                                            </label>
                                        </label>
                                    </td>
                                    <th>정렬 방향</th>
                                    <td>
                                        <label class="col-md-12 col-sm-12 text-left">
                                            <label class="col-form-label mr-3">
                                                <input type="radio" class="flat" name="search_sort" value="DESC" checked required /> 내림차순
                                            </label>
                                            <label class="col-form-label">
                                                <input type="radio" class="flat" name="search_sort" value="ASC"  required /> 오름차순
                                            </label>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>접속기록 <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                    <table class="table table-bordered info-table" id="login_table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>회원번호</th>
                                            <th>닉네임</th>
                                            <th>Email</th>
                                            <th>골드</th>
                                            <th>금고골드</th>
                                            <th>합계</th>
                                            <th>IP</th>
                                            <th>로그인시간</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>홀덤 <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                    <table class="table table-bordered info-table" id="holdem_table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>회원번호</th>
                                            <th>닉네임</th>
                                            <th>Email</th>
                                            <th>변동골드</th>
                                            <th>게임 수</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>바둑이 <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                    <table class="table table-bordered info-table" id="badugi_table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>회원번호</th>
                                            <th>닉네임</th>
                                            <th>Email</th>
                                            <th>변동골드</th>
                                            <th>게임 수</th>
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
            let login_table = $('#login_table').DataTable({
                aaSorting: [],
                bSort: true,
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
                            return '그룹유저_접속기록_' + $(':input:radio[name=search_group]:checked').val() + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            let holdem_table = $('#holdem_table').DataTable({
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
                            return '그룹유저_홀덤_' + $(':input:radio[name=search_group]:checked').val() + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            let badugi_table = $('#badugi_table').DataTable({
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
                            return '그룹유저_바둑이_' + $(':input:radio[name=search_group]:checked').val() + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
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

                let group = $(':input:radio[name=search_group]:checked').val();
                let startDate = $('#search_start_date1').val();
                let endDate = $('#search_end_date1').val();
                let orderBy = $(':input:radio[name=search_orderby]:checked').val();
                let sort = $(':input:radio[name=search_sort]:checked').val();

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
                    url: '/monitor/groupLogs',
                    data: {
                        group: group,
                        startDate: startDate,
                        endDate: endDate,
                        orderBy: orderBy,
                        sort: sort,
                        from: 'groupUsers'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        $('#search_period').text(startDate + ' ~ ' + endDate);

                        let loginLogs = data.groupUsers;
                        login_table.clear();
                        $('#login_table tbody').empty();
                        if (loginLogs.length > 0) {
                            let no = 0;
                            $(loginLogs).each(function(index, log) {
                                // console.log(log);
                                let id_email = (log.google_email !== "") ? log.google_email : log.platform_id;
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.user_seq + '</td>' +
                                    '<td>' + log.nickname + '</td>' +
                                    '<td>' + id_email + '</td>' +
                                    '<td>' + numberFormat(log.gold) + '</td>' +
                                    '<td>' + numberFormat(log.safe_gold) + '</td>' +
                                    '<td>' + numberFormat( parseInt(log.gold) + parseInt(log.safe_gold) ) + '</td>' +
                                    '<td>' + log.ip + '</td>' +
                                    '<td>' + log.log_date + '</td>' +
                                    '</tr>');
                                login_table.row.add(tr);
                            });
                        }
                        login_table.draw();

                        let holdemLogs = data.groupHoldem;
                        holdem_table.clear();
                        $('#holdem_table tbody').empty();
                        if (holdemLogs.length > 0) {
                            let no = 0;
                            $(holdemLogs).each(function(index, log) {
                                // console.log(log);
                                let id_email = (log.google_email !== "") ? log.google_email : log.platform_id;
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.user_seq + '</td>' +
                                    '<td>' + log.nickname + '</td>' +
                                    '<td>' + id_email + '</td>' +
                                    '<td>' + numberFormat(log.change_money) + '</td>' +
                                    '<td>' + numberFormat(log.game_count) + '</td>' +
                                    '</tr>');
                                holdem_table.row.add(tr);
                            });
                        }
                        holdem_table.draw();

                        let badugiLogs = data.groupBadugi;
                        badugi_table.clear();
                        $('#badugi_table tbody').empty();
                        if (badugiLogs.length > 0) {
                            let no = 0;
                            $(badugiLogs).each(function(index, log) {
                                // console.log(log);
                                let id_email = (log.google_email !== "") ? log.google_email : log.platform_id;
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.user_seq + '</td>' +
                                    '<td>' + log.nickname + '</td>' +
                                    '<td>' + id_email + '</td>' +
                                    '<td>' + numberFormat(log.change_money) + '</td>' +
                                    '<td>' + numberFormat(log.game_count) + '</td>' +
                                    '</tr>');
                                badugi_table.row.add(tr);
                            });
                        }
                        badugi_table.draw();

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
