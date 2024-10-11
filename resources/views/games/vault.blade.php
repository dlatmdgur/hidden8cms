@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.info') }}">게임조회</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.vault') }}">금고 조회</a></li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>금고 조회</h2>
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
                                    <div class="row col-md-12 col-sm-12">
                                        <label class="col-form-label col-md-1 col-sm-1 label-align">
                                            <span style="font-weight: bold; text-align: right;">금고 선택 : </span>
                                        </label>
                                        <label class="col-form-label col-md-4 col-sm-4">
                                            <input type="radio" class="flat" name="log_type" value="chip" checked="" required /> 칩 &nbsp;
                                            <input type="radio" class="flat" name="log_type" value="gold" /> 골드
                                            <input type="radio" class="flat" name="log_type" value="all" /> 전체
                                        </label>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>금고정보 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <tr>
                                                <th width="250">금고 타입</th>
                                                <td colspan="4" id="members_grade"></td>
                                            </tr>
                                            <tr>
                                                <th>보유 금고 머니</th>
                                                <td style="width:120px; font-weight: bold;">칩</td>
                                                <td id="safe_chip" style="width:35%;">0</td>
                                                <td style="width:120px; font-weight: bold;">골드</td>
                                                <td id="safe_gold" style="width:35%;">0</td>
                                            </tr>
                                            <tr>
                                                <th>사용 기간</th>
                                                <td colspan="4" id="safe_period"> - </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>금고 입출금 내역 조회<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table"  id="log_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>거래 일시</th>
                                                <th>구분</th>
                                                <th>변경 전 잔액</th>
                                                <th>입금액</th>
                                                <th>출금액</th>
                                                <th>변경 후 잔액</th>
                                                <th>변경 전 금고잔액</th>
                                                <th>변경 후 금고잔액</th>
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
                    {
                        extend: 'excelHtml5',
                        title: function() {
                            return '금고입출금내역_' + $('#search_input').val() + '_' + $("#search_start_date").val().replace(/-/gi, '') +'-'+$("#search_end_date").val().replace(/-/gi, '');
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
                let searchType = $(':input:radio[name=log_type]:checked').val();
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
                    url: '/game/vaultLogs',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val(),
                        platform: $('select[name=login_type]').val(),
                        keyword: $(':input:text[name=keyword]').val(),
                        startDate: startDate,
                        endDate: endDate,
                        searchType: searchType,
                        from: 'game'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

                        let userInfo = data.userInfo;
                        $('#user_seq').val(userInfo.user_seq);
                        $('#nickname').val(userInfo.nickname);

                        let safe_start_date = '';
                        let safe_end_date = '';
                        if ( parseInt(userInfo.members_type) > 0 ) {
                            safe_start_date = userInfo.members_start_date;
                            safe_end_date = membersEndDate(userInfo.members_start_date, userInfo.members_period);
                        } else {
                            safe_start_date = userInfo.safe_start_date;
                            safe_end_date = membersEndDate(userInfo.safe_start_date, userInfo.safe_period);
                        }

                        $('#members_grade').text(membersInfo[userInfo.members_type].name);
                        $('#safe_chip').text(numberToKorean(userInfo.safe_chip));
                        $('#safe_gold').text(numberToKorean(userInfo.safe_gold));
                        $('#safe_period').text(safe_start_date + ' ~ ' + safe_end_date);

                        // logs
                        let safeLog = data.safeLogs;
                        log_table.clear();
                        $('#log_table tbody').empty();
                        if (safeLog.length > 0) {
                            let no = 0;
                            $(safeLog).each(function(index, log) {
                                let change_gem = parseInt(log.change_gem) + parseInt(log.change_event_gem)
                                let change_chip = 0;
                                let change_gold = 0;
                                if (log.safe_type === "1") {
                                    change_chip = log.amount;
                                } else {
                                    change_gold = log.amount;
                                }
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.log_date + '</td>' +
                                    '<td>' + log.money_type + '</td>' +
                                    '<td>' + numberToKorean(log.before_money) + '</td>' +
                                    '<td>' + numberToKorean(change_chip) + '</td>' +
                                    '<td>' + numberToKorean(change_gold) + '</td>' +
                                    '<td>' + numberToKorean(log.after_money) + '</td>' +
                                    '<td>' + numberToKorean(log.before_safe_money) + '</td>' +
                                    '<td>' + numberToKorean(log.after_safe_money) + '</td>' +
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
    <script src="/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="/vendors/pdfmake/build/vfs_fonts.js"></script>
@endsection
