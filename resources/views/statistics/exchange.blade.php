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
                                <h2>교환소 이용현황</h2>
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
                                <th rowspan="1" style="min-width: 60px;">검색</th>
                                <td style="border-bottom: 0; ">
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
                                <th rowspan="1" style="min-width: 60px;">Excel</th>
                                <td><label class="col-form-label col-md-1 col-sm-5 label-align">
                                        <button type="button" id="btn-excel" class="btn btn-secondary">Excel</button>
                                    </label>
                                </td>
                            </tr>
{{--                            <tr>--}}
{{--                                <td style="padding: 10px 0; border-top: 0; text-align: left; ">--}}
{{--                                    <label class="col-form-label col-md-3 col-sm-3 label-align">--}}
{{--                                        <input type="radio" class="flat" name="search_type" value="nickname" checked="" required /> 닉네임 &nbsp;--}}
{{--                                        <input type="radio" class="flat" name="search_type" value="userSeq" /> 회원번호 &nbsp;--}}
{{--                                        <input type="radio" class="flat" name="search_type" value="email" /> Email--}}
{{--                                    </label>--}}
{{--                                    <div class="col-md-3 col-sm-3">--}}
{{--                                        <input type="text" class="form-control" id="search_input" name="keyword" placeholder="검색어">--}}
{{--                                        <input type="text" style="display:none">--}}
{{--                                        <input type="hidden" id="user_seq">--}}
{{--                                        <input type="hidden" id="nickname">--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
                        </table>

                        <div class="row">
                            <div class="col-md-12">
                                <br/><br/>
                                <h4>최근 3일 최고 교환 유저</h4>
                                <table class="table table-bordered info-table top-margin"  id="top_table">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="first_row">날짜</th>
                                        <th rowspan="2" class="first_row">요일</th>
                                        <th rowspan="2" class="first_row">유저</th>
                                        <th colspan="2" class="first_row">코인 <i class="fa fa-arrow-right"></i> 골드</th>
                                    </tr>
                                    <tr>
                                        <th class="second_row">총 교환 건수</th>
                                        <th class="second_row">총 교환 금액</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <br/><br/>
                                <h4>2백만 이상 교환건 <small>( 기간 : <span class="search_period"></span> )</small></h4>
                                <table class="table table-bordered info-table top-margin"  id="over_table">
                                    <thead>
                                    <tr>
                                        <th class="first_row">날짜</th>
                                        <th class="first_row">요일</th>
                                        <th class="first_row">닉네임</th>
                                        <th class="first_row">구분</th>
                                        <th class="first_row">골드 <i class="fa fa-arrow-right"></i> 코인</th>
                                        <th class="first_row">코인 <i class="fa fa-arrow-right"></i> 골드</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <br/><br/>
                                <h4>교환소 이용현황 <small>( 기간 : <span class="search_period"></span> )</small></h4>
                                <table class="table table-bordered info-table top-margin"  id="log_table">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="first_row">날짜</th>
                                        <th rowspan="2" class="first_row">요일</th>
                                        <th colspan="2" class="first_row">합계</th>
                                        <th colspan="2" class="first_row">골드 <i class="fa fa-arrow-right"></i> 코인</th>
                                        <th colspan="2" class="first_row">코인 <i class="fa fa-arrow-right"></i> 골드</th>
                                    </tr>
                                    <tr>
                                        <th class="second_row">총 교환 건수</th>
                                        <th class="second_row">총 교환 금액</th>
                                        <th class="second_row">교환건수</th>
                                        <th class="second_row">교환골드</th>
                                        <th class="second_row">교환건수</th>
                                        <th class="second_row">교환코인</th>
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
    // dailyLogs
    function GetColumnPrefix(colIndex) {
        switch (colIndex) {
            case 0:
                return "날짜";
            case 1:
                return "요일";
            case 2:
                return "총 교환 건수";
            case 3:
                return "총 교환 금액";
            case 4:
                return "[골드 -> 코인]교환 건수";
            case 5:
                return "[골드 -> 코인]교환 금액";
            case 6:
                return "[코인 -> 골드]교환 건수";
            case 7:
                return "[코인 -> 골드]교환 금액";
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
                        return '교환소_이용현황(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
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
                let allCountTotal = 0;
                let allSumTotal = 0;
                let goldCountTotal = 0;
                let goldSumTotal = 0;
                let coinCountTotal = 0;
                let coinSumTotal = 0;
                //$( api.column( 0 ).footer() ).html('Total');
                api.column(2, {search:'applied'}).data().each(function(data,index){
                    allCountTotal += intVal(data);
                });
                api.column(3, {search:'applied'}).data().each(function(data,index){
                    allSumTotal += intVal(data);
                });
                api.column(4, {search:'applied'}).data().each(function(data,index){
                    goldCountTotal += intVal(data);
                });
                api.column(5, {search:'applied'}).data().each(function(data,index){
                    goldSumTotal += intVal(data);
                });
                api.column(6, {search:'applied'}).data().each(function(data,index){
                    coinCountTotal += intVal(data);
                });
                api.column(7, {search:'applied'}).data().each(function(data,index){
                    coinSumTotal += intVal(data);
                });
                $(api.column(2).footer()).html(allCountTotal.toLocaleString());
                $(api.column(3).footer()).html(allSumTotal.toLocaleString());
                $(api.column(4).footer()).html(goldCountTotal.toLocaleString());
                $(api.column(5).footer()).html(goldSumTotal.toLocaleString());
                $(api.column(6).footer()).html(coinCountTotal.toLocaleString());
                $(api.column(7).footer()).html(coinSumTotal.toLocaleString());
            },

        });

        let top_table = $('#top_table').DataTable({
            aaSorting: [],
            bSort: false,
            pageLength: 10,
            pagingType: 'full_numbers',
            language: {
                "emptyTable": "데이터가 없습니다."
            },
            //dom: 'Bfrtip',
            // buttons: [
            //     $.extend(true, {}, buttonCommon, {
            //         extend: 'excelHtml5',
            //         title: function () {
            //             return '교환소_최근3일최고교환유저_' + Date.today();
            //         },
            //     }),
            // ],
        });

        let over_table = $('#over_table').DataTable({
            aaSorting: [],
            bSort: false,
            pageLength: 10,
            pagingType: 'full_numbers',
            language: {
                "emptyTable": "데이터가 없습니다."
            },
            dom: 'Bfrtip',
            buttons: [
                // $.extend(true, {}, buttonCommon, {
                //     extend: 'excelHtml5',
                //     title: function () {
                //         return '교환소_이용현황(2백만이상)_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                //     },
                // }),
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
                url: '/statistics/getExchangeLogs',
                data: {
                    target: 'exchange',
                    startDate: startDate,
                    endDate: endDate,
                    // type: $(':input:radio[name=search_type]:checked').val(),
                    // keyword: $(':input:text[name=keyword]').val(),
                    from: 'exchange'
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }

                    $("#search-error-bag").hide();

                    $('.search_period').text(startDate + ' ~ ' + endDate);

                    let dailyLogs = data.dailyLogs;
                    log_table.clear();
                    $('#log_table tbody').empty();
                    if (dailyLogs.length > 0) {
                        let no = 0;
                        $(dailyLogs).each(function(index, log) {
                            //console.log('log', log);
                            let tr = $('<tr>' +
                                '<td>' + log.log_date + '</td>' +
                                '<td>' + getWeekDay(log.log_date) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.coin_count) + parseInt(log.gold_count)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.coin_amount) + parseInt(log.gold_amount)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.coin_count)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.coin_amount)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.gold_count)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.gold_amount)) + '</td>' +
                                '</tr>');
                            log_table.row.add(tr);
                        });
                    }
                    log_table.draw();

                    // 최상위 교환 유저
                    let todayTopLogs = data.topGoldLogs;
                    console.log('todayTopLogs', todayTopLogs);
                    top_table.clear();
                    $('#top_table tbody').empty();
                    if (todayTopLogs.length > 0) {
                        let no = 0;
                        $(todayTopLogs).each(function(index, log) {
                            //console.log('log', log);
                            let tr = $('<tr>' +
                                '<td>' + log.log_date + '</td>' +
                                '<td>' + getWeekDay(log.log_date) + '</td>' +
                                '<td><a href="/statistics/exchangeUser?userSeq='+log.user_seq+'">' + log.nickname + '</a></td>' +
                                '<td>' + numberFormat(parseInt(log.total_count)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.total_amount)) + '</td>' +
                                '</tr>');
                            top_table.row.add(tr);
                        });
                    }
                    top_table.draw();


                    // 2백만 이상 교환 건
                    let overLimitLogs = data.overLimitLogs;
                    console.log('overLimitLogs', overLimitLogs);
                    over_table.clear();
                    $('#over_table tbody').empty();
                    if (overLimitLogs.length > 0) {
                        let no = 0;
                        $(overLimitLogs).each(function(index, log) {
                            //console.log('log', log);
                            let tr = $('<tr>' +
                                '<td>' + log.log_date + '</td>' +
                                '<td>' + getWeekDay(log.log_date) + '</td>' +
                                '<td><a href="/statistics/exchangeUser?userSeq='+log.user_seq+'">' + log.nickname + '</a></td>' +
                                '<td>' + log.type + '</td>' +
                                '<td>' + numberFormat(parseInt(log.coin_amount)) + '</td>' +
                                '<td>' + numberFormat(parseInt(log.gold_amount)) + '</td>' +
                                '</tr>');
                            over_table.row.add(tr);
                        });
                    }
                    over_table.draw();
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

        // excel log
        $('#btn-excel').off('click').on('click', function() {
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

            postForm('/statistics/excelExchangeLogs', {_token: $('meta[name="csrf-token"]').attr('content'), target: 'exchange', startDate: startDate, endDate: endDate, from: 'exchange'});

            // $.ajax({
            //     type: 'POST',
            //     url: '/statistics/excelExchangeLogs',
            //     data: {
            //         target: 'exchange',
            //         startDate: startDate,
            //         endDate: endDate,
            //         from: 'exchange'
            //     },
            //     dataType: 'json',
            //     success: function(data) {
            //         var blob = new Blob([data], { type: 'data:application/vnd.ms-excel' });
            //     },
            //     error: function(data) {
            //
            //     }
            // });
        });

    });

    function postForm(path, params, method) {
        method = method || 'post';

        var form = document.createElement('form');
        form.setAttribute('method', method);
        form.setAttribute('action', path);

        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                var hiddenField = document.createElement('input');
                hiddenField.setAttribute('type', 'hidden');
                hiddenField.setAttribute('name', key);
                hiddenField.setAttribute('value', params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
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
