@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.info') }}">게임조회</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.posts') }}">가방 조회</a></li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>가방 조회</h2>
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
                                        </form>

                                        <table class="table-bordered info-table" style="width: 100%">
                                            <tr>
                                                <th width="15%">사용 여부</th>
                                                <td style="text-align: left;">
                                                    <div class="col-form-label col-md-6 col-sm-6">
                                                        <input type="radio" class="flat" name="post_type" value="all" checked="" required />  전체 &nbsp;
                                                        <input type="radio" class="flat" name="post_type" value="used" /> 사용
                                                        <input type="radio" class="flat" name="post_type" value="unused" /> 미사용
                                                    </div>
                                                </td>
                                                <th width="15%">아이템 종류</th>
                                                <td style="text-align: left;">
                                                    <div class="col-form-label col-md-6 col-sm-6">
                                                        <select class="form-control" name="item_type" id="item_type">
                                                            <option value="all">전체</option>
                                                            <option value="2017">유료보석 획득</option>
                                                            <option value="2025">무료보석 획득</option>
                                                            <option value="2016">칩 획득</option>
                                                            <option value="2018">골드 획득</option>
                                                            <option value="2019">골드 티켓 획득</option>
                                                            <option value="2012">룰렛 티켓 획득</option>
                                                            <option value="2003">아바타 카드 획득</option>
                                                            <option value="2014">스타트팩 획득</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>날짜 선택</th>
                                                <td colspan="3">
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="15%">가방 미확인 개수</th>
                                                <td  colspan="3" style="height:55px; padding-left:20px; text-align: left; line-height: 35px;" id="unreadCount"> - </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>가방 로그<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table"  id="log_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>받은 날짜</th>
                                                <th>만료 일시</th>
                                                <th>상품설명</th>
                                                <th>보낸이</th>
                                                <th>상품수량</th>
                                                <th>획득경로</th>
                                                <th>사용 일시</th>
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
        .btn-group.dt-buttons { position: absolute; top: -60px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter { display: none; }
        .paging_full_numbers { width: auto; }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script>
        $(document).ready(function () {
            let log_table = $('#log_table').DataTable({
                aaSorting: [],
                bSort: false,
                pageLength: 10,
                pagingType: 'full_numbers',
                language: {
                    "emptyTable": "데이터가 없습니다."
                },
                dom: 'Brtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: function() {
                            return '가방내역_' + $('#nickname').val() + '_' + $("#search_start_date").val().replace(/-/gi, '') +'-'+$("#search_end_date").val().replace(/-/gi, '');
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
                $('#search-errors').html('')

                let keyword = $('#search_input').val();
                let platform = $('select[name=login_type]').val();
                let searchType = $(':input:radio[name=search_type]:checked').val();
                let postType = $(':input:radio[name=post_type]:checked').val();
                let itemType = $('#item_type option:selected').val();
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

                if (searchType.length === 0) {
                    alert('사용 여부를 선택하세요.');
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/game/presents',
                    data: {
                        type: searchType,
                        keyword: keyword,
                        platform: platform,
                        searchType: postType,
                        startDate: startDate,
                        itemType: itemType,
                        endDate: endDate,
                        from: 'post'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

                        $('#user_seq').val(data.userInfo.user_seq);
                        $('#nickname').val(data.userInfo.nickname);

                        let unreadCount = data.unreadCount;
                        let presents = data.presents;

                        $('#unreadCount').text(unreadCount + ' 개');

                        log_table.clear();
                        $('#log_table tbody').empty();
                        if (presents.length > 0) {
                            let no = 0;
                            $(presents).each(function(index, log) {
                                let use_date = '';
                                let end_date;
                                if(log.unlimited === '1') {
                                    end_date = '-';
                                    use_date = (log.is_read === '0') ? '미사용' : log.update_date;
                                } else {
                                    end_date = (log.is_read === '0') ? membersEndDate(log.update_date, log.period_time) : log.update_date;
                                    if (log.is_read === '0') {
                                        if(checkMembers(end_date, 0)) {
                                            use_date = '미사용';
                                        } else {
                                            use_date = '만료됨';
                                        }
                                    } else {
                                        use_date = log.update_date;
                                    }
                                }
                                let tr = $('<tr>' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.update_date + '</td>' +
                                    '<td>' + end_date + '</td>' +
                                    '<td>' + log.item_name + '</td>' +
                                    '<td>시스템</td>' +
                                    '<td class="number">' + numberToKorean(log.item_ea) + '</td>' +
                                    '<td>' + log.reason + '</td>' +
                                    '<td>' + use_date + '</td>' +
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
