@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('statistics.ccu') }}">지표 관리</a></li>
                <li class="breadcrumb-item">아이템 구매 및 소비 현황</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>아이템 구매 및 보석 소비 현황</h2>
                                </div>
                            </div>
                        </div>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="x_content">
                            <table class="table-bordered info-table">
                                <tr>
                                    <th>날짜 선택</th>
                                    <td>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker">
                                                <input type="text" class="form-control" id="search_start_date1" />
                                                <span class="input-group-addon" style="cursor:pointer;">
                                                    <i class="fa fa-calendar mt-1"></i>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker2">
                                                <input type="text" class="form-control" id="search_end_date1" />
                                                <span class="input-group-addon" style="cursor:pointer;">
                                                    <i class="fa fa-calendar mt-1"></i>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-1 col-sm-5 label-align">
                                            <button type="button" id="btn-search-log" class="btn btn-secondary">검색</button>
                                        </label>
                                        <label class="col-form-label col-md-5 col-sm-5 label-align">
                                            <button type="button" class="btn btn-primary period-selector" period="1" target="1">1일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="3" target="1">3일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="7" target="1">7일</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="30" target="1">1개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="90" target="1">3개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="180" target="1">6개월</button> &nbsp;
                                            <button type="button" class="btn btn-warning period-selector" period="365" target="1">1년</button>
                                        </label>
                                    </td>
                                </tr>
                            </table>

                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>날짜별 아이템 구매 및 소비 현황 <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                    <table class="table table-bordered info-table top-margin" id="log_table">
                                        <thead>
                                        <tr>
                                            <th rowspan="3" class="first_row">날짜</th>
                                            <th rowspan="3" class="first_row">요일</th>
                                            <th colspan="3" class="first_row">멤버스</th>
                                            <th colspan="5" class="first_row">패키지</th>
                                            <th colspan="9" class="first_row">아이템</th>
                                        </tr>
                                        <tr>
                                            <th class="first_row">실버</th>
                                            <th class="first_row">골드</th>
                                            <th class="first_row">다이아</th>

                                            <th class="first_row">로얄</th>
                                            <th class="first_row">SuperWinGame</th>
                                            <th class="first_row">고급 로얄</th>
                                            <th class="first_row">고급 SuperWinGame</th>
                                            <th class="first_row">최고급 SuperWinGame</th>

                                            <th class="first_row">2000억 리필권</th>
                                            <th class="first_row">닉네임 변경권</th>
                                            <th class="first_row">강퇴권</th>
                                            <th colspan="2" class="first_row">플래티넘 복권</th>
                                            <th colspan="2" class="first_row">플러스 복권</th>
                                            <th colspan="2" class="first_row">실버 복권</th>
                                        </tr>
                                        <tr>
                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>

                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>

                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>
                                            <th class="second_row">BUY</th>

                                            <th class="second_row">1회</th>
                                            <th class="second_row">10회</th>
                                            <th class="second_row">1회</th>
                                            <th class="second_row">10회</th>
                                            <th class="second_row">1회</th>
                                            <th class="second_row">10회</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    </table>
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
        .dataTables_filter > label { display: none; }
        .paging_full_numbers { width: auto; }
        .custom-file {font-size: 16px;}

        .info-table { width: 100%; margin-bottom: 1rem; color: #212529; }
        .info-table th { text-align: center; vertical-align: middle !important; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
        .first_row { background-color: #fff; color: #000; }
        .second_row { background-color: #c0c0c0; color: #fff; }
    </style>

    <script>
        // dailyLogs
        function GetColumnPrefix(colIndex) {
            switch (colIndex) {
                case 0:
                    return "날짜";
                case 1:
                    return "요일";
                case 2:
                    return "멤버스 실버";
                case 3:
                    return "멤버스 골드";
                case 4:
                    return "멤버스 다이아";
                case 5:
                    return "로얄 패키지";
                case 6:
                    return "SuperWinGame 패키지";
                case 7:
                    return "고급로얄 패키지";
                case 8:
                    return "고급 SuperWinGame 패키지";
                case 9:
                    return "최고급 SuperWinGame 패키지";
                case 10:
                    return "2000억 리필권";
                case 11:
                    return "닉네임 변경권";
                case 12:
                    return "강퇴권";
                case 13:
                    return "플래티넘 복권 1회";
                case 14:
                    return "플래티넘 복권 10회";
                case 15:
                    return "플러스 복권 1회";
                case 16:
                    return "플러스 복권 10회";
                case 17:
                    return "실버 복권 1회";
                case 18:
                    return "실버 복권 10회";
                default:
                    return "";
            }
        }

        let buttonCommon = {
            exportOptions: {
                columns: ':visible',
                format: {
                    header: function(data, columnindex, trDOM, node) {
                        return GetColumnPrefix(columnindex);
                    }
                }
            }
        }

        $(document).ready(function () {
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
                    $.extend(true, {}, buttonCommon, {
                        extend: 'excelHtml5',
                        title: function () {
                            return '지표관리_아이템구매소비_현황(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                        },
                    }),
                ],
                "footerCallback":function(){
                    let api = this.api(), data;
                    let intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    let totalNumbers = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

                    for (let i = 2; i < 19; i++) {
                        api.column(i, {search:'applied'}).data().each(function(data,index){
                            totalNumbers[i] += intVal(data);
                        });

                        $(api.column(i).footer()).html( totalNumbers[i].toLocaleString() );
                    }
                },
            });

            $('#search_start_date1').daterangepicker({
                "singleDatePicker": true,
                "timePicker": false,
                "timePicker24Hour": false,
                "timePickerSeconds": false,
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ date("Y-m-d") }}').format('YYYY-MM-DD')
            }, function(start, end, label) {
            });
            $('#search_end_date1').daterangepicker({
                "singleDatePicker": true,
                "timePicker": false,
                "timePicker24Hour": false,
                "timePickerSeconds": false,
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ date("Y-m-d") }}').format('YYYY-MM-DD')
            }, function(start, end, label) {
            });

            // date search make period
            $('.period-selector').off('click').on('click', function () {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                fillSearchDate(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
            });

            // sales log
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
                    url: '/statistics/getDailyLogs',
                    data: {
                        target: 'items',
                        startDate: startDate,
                        endDate: endDate,
                        from: 'items'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

                        $('#search_period').text(startDate + ' ~ ' + endDate);

                        let dailyLogs = data.dailyLogs;
                        log_table.clear();
                        $('#log_table tbody').empty();
                        if (dailyLogs.length > 0) {
                            let no = 0;
                            $(dailyLogs).each(function(index, log) {
                                let tr = $('<tr>' +
                                    '<td>' + log.log_date + '</td>' +
                                    '<td>' + getWeekDay(log.log_date) + '</td>' +
                                    '<td>' + numberFormat(log.members_silver) + '</td>' +
                                    '<td>' + numberFormat(log.members_gold) + '</td>' +
                                    '<td>' + numberFormat(log.members_diamond) + '</td>' +
                                    '<td>' + numberFormat(log.package_royal) + '</td>' +
                                    '<td>' + numberFormat(log.package_allstar) + '</td>' +
                                    '<td>' + numberFormat(log.package_adv_royal) + '</td>' +
                                    '<td>' + numberFormat(log.package_adv_allstar) + '</td>' +
                                    '<td>' + numberFormat(log.package_fine_allstar) + '</td>' +
                                    '<td>' + numberFormat(log.refill_2000) + '</td>' +
                                    '<td>' + numberFormat(log.nickname_change) + '</td>' +
                                    '<td>' + numberFormat(log.force_out) + '</td>' +
                                    '<td>' + numberFormat(log.lottery_platinum_1) + '</td>' +
                                    '<td>' + numberFormat(log.lottery_platinum_10) + '</td>' +
                                    '<td>' + numberFormat(log.lottery_plus_1) + '</td>' +
                                    '<td>' + numberFormat(log.lottery_plus_10) + '</td>' +
                                    '<td>' + numberFormat(log.lottery_silver_1) + '</td>' +
                                    '<td>' + numberFormat(log.lottery_silver_10) + '</td>' +
                                    '</tr>');
                                log_table.row.add(tr);
                            });
                        }
                        log_table.draw();
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
@endsection
