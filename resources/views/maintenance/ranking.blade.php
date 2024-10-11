@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenance.ranking') }}">업데이트</a></li>
                <li class="breadcrumb-item">랭킹전관리</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>랭킹전관리</h2>
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
                                                <div class="col-form-label col-md-2 col-sm-2 label-align">
                                                    <select class="form-control" id="search_game_type">
                                                        <option value="">게임</option>
                                                        <option value="3">바카라</option>
                                                        <option value="4">바둑이</option>
                                                        <option value="5">하이로우</option>
                                                        <option value="6">세븐포커</option>
                                                        <option value="7">홀덤</option>
                                                    </select>
                                                </div>
                                                <div class="col-form-label col-md-2 col-sm-2 label-align">
                                                    <select class="form-control" id="search_sub_type">
                                                        <option value="">채널</option>
                                                        <option value="0">일반</option>
                                                        <option value="1">친구</option>
                                                        <option value="2">골드대전</option>
                                                        <option value="3">친구골드</option>
                                                    </select>
                                                </div>
                                                <label class="col-form-label col-md-3 col-sm-3 label-align">
                                                    <div class="input-group date" id="datepicker1">
                                                        <input type="text" class="form-control" id="search_start_date" placeholder="시작날짜" />
                                                        <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                    </div>
                                                </label>
                                                <label class="col-form-label col-md-3 col-sm-3 label-align">
                                                    <div class="input-group date" id="datepicker2">
                                                        <input type="text" class="form-control" id="search_end_date" placeholder="종료날짜" />
                                                        <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                    </div>
                                                </label>
                                                <div class="col-form-label col-md-1 col-sm-1 label-align">
                                                    <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>랭킹전 예약 리스트<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table" id="log_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>게임</th>
                                                <th>채널</th>
                                                <th>시작시간</th>
                                                <th>종료시간</th>
                                                <th>비고</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>랭킹전 추가 <small id="edit_mode">등록 </small> </h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table-bordered info-table" style="width: 100%">
                                            <tr>
                                                <th width="200">게임선택</th>
                                                <td><select class="form-control" id="edit_gametype" name="edit_gametype">
                                                        <option value="">게임</option>
                                                        <option value="3">바카라</option>
                                                        <option value="4">바둑이</option>
                                                        <option value="5">하이로우</option>
                                                        <option value="6">세븐포커</option>
                                                        <option value="7">홀덤</option>
                                                    </select></td>
                                                <th width="200">채널선택</th>
                                                <td><select class="form-control" id="edit_subtype" name="edit_subtype">
                                                        <option value="">채널</option>
                                                        <option value="0">일반</option>
                                                        <option value="1">친구</option>
                                                        <option value="2">골드대전</option>
                                                        <option value="3">친구골드</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <th>시작시간</th>
                                                <td style="text-align: left;">
                                                    <label class="col-form-label col-md-5 col-sm-5 label-align">
                                                        <div class="input-group date" id="datepicker3">
                                                            <input type="text" class="form-control" id="edit_startDate" placeholder="시작날짜" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </label>
                                                    <label class="col-form-label col-md-5 col-sm-5 label-align">
                                                        <div class="input-group date" id="datepicker4">
                                                            <input type="text" class="form-control" id="edit_startTime" placeholder="시작시간" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </label>
                                                </td>
                                                <th>종료시간</th>
                                                <td>
                                                    <label class="col-form-label col-md-5 col-sm-5 label-align">
                                                        <div class="input-group date" id="datepicker5">
                                                            <input type="text" class="form-control" id="edit_endDate" placeholder="종료날짜" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </label>
                                                    <label class="col-form-label col-md-5 col-sm-5 label-align">
                                                        <div class="input-group date" id="datepicker6">
                                                            <input type="text" class="form-control" id="edit_endTime" placeholder="종료시간" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="position:relative; width: 50%; float:left; padding-top:20px; border-left:0; border-right:0; border-bottom:0; text-align: left;">
                                            <button type="button" id="btn-reset" class="btn btn-primary reset-btn">초기화</button>
                                        </div>
                                        <div style="position:relative; width: 50%; float:left; padding-top:20px; border-left:0; border-right:0; border-bottom:0; text-align: right;">
                                            <input type="hidden" id="edit_id" value="-1"/>
                                            <button type="button" id="btn-save" class="btn btn-success save-btn">저장</button>
                                        </div>
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
    <style>
        ..dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter { display: none; }
        .paging_full_numbers { width: auto; }
        /*.dataTables_filter > label { width: 100%; text-align: left; }*/
        /*.dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }*/
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }
        .custom-file {font-size: 16px;}

        .info-table { width: 100%; }
        .info-table th { padding: 10px; text-align: center; vertical-align: middle; }
        .info-table td { padding: 10px; text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script>
        const gameTypes = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');
        const rankSubTypes = $.parseJSON('{!! json_encode(Helper::rankSubTypes()) !!}');

        $(document).ready(function () {
            $('#datepicker1').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker2').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker3').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker4').datetimepicker({
                format: 'HH:mm:ss'
            });
            $('#datepicker5').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker6').datetimepicker({
                format: 'HH:mm:ss'
            });

            let log_table = $('#log_table').DataTable({
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
                            return '랭킹전리스트_' + $("#search_start_date").val().replace(/-/gi, '') +'-'+$("#search_end_date").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            // search
            $('#btn-search').off('click').on('click', function() {
                let gameType = $('#search_game_type option:selected').val();
                let subType = $('#search_sub_type option:selected').val();
                let startDate = $('#search_start_date').val();
                let endDate = $('#search_end_date').val();

                if (startDate.length > 0) {
                    startDate = startDate + ' 00:00:00'
                }
                if (endDate.length > 0) {
                    endDate = endDate + ' 00:00:00'
                }

                loadList(gameType, subType, startDate, endDate);
            });

            // action : 저장
            $('#btn-save').off('click').on('click', function() {
                let id = $('#edit_id').val();
                let gameType = $('#edit_gametype').val();
                let subType = $('#edit_subtype').val();
                let startDate = $('#edit_startDate').val();
                let startTime = $('#edit_startTime').val();
                let endDate = $('#edit_endDate').val();
                let endTime = $('#edit_endTime').val();

                if (gameType.length === 0) {
                    alert('게임을 입력하세요.');
                    return false;
                }

                if (subType.length === 0) {
                    alert('채널을 선택하세요');
                    return false;
                }

                if (startDate.length === 0) {
                    alert('시작날짜를 입력하세요');
                }
                if (startTime.length === 0) {
                    alert('시작시간을 입력하세요');
                }

                if (endDate.length === 0) {
                    alert('종료날짜를 입력하세요');
                }
                if (endTime.length === 0) {
                    alert('종료시간을 입력하세요');
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/maintenance/editSchedule',
                    data: {
                        id: id,
                        gameType: gameType,
                        subType: subType,
                        startDate: startDate + ' ' + startTime,
                        endDate: endDate + ' ' + endTime,
                        from: 'ranking'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        alert(data.messages[0]);

                        reset();
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

            $('#btn-reset').off('click').on('click', function() {
                console.log('reset!');
               resetEdit();
            });

            let loadList = function (gameType, subType, startDate, endDate) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/maintenance/rankSchedule',
                    data: {
                        gameType: gameType,
                        subType: subType,
                        startDate: startDate,
                        endDate: endDate,
                        from: 'ranking'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        drawTables(data.scheduleList);
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
            };

            let drawTables = function (data) {
                log_table.clear();
                if (data.length > 0) {
                    let no = 0;
                    $(data).each(function (index, schedule) {
                        let tr = $('<tr id="tr_'+schedule.id+'">' +
                            '<td>'+(++no)+'</td>' +
                            '<td>'+gameTypes[schedule.gametype].name+'</td>' +
                            '<td>'+rankSubTypes[schedule.subtype]+'</td>' +
                            '<td>'+schedule.start+'</td>' +
                            '<td>'+schedule.end+'</td>' +
                            '<td><button class="btn-info btn-sm btn-edit-schedule" ' +
                            'data-id="'+schedule.id+'" ' +
                            'data-gametype="'+schedule.gametype+'" ' +
                            'data-subtype="'+schedule.subtype+'" ' +
                            'data-start="'+schedule.start+'" ' +
                            'data-end="'+schedule.end+'" ' +
                            '">수정</button></td>' +
                            '</tr>');
                        log_table.row.add(tr);
                    });
                }
                log_table.draw();
            };


            // iniLoad
            loadList('', '', '', '');
        });

        function reset() {
            document.location.reload();
        }

        function resetEdit() {
            $('#edit_mode').text('등록');
            $('#log_table tr').children('td, th').css('background', '#FFF');
            $('#edit_id').val(-1);
            $('#edit_gametype').val('');
            $('#edit_subtype').val('');
            $('#edit_startDate').val('');
            $('#edit_startTime').val('');
            $('#edit_endDate').val('');
            $('#edit_endTime').val('');
        }

        $(document).on('click', '.btn-edit-schedule', function() {
            resetEdit();

            $('#edit_mode').text('수정');

            let data = $(this).data();
            console.log('data', data);
            $('#tr_'+data.id).children('td, th').css('background', '#FFB6C1');

            $('#edit_id').val(data.id);
            $('#edit_gametype').val(data.gametype);
            $('#edit_subtype').val(data.subtype);
            $('#edit_startDate').val(data.start.substring(0, 10));
            $('#edit_startTime').val(data.start.substring(11, data.start.length));
            $('#edit_endDate').val(data.end.substring(0, 10));
            $('#edit_endTime').val(data.end.substring(11, data.end.length));
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
