@extends('layouts.mainlayout')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('statistics.ccu') }}">지표 관리</a></li>
            <li class="breadcrumb-item">재화 보유 현황</li>
        </ol>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="row x_title">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>재화 보유 현황</h2>
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

                        <div class="row">
                            <div class="col-md-12">
                                <br/><br/>
                                <h4>날짜별 재화 보유 현황 <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                <table class="table table-bordered info-table top-margin"  id="log_table">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="first_row">날짜</th>
                                        <th rowspan="2" class="first_row">요일</th>
                                        <th colspan="2" class="first_row">GEM</th>
                                        <th colspan="3" class="first_row">CHIP</th>
                                        <th colspan="3" class="first_row">GOLD</th>
                                        <th colspan="2" class="first_row">카드덱</th>
                                        <th colspan="3" class="first_row">멤버스</th>
                                        <th colspan="5" class="first_row">아이템</th>
                                    </tr>
                                    <tr>
                                        <th class="second_row">PAID</th>
                                        <th class="second_row">FREE</th>
                                        <th class="second_row">보유</th>
                                        <th class="second_row">금고</th>
                                        <th class="second_row">합계</th>
                                        <th class="second_row">보유</th>
                                        <th class="second_row">금고</th>
                                        <th class="second_row">합계</th>
                                        <th class="second_row">블루카드덱</th>
                                        <th class="second_row">골드카드덱</th>
                                        <th class="second_row">실버</th>
                                        <th class="second_row">골드</th>
                                        <th class="second_row">다이아</th>
                                        <th class="second_row">2000억<br/> 리필권</th>
                                        <th class="second_row">1000억<br/> 리필권</th>
                                        <th class="second_row">500억<br/> 리필권</th>
                                        <th class="second_row">닉네임<br/> 변경권</th>
                                        <th class="second_row">강퇴권</th>
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
    .second_row { background-color: #c0c0c0; color: #fff; }

    .log_table_wrapper {}
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
                return "보석 유료";
            case 3:
                return "보석 무료";
            case 4:
                return "칩 보유";
            case 5:
                return "칩 금고";
            case 6:
                return "칩 합계";
            case 7:
                return "골드 보유";
            case 8:
                return "골드 금고";
            case 9:
                return "골드 합계";
            case 10:
                return "블루카드덱";
            case 11:
                return "골드카드덱";
            case 12:
                return "멤버스 실버";
            case 13:
                return "멤버스 골드";
            case 14:
                return "멤버스 다이아";
            case 15:
                return "2000억 리필권";
            case 16:
                return "1000억 리필권";
            case 17:
                return "500억 리필권";
            case 18:
                return "닉네임 변경권";
            case 19:
                return "강퇴권";
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
                        return '지표관리_재화보유_현황(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                    },
                }),
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
                    // console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }

                    $("#search-error-bag").hide();

                    $('#search_period').text(startDate + ' ~ ' + endDate);

                    let dailyLogs = data.dailyLogs;
                    log_table.clear();
                    $('#log_table tbody').empty();
                    if (dailyLogs.length > 0) {
                        let no = 0;
                        $(dailyLogs).each(function(index, log) {
                            console.log('log', log);
                            let tr = $('<tr>' +
                                '<td>' + log.log_date + '</td>' +
                                '<td>' + getWeekDay(log.log_date) + '</td>' +
                                '<td>' + numberFormat(log.gem) + '</td>' +
                                '<td>' + numberFormat(log.gem_free) + '</td>' +
                                '<td>' + numberToKorean(log.chip) + '</td>' +
                                '<td>' + numberToKorean(log.chip_safe) + '</td>' +
                                '<td>' + numberToKorean(log.chip_total) + '</td>' +
                                '<td>' + numberToKorean(log.gold) + '</td>' +
                                '<td>' + numberToKorean(log.gold_safe) + '</td>' +
                                '<td>' + numberToKorean(log.gold_total) + '</td>' +
                                '<td>' + numberFormat(log.card_deck_blue) + '</td>' +
                                '<td>' + numberFormat(log.card_deck_gold) + '</td>' +
                                '<td>' + numberFormat(log.members_diamond) + '</td>' +
                                '<td>' + numberFormat(log.members_gold) + '</td>' +
                                '<td>' + numberFormat(log.members_silver) + '</td>' +
                                '<td>' + numberFormat(log.refill_2000) + '</td>' +
                                '<td>' + numberFormat(log.refill_1000) + '</td>' +
                                '<td>' + numberFormat(log.refill_500) + '</td>' +
                                '<td>' + numberFormat(log.nickname_change) + '</td>' +
                                '<td>' + numberFormat(log.force_out) + '</td>' +
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
