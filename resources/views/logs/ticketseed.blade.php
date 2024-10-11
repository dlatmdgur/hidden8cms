@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('logs.money') }}">운영로그</a></li>
                <li class="breadcrumb-item">티켓이벤트 로그</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>티켓이벤트 로그</h2>
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
                                                        <input type="radio" class="flat" name="search_type" value="nickname"{{ $search['search_type'] == 'nickname' ? ' checked' : '' }}> 닉네임 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="userSeq"{{ $search['search_type'] == 'userSeq' ? ' checked' : '' }}> 회원번호 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="email"{{ $search['search_type'] == 'email' ? ' checked' : '' }}> Email
                                                    </label>
                                                    <label class="col-form-label label-align ml-2">
                                                        <select class="form-control" name="login_type">
                                                            <option value="1"{{ $search['login_type'] == '1' ? ' selected' : '' }}>구글</option>
                                                            <option value="3"{{ $search['login_type'] == '3' ? ' selected' : '' }}>플랫폼</option>
                                                            <option value="2"{{ $search['login_type'] == '2' ? ' selected' : '' }}>유니티</option>
                                                            <option value="0"{{ $search['login_type'] == '0' ? ' selected' : '' }}>게스트</option>
                                                        </select>
                                                    </label>
                                                </label>
                                                <div class="col-md-2">
                                                    <label class="col-form-label col-md-12">
                                                        <input type="text" class="form-control" id="search_input" name="keyword" placeholder="검색어" value="{{ $search['keyword'] }}">
                                                        <input type="text" style="display:none" />
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="item form-group row">
                                                <label class="col-md-1 col-sm-1 text-center">
                                                    <label class="col-form-label mt-2">조회기간 : </label>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-3 label-align">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" id="search_start_date" name="search_start_date" value="{{ $search['search_start_date'] }}">
                                                        <span class="input-group-addon" style="cursor:pointer;">
                                                            <i class="fa fa-calendar mt-1"></i>
                                                        </span>
                                                    </div>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-3 label-align">
                                                    <div class="input-group date" id="datepicker2">
                                                        <input type="text" class="form-control" id="search_end_date" name="search_end_date" value="{{ $search['search_end_date'] }}">
                                                        <span class="input-group-addon" style="cursor:pointer;">
                                                            <i class="fa fa-calendar mt-1"></i>
                                                        </span>
                                                    </div>
                                                </label>
                                                <div class="col-md-2">
                                                    <label class="col-form-label">
                                                        <button type="button" class="btn btn-primary period-selector" period="1" target="">1일</button> &nbsp;
                                                        <button type="button" class="btn btn-primary period-selector" period="3" target="">3일</button> &nbsp;
                                                        <button type="button" class="btn btn-primary period-selector" period="7" target="">7일</button>
                                                    </label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="col-form-label">
                                                        <select class="form-control custom-select" name="type">
                                                            <option value="use"{{ $search['type'] == 'use' ? ' selected' : '' }}>사용</option>
                                                            <option value="get"{{ $search['type'] == 'get' ? ' selected' : '' }}>획득</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="col-form-label">
                                                        <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                    </label>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="x_panel">
                                    <div class="x_content">
                                        <table class="table table-bordered info-table text-center">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>상품</th>
                                                <th>수량</th>
                                                <th>메모</th>
                                                <th>날짜</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($records) == 0 && $search['keyword'] != '')
                                                <tr>
                                                    <td colspan="5">데이터가 없습니다.</td>
                                                </tr>
                                            @endif
                                            @foreach($records as $key => $row)
                                                <tr>
                                                    <td>{{ $numberStart-- }}</td>
                                                    <td>{{ $row->item_name }}</td>
                                                    <td>{{ $row->item_ea }}</td>
                                                    <td>{{ $row->reason }}</td>
                                                    <td>{{ $row->log_date }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($records)
                                    <div class="x_content">
                                        <ul class="pagination justify-content-center">{{ $records->links() }}</ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });
            $('#search_start_date').daterangepicker({
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
            $('#search_end_date').daterangepicker({
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

            $('.period-selector').off('click').on('click', function() {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                fillSearchDate(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
            });

            $('#btn-search').on('click', function() {
                if($('#search_input').val() == '') {
                    alert('조회할 유저를 입력해 주세요.');
                    return;
                }
                $('#searchForm').submit();
            });

            //1개 삭제
            $('button[name=delOne]').on('click', function() {
                let params = {
                    target: 'one',
                    seq: $(this).attr('data-i')
                };

                ajaxDelete(params);
            });
            //모두 삭제
            $('button[name=delAll]').on('click', function() {
                let params = {
                    target: 'all',
                    seq: $(this).attr('data-i')
                };

                ajaxDelete(params);
            });
        });

        // Send
        function ajaxDelete(params) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/operation/ticketSeed/delete',
                data: params,
                dataType: 'json',
                success: function(data) {
                    if(data.error === true) return;
                    $('#search-error-bag').hide();

                    alert('삭제되었습니다.');
                    $('#btn-search').trigger('click');
                },
                error: function(data) {
                    if(data.status === 419) {
                        alert('세션이 만료되었습니다.');
                        location.href = '/login';
                    }
                    let errors = $.parseJSON(data.responseText);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $('#search-error-bag').show();
                }
            });
        }
    </script>
@endsection
