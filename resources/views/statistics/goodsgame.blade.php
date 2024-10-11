@extends('layouts.mainlayout')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('statistics.ccu') }}">지표 관리</a></li>
            <li class="breadcrumb-item">재화 현황</li>
        </ol>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="row x_title">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>재화 현황</h2>
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
                    </div>

                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-12">
                                <br/><br/>
                                <h4>날짜별 유저 보유 재화 <small>( 기간 : <span class="search_period"></span> ) : [해당일 0시 기준 서버 재화]</small></h4>
                                <table class="table table-bordered info-table top-margin"  id="log_money_table">
                                    <thead>
                                    <tr>
                                        <th class="first_row">날짜</th>
                                        <th class="first_row">요일</th>
                                        <th class="first_row" style="width:17%">유료보석</th>
                                        <th class="first_row" style="width:17%">무료보석</th>
                                        <th class="first_row" style="width:17%">칩</th>
                                        <th class="first_row" style="width:17%">골드</th>
                                        <th class="first_row" style="width:17%">적립통장 골드</th>
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
                                <h4>날짜별 재화 현황 <small>( 기간 : <span class="search_period"></span> )</small></h4>
                                <table class="table table-bordered info-table top-margin"  id="log_daily_table">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="first_row">날짜</th>
                                        <th rowspan="2" class="first_row">요일</th>
                                        <th colspan="3" class="first_row">칩</th>
                                        <th colspan="3" class="first_row">골드</th>
                                    </tr>
                                    <tr>
                                        <th class="second_row">유저 획득</th>
                                        <th class="second_row">유저 손실</th>
                                        <th class="second_row">게임 수입</th>
                                        <th class="second_row">유저 획득</th>
                                        <th class="second_row">유저 손실</th>
                                        <th class="second_row">게임 수입</th>
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
                                <h4>날짜별 재화 현황(게임, 채널) <small>( 기간 : <span class="search_period"></span> )</small></h4>
                                <table class="table table-bordered info-table top-margin"  id="log_games_table">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="first_row">날짜</th>
                                        <th rowspan="2" class="first_row">요일</th>
                                        <th rowspan="2" class="first_row">게임</th>
                                        <th rowspan="2" class="first_row">채널타입</th>
                                        <th colspan="3" class="first_row">칩</th>
                                        <th colspan="3" class="first_row">골드</th>
                                    </tr>
                                    <tr>
                                        <th class="second_row">유저 획득</th>
                                        <th class="second_row">유저 손실</th>
                                        <th class="second_row">게임 수입</th>
                                        <th class="second_row">유저 획득</th>
                                        <th class="second_row">유저 손실</th>
                                        <th class="second_row">게임 수입</th>
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
    const gameNames = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');
    const gameSubTypes = $.parseJSON('{!! json_encode(Helper::gameSubType()) !!}');

    // dailyLogs
    function GetUserGoodsColumnPrefix(colIndex) {
        switch (colIndex) {
            case 0:
                return "날짜";
            case 1:
                return "요일";
            case 2:
                return "유료 보석";
            case 3:
                return "무료 보석";
            case 4:
                return "칩";
            case 5:
                return "골드";
            case 6:
                return "적립통장 골드";
            default:
                return "";
        }
    }

    function GetTotalGoodsColumnPrefix(colIndex) {
        switch (colIndex) {
            case 0:
                return "날짜";
            case 1:
                return "요일";
            case 2:
                return "칩 유저 획득";
            case 3:
                return "칩 유저 손실";
            case 4:
                return "칩 게임수입";
            case 5:
                return "골드 유저 획득";
            case 6:
                return "골드 유저 손실";
            case 7:
                return "골드 게임 수입";
            default:
                return "";
        }
    }

    function GetGameGoodsColumnPrefix(colIndex) {
        switch (colIndex) {
            case 0:
                return "날짜";
            case 1:
                return "요일";
            case 2:
                return "게임";
            case 3:
                return "채널타입";
            case 4:
                return "칩 유저 획득";
            case 5:
                return "칩 유저 손실";
            case 6:
                return "칩 게임수입";
            case 7:
                return "골드 유저 획득";
            case 8:
                return "골드 유저 손실";
            case 9:
                return "골드 게임 수입";
            default:
                return "";
        }
    }

    let buttonUserGoods = {
        exportOptions: {
            columns: ':visible',
            format: {
                header: function(data, columnindex, trDOM, node) {
                    return GetUserGoodsColumnPrefix(columnindex);
                }
            }
        }
    }

    let buttonTotalGoods = {
        exportOptions: {
            columns: ':visible',
            format: {
                header: function(data, columnindex, trDOM, node) {
                    return GetTotalGoodsColumnPrefix(columnindex);
                }
            }
        }
    }

    let buttonGameGoods = {
        exportOptions: {
            columns: ':visible',
            format: {
                header: function(data, columnindex, trDOM, node) {
                    return GetGameGoodsColumnPrefix(columnindex);
                }
            }
        }
    }

    $(document).ready(function () {
        let log_money_table = $('#log_money_table').DataTable({
            aaSorting: [],
            bSort: false,
            pageLength: 10,
            pagingType: 'full_numbers',
            language: {
                "emptyTable": "데이터가 없습니다."
            },
            dom: 'Bfrtip',
            buttons: [
                $.extend(true, {}, buttonUserGoods, {
                    extend: 'excelHtml5',
                    title: function () {
                        return '지표관리_유저보유_재화_현황(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                    },
                }),
            ],
        });

        let log_daily_table = $('#log_daily_table').DataTable({
            aaSorting: [],
            bSort: false,
            pageLength: 10,
            pagingType: 'full_numbers',
            language: {
                "emptyTable": "데이터가 없습니다."
            },
            dom: 'Bfrtip',
            buttons: [
                $.extend(true, {}, buttonTotalGoods, {
                    extend: 'excelHtml5',
                    title: function () {
                        return '지표관리_전체_재화_현황(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                    },
                }),
            ],
        });

        let log_games_table = $('#log_games_table').DataTable({
            aaSorting: [],
            bSort: false,
            pageLength: 16,
            pagingType: 'full_numbers',
            language: {
                "emptyTable": "데이터가 없습니다."
            },
            dom: 'Bfrtip',
            buttons: [
                $.extend(true, {}, buttonGameGoods, {
                    extend: 'excelHtml5',
                    title: function () {
                        return '지표관리_게임채널별_재화_현황(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                    },
                }),
            ],
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
                    target: 'goods',
                    startDate: startDate,
                    endDate: endDate,
                    from: 'goods'
                },
                dataType: 'json',
                success: function(data) {
                    if(data.error === true) return;

                    $("#search-error-bag").hide();

                    $('.search_period').text(startDate + ' ~ ' + endDate);

                    let dailyLogs = data.dailyLogs;
                    let dailyMoneyLogs = dailyLogs.money;
                    let dailyTotalLogs = dailyLogs.total;
                    let dailyGamesLogs = dailyLogs.games;

                    log_money_table.clear();
                    $('#log_money_table tbody').empty();
                    if (dailyMoneyLogs.length > 0) {
                        let no = 0;
                        $(dailyMoneyLogs).each(function(index, log) {
                            let log_date = log.date.substring(0, 10);
                            let tr = $('<tr>' +
                                '<td>' + log_date + '</td>' +
                                '<td>' + getWeekDay(log_date) + '</td>' +
                                '<td>' + numberToKorean(log.gem) + '</td>' +
                                '<td>' + numberToKorean(log.gem_evnet) + '</td>' +
                                '<td>' + numberToKorean(log.chip) + '</td>' +
                                '<td>' + numberToKorean(log.gold) + '</td>' +
                                '<td>' + numberToKorean(log.rakeback_gold) + '</td>' +
                                '</tr>');
                            log_money_table.row.add(tr);
                        });
                    }
                    log_money_table.draw();

                    log_daily_table.clear();
                    $('#log_daily_table tbody').empty();
                    if (dailyTotalLogs.length > 0) {
                        let no = 0;
                        $(dailyTotalLogs).each(function(index, log) {
                            let log_date = log.date.substring(0, 10);
                            let tr = $('<tr>' +
                                '<td>' + log_date + '</td>' +
                                '<td>' + getWeekDay(log_date) + '</td>' +
                                '<td>' + numberToKorean(log.chip_sum_inc) + '</td>' +
                                '<td>' + numberToKorean(log.chip_sum_dec) + '</td>' +
                                '<td>' + numberToKorean(log.chip_income) + '</td>' +
                                '<td>' + numberToKorean(log.gold_sum_inc) + '</td>' +
                                '<td>' + numberToKorean(log.gold_sum_dec) + '</td>' +
                                '<td>' + numberToKorean(log.gold_income) + '</td>' +
                                '</tr>');
                            log_daily_table.row.add(tr);
                        });
                    }
                    log_daily_table.draw();

                    log_games_table.clear();
                    $('#log_games_table tbody').empty();
                    if (dailyGamesLogs.length > 0) {
                        let no = 0;
                        $(dailyGamesLogs).each(function(index, log) {
                            //console.log('log', log);
                            let chip_sum_inc = 0;
                            let chip_sum_dec = 0;
                            let chip_income = 0;
                            let gold_sum_inc = 0;
                            let gold_sum_dec = 0;
                            let gold_income = 0;

                            if (parseInt(log.subtype) < 2) {
                                chip_sum_inc = log.sum_inc;
                                chip_sum_dec = log.sum_dec;
                                chip_income = log.income;
                            } else {
                                gold_sum_inc = log.sum_inc;
                                gold_sum_dec = log.sum_dec;
                                gold_income = log.income;
                            }
                            let log_date = log.date.substring(0, 10);
                            let tr = $('<tr>' +
                                '<td>' + log_date + '</td>' +
                                '<td>' + getWeekDay(log_date) + '</td>' +
                                '<td>' + gameNames[log.gametype].name + '</td>' +
                                '<td>' + gameSubTypes[log.subtype].name + '</td>' +
                                '<td>' + numberToKorean(chip_sum_inc) + '</td>' +
                                '<td>' + numberToKorean(chip_sum_dec) + '</td>' +
                                '<td>' + numberToKorean(chip_income) + '</td>' +
                                '<td>' + numberToKorean(gold_sum_inc) + '</td>' +
                                '<td>' + numberToKorean(gold_sum_dec) + '</td>' +
                                '<td>' + numberToKorean(gold_income) + '</td>' +
                                '</tr>');
                            log_games_table.row.add(tr);
                        });
                    }
                    log_games_table.draw();
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
