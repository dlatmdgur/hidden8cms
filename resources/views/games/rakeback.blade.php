@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.info') }}">게임조회</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.rakeBack') }}">저금통 조회</a></li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>저금통 조회</h2>
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
                                                        <option value="1">구글</option>
                                                        <option value="3">플랫폼</option>
                                                        <option value="2">유니티</option>
                                                        <option value="0">게스트</option>
                                                    </select>
                                                </label>
                                            </label>
                                            <div class="col-md-3">
                                                <label class="col-form-label col-md-12">
                                                    <input type="text" class="form-control" id="search_input" name="keyword" placeholder="검색어" />
                                                    <input type="text" style="display:none" />
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="col-form-label">
                                                    <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row col-md-12 col-sm-12">
                                        <label class="col-form-label col-md-1 col-sm-1 label-align">
                                            <span style="font-weight: bold;line-height: 35px; text-align: right;">조회 기간 선택 : </span>
                                        </label>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker">
                                                <input type="text" class="form-control" id="search_start_date" />
                                                <span class="input-group-addon" style="cursor:pointer;">
                                                    <i class="fa fa-calendar mt-1"></i>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker2">
                                                <input type="text" class="form-control" id="search_end_date" />
                                                <span class="input-group-addon" style="cursor:pointer;">
                                                    <i class="fa fa-calendar mt-1"></i>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-5 col-sm-5 label-align">
                                            <button type="button" class="btn btn-primary period-selector" period="1">1일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="3">3일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="7">7일</button>
                                            <button type="button" class="btn btn-success period-selector" period="30">1개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="90">3개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="180">6개월</button>
                                            <button type="button" class="btn btn-warning period-selector" period="365">1년</button>
                                        </label>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>저금통정보 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <tr>
                                                <th width="250">멤버쉽 타입</th>
                                                <td id="members_grade"></td>
                                                <th width="250">적립율</th>
                                                <td id="members_rate"></td>
                                                <th width="250">적립골드</th>
                                                <td id="rakeback_gold"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>


                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>저금통 적립 내역 조회<small>(일별/게임별)</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table"  id="in_log_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>날짜</th>
                                                <th>게임</th>
                                                <th>적립액</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>저금통 출금 내역 조회<small>(건별)</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table"  id="out_log_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>출금 일시</th>
                                                <th>금액</th>
                                                <th>출금 전 저금통 잔액</th>
                                                <th>출금 전 골드</th>
                                                <th>출금 후 저금통 잔액</th>
                                                <th>출금 후 골드</th>
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
    </div>

    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter { display: none; }
        .paging_full_numbers { width: auto; }
        /*.dataTables_filter > label { width: 100%; text-align: left; }*/
        /*.dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }*/

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script>
        const membersInfo = $.parseJSON('{!! json_encode(Helper::membersInfo()) !!}');
        const gameTypes = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');

        $(document).ready(function () {
            let in_log_table = $('#in_log_table').DataTable({
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
                            return '저금통입금내역_' + $('#nickname').val() + '_' + $("#search_start_date").val().replace(/-/gi, '') +'-'+$("#search_end_date").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            let out_log_table = $('#out_log_table').DataTable({
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
                            return '저금통출금내역_' + $('#nickname').val() + '_' + $("#search_start_date").val().replace(/-/gi, '') +'-'+$("#search_end_date").val().replace(/-/gi, '');
                        }
                    }
                ],
            });

            $('#search_start_date').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "locale": {
                    "format": "YYYY-MM-DD HH:mm:ss",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ date("Y-m-d") }} 00:00:00').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });
            $('#search_end_date').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "locale": {
                    "format": "YYYY-MM-DD HH:mm:ss",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ date("Y-m-d") }} 23:59:59').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });

            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });

            // date search make period
            $('.period-selector').off('click').on('click', function() {
                let period = $(this).attr('period');
                fillSearchDateTime(parseInt(period), 'search_start_date', 'search_end_date');
            });

            // Search & display
            $('#btn-search').off('click').on('click', function() {
                $('#search-errors').html('');

                let keyword = $('#search_input').val();
                let startDate = $('#search_start_date').val();
                let endDate = $('#search_end_date').val();

                if (keyword.length === 0) {
                    alert('검색어를 입력하세요.');
                    return false;
                }

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
                    url: '/game/rakeBackLogs',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val(),
                        platform: $('select[name=login_type]').val(),
                        keyword: $(':input:text[name=keyword]').val(),
                        startDate: startDate,
                        endDate: endDate,
                        from: 'game'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

                        let userInfo = data.userInfo;
                        $('#user_seq').val(userInfo.user_seq);
                        $('#nickname').val(userInfo.nickname);


                        $('#members_grade').text(membersInfo[userInfo.members_type].name);
                        $('#members_rate').text( parseFloat(membersInfo[userInfo.members_type].rakeback).toFixed(2) );
                        $('#rakeback_gold').text(numberToKorean(userInfo.rakeback_gold));

                        // inlogs
                        let inLog = data.rakeBackInLogs;
                        in_log_table.clear();
                        $('#in_log_table tbody').empty();
                        if (inLog.length > 0) {
                            let no = 0;
                            $(inLog).each(function(index, log) {
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.log_date + '</td>' +
                                    '<td>' + gameTypes[log.game_type].name + '</td>' +
                                    '<td>' + numberToKorean(log.rakeback) + '</td>' +
                                    '</tr>');
                                in_log_table.row.add(tr);
                            });
                        }
                        in_log_table.draw();

                        // outlogs
                        let outLog = data.rakeBackOutLogs;
                        out_log_table.clear();
                        $('#out_log_table tbody').empty();
                        if (outLog.length > 0) {
                            let no = 0;
                            $(outLog).each(function(index, log) {
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.log_date + '</td>' +
                                    '<td>' + numberToKorean(log.amount) + '</td>' +
                                    '<td>' + numberToKorean(log.before_rakeback) + '</td>' +
                                    '<td>' + numberToKorean(log.before_gold) + '</td>' +
                                    '<td>' + numberToKorean(log.after_rakeback) + '</td>' +
                                    '<td>' + numberToKorean(log.after_gold) + '</td>' +
                                    '</tr>');
                                out_log_table.row.add(tr);
                            });
                        }
                        out_log_table.draw();

                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
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
@endsection
