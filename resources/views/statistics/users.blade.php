@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('statistics.users') }}">지표관리</a></li>
                <li class="breadcrumb-item">회원 이용 통계</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>회원 이용 통계</h2>
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
                                    <h4>Chart <small>( 기간 : <span class="search_period"></span> )</small></h4>
                                    <canvas id="chartCanvas" style="width:100%; height:370px"></canvas>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <br/><br/>
                                    <h4>날짜별 재화 보유 현황 <small>( 기간 : <span class="search_period"></span> )</small></h4>
                                    <table class="table table-bordered info-table top-margin"  id="log_table">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="first_row">날짜</th>
                                            <th rowspan="2" class="first_row">신규 회원수</th>
                                            <th rowspan="2" class="first_row">탈퇴 회원수</th>
                                            <th rowspan="2" class="first_row">접속 유저수</th>
                                            <th rowspan="2" class="first_row">플레이 유저수</th>
                                            <th colspan="3" class="first_row">동시접속자수(CCU)</th>
                                        </tr>
                                        <tr>
                                            <th class="second_row">AVG</th>
                                            <th class="second_row">MAX</th>
                                            <th class="second_row">MIN</th>
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
    <!-- Chart.js -->
    <script src="/js/Chart.js.2.9.3.min.js"></script>
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
                    return "신규 회원수";
                case 2:
                    return "탈퇴 회원수";
                case 3:
                    return "접속 유저수";
                case 4:
                    return "플레이 유저수";
                case 5:
                    return "동시접속자수(AVG)";
                case 6:
                    return "동시접속자수(MAX)";
                case 7:
                    return "동시접속자수(Min)";
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


        let timeFormat = 'HH:mm';
        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        let chartCanvas = document.getElementById('chartCanvas').getContext('2d');
        let color = Chart.helpers.color;
        let chartConfig = {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                ]
            },
            options: {
                title: {
                    text: 'CCU'
                },
                scales: {
                    yAxes: [{
                        display: true,
                        position: 'left',
                        type: "linear",
                        scaleLabel: {
                            display: true,
                            labelString: 'Users',
                            beginAtZero: true,
                        },
                        id: "Users"
                    },{
                        scaleLabel: {
                            display: true,
                            labelString: 'CCU',
                            beginAtZero: true,
                        },
                        display: true,
                        type: "linear",
                        position:"right",
                        gridLines: {
                            display: false
                        },
                        id: "CCU"
                    }]
                }
            }
        };

        window.usersChart = new Chart(chartCanvas, chartConfig);

        function drawChart(logs, is_daily, minute) {
            let labels, ccuAvg, usersNew, usersActive, usersPlay;
            logs.reverse();
            labels = logs.map(data => data.log_date.substr(5,8));

            ccuAvg = logs.map(data => data.ccu_avg);
            usersNew = logs.map(data => data.users_new);
            usersActive = logs.map(data => data.users_active);
            usersPlay = logs.map(data => data.users_play);

            chartConfig.data.labels = labels;
            chartConfig.data.datasets = [
                {   // usersNew
                    type: 'line',
                    label: '신규유저수',
                    backgroundColor: color(window.chartColors.yellow).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.red,
                    data: usersNew,
                    fill: false,
                    spanGaps: true,
                    yAxisID: "Users",
                },
                {   // usersActive
                    type: 'line',
                    label: '접속유저수',
                    backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.blue,
                    data: usersActive,
                    spanGaps: true,
                    yAxisID: "Users",
                },
                {   // usersPlay
                    type: 'line',
                    label: '플레이유저수',
                    backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.green,
                    fill: false,
                    data: usersPlay,
                    spanGaps: true,
                    yAxisID: "Users",
                },
                {   // ccuAvg
                    type: 'line',
                    label: '동접자수(AVG)',
                    backgroundColor: color(window.chartColors.orange).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.orange,
                    fill: false,
                    data: ccuAvg,
                    spanGaps: true,
                    yAxisID: "CCU",
                }
            ];

            window.usersChart.update();
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
                            return '회원_이용_통계_' + $("#search_start_date1").val().replace(/-/gi, '') + '-' + $("#search_end_date1").val().replace(/-/gi, '');
                        },
                    }),
                ],
                // "footerCallback":function(){
                //     let api = this.api(), data;
                //     let intVal = function (i) {
                //         return typeof i === 'string' ?
                //             i.replace(/[\$,]/g, '') * 1 :
                //             typeof i === 'number' ?
                //                 i : 0;
                //     };
                //     let allCountTotal = 0;
                //     let allSumTotal = 0;
                //     let goldCountTotal = 0;
                //     let goldSumTotal = 0;
                //     let coinCountTotal = 0;
                //     let coinSumTotal = 0;
                //     //$( api.column( 0 ).footer() ).html('Total');
                //     api.column(2, {search:'applied'}).data().each(function(data,index){
                //         allCountTotal += intVal(data);
                //     });
                //     api.column(3, {search:'applied'}).data().each(function(data,index){
                //         allSumTotal += intVal(data);
                //     });
                //     api.column(4, {search:'applied'}).data().each(function(data,index){
                //         goldCountTotal += intVal(data);
                //     });
                //     api.column(5, {search:'applied'}).data().each(function(data,index){
                //         goldSumTotal += intVal(data);
                //     });
                //     api.column(6, {search:'applied'}).data().each(function(data,index){
                //         coinCountTotal += intVal(data);
                //     });
                //     api.column(7, {search:'applied'}).data().each(function(data,index){
                //         coinSumTotal += intVal(data);
                //     });
                //     $(api.column(2).footer()).html( (Math.floor(allCountTotal / api.column(2).data().count())).toLocaleString());
                //     $(api.column(3).footer()).html( (Math.floor(allSumTotal / api.column(3).data().count())).toLocaleString());
                //     $(api.column(4).footer()).html( (Math.floor(goldCountTotal / api.column(4).data().count())).toLocaleString());
                //     $(api.column(5).footer()).html( (Math.floor(goldSumTotal / api.column(5).data().count())).toLocaleString());
                //     $(api.column(6).footer()).html( (Math.floor(coinCountTotal / api.column(6).data().count())).toLocaleString());
                //     $(api.column(7).footer()).html( (Math.floor(coinSumTotal / api.column(7).data().count())).toLocaleString());
                // },
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
                        target: 'users',
                        startDate: startDate,
                        endDate: endDate,
                        from: 'users'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

                        $('.search_period').text(startDate + ' ~ ' + endDate);

                        let dailyLogs = data.dailyLogs;
                        log_table.clear();
                        $('#log_table tbody').empty();
                        if (dailyLogs.length > 0) {
                            let no = 0;
                            $(dailyLogs).each(function(index, log) {
                                let tr = $('<tr>' +
                                    '<td>' + log.log_date + '</td>' +
                                    '<td>' + numberFormat(log.users_new) + '</td>' +
                                    '<td>' + numberFormat(log.users_del) + '</td>' +
                                    '<td>' + numberFormat(log.users_active) + '</td>' +
                                    '<td>' + numberFormat(log.users_play) + '</td>' +
                                    '<td>' + numberFormat(log.ccu_avg) + '</td>' +
                                    '<td>' + numberFormat(log.ccu_max) + '</td>' +
                                    '<td>' + numberFormat(log.ccu_min) + '</td>' +
                                    '</tr>');
                                log_table.row.add(tr);
                            });
                        }
                        log_table.draw();

                        drawChart(dailyLogs, true, 0);
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
