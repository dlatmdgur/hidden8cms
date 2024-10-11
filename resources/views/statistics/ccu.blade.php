@extends('layouts.mainlayout')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('statistics.ccu') }}">지표 관리</a></li>
            <li class="breadcrumb-item">CCU</li>
        </ol>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="row x_title">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>CCU</h2>
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
                                        <button type="button" class="btn btn-primary period-selector" period="7" target="1">7일</button>
                                        <button type="button" class="btn btn-success period-selector" period="30" target="1">1개월</button> &nbsp;
                                        <button type="button" class="btn btn-success period-selector" period="90" target="1">3개월</button> &nbsp;
                                        <button type="button" class="btn btn-success period-selector" period="180" target="1">6개월</button>
                                        <button type="button" class="btn btn-warning period-selector" period="365" target="1">1년</button>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th>데이터 간격</th>
                                <td>
                                    <label class="col-form-label col-md-12 col-sm-12 text-left">
                                        <!--<input type="radio" class="flat" name="search_minute" value="1" /> 1분 &nbsp;-->
                                        <input type="radio" class="flat" name="search_minute" value="5" /> 5분 &nbsp;
                                        <input type="radio" class="flat" name="search_minute" value="10" /> 10분 &nbsp;
                                        <input type="radio" class="flat" name="search_minute" value="15" /> 15분 &nbsp;
                                        <input type="radio" class="flat" name="search_minute" value="30" checked="true" required/> 30분 &nbsp;
                                        <input type="radio" class="flat" name="search_minute" value="60" /> 1시간 &nbsp;
                                        <input type="radio" class="flat" name="search_minute" value="1440" /> 1일
                                    </label>
                                </td>
                            </tr>
                        </table>

                        <div class="row">
                            <div class="col-md-12">
                                <br/><br/>
                                <h4>CCU <small>( 기간 : <span id="search_period"></span> )</small></h4>
                                <canvas id="chartCanvas" style="width:100%; height:370px"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .info-table { width: 100%; margin-bottom: 1rem; color: #212529; }
    .info-table th { text-align: center; vertical-align: middle !important; }
    .info-table td { text-align: center; vertical-align: middle; }
    .info-table td.number { text-align: right; }
</style>
<!-- Chart.js -->
<script src="/js/Chart.js.2.9.3.min.js"></script>
<script>
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
        }
    };

    window.ccuChart = new Chart(chartCanvas, chartConfig);

    function drawChart(logs, is_daily, minute) {
        let labels, minData, maxData, avgData;
        if (is_daily) {
            labels = logs.map(data => data.log_date.substr(11));
        } else {
            if (minute >= 1440) {
                labels = logs.map(data => data.log_date.substr(5, 6));
            } else {
                labels = logs.map(data => data.log_date.substr(5, 11));
            }
        }

        minData = logs.map(data => data.min);
        maxData = logs.map(data => data.max);
        avgData = logs.map(data => data.avg);

        chartConfig.data.labels = labels;
        chartConfig.data.datasets = [
            {
                type: 'line',
                label: 'min',
                backgroundColor: color(window.chartColors.yellow).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                data: minData,
                fill: false,
                spanGaps: true
            },
            {   // max
                type: 'line',
                label: 'Max',
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                data: maxData,
                spanGaps: true
            },
            {
                type: 'line',
                label: 'Avg',
                backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                borderColor: window.chartColors.green,
                fill: false,
                data: avgData,
                spanGaps: true
            }
        ];

        window.ccuChart.update();
    }

    $(document).ready(function () {
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
            let minute = $('input[name=search_minute]:checked').val();

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
                url: '/statistics/getCcuLogs',
                data: {
                    target: 'ccu',
                    startDate: startDate,
                    endDate: endDate,
                    minute: minute,
                    from: 'ccu'
                },
                dataType: 'json',
                success: function(data) {
                    if(data.error === true) return;

                    $("#search-error-bag").hide();

                    $('#search_period').text(startDate + ' ~ ' + endDate);

                    let ccuLogs = data.ccuLogs;
                    let is_daily = false;
                    if (startDate === endDate) {
                        is_daily = true;
                    }

                    drawChart(ccuLogs, is_daily, minute);
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
@endsection
