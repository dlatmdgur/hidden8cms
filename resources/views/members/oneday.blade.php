@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.list') }}">사용자조회</a></li>
                <li class="breadcrumb-item">하루 가입 유저</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>하루 가입 유저</h2>
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
                                                <label class="col-md-4 col-sm-4 ">
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="nickname" checked="" required /> 닉네임 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" id="search_user_seq" value="userid" /> 아이디 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="ip" /> IP
                                                    </label>
                                                    <label class="col-form-label label-align ml-2">
                                                        <div class="input-group date">
                                                            <input type="text" name="reg_date" class="form-control">
                                                            <span class="input-group-addon" style="cursor:pointer;">
                                                                <i class="fa fa-calendar mt-1"></i>
                                                            </span>
                                                        </div>
                                                    </label>
                                                </label>
                                                <div class="col-md-3">
                                                    <label class="col-form-label col-md-12">
                                                        <input type="text" class="form-control" id="search_input" name="keyword" placeholder="검색어" />
                                                        <input type="text" style="display:none" />
                                                    </label>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label col-md-12">
                                                        <select name="input_referrer" class="form-control custom-select">
                                                            <option value="">전체</option>
                                                            <option value="Y">추천인입력</option>
                                                            <option value="N">추천인미입력</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="col-form-label">
                                                        <input type="hidden" name="page" value="1">
                                                        <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                    </label>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>유저리스트</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <thead>
                                            <tr style="background-color: #EEEEEE">
                                                <th>회원번호</th>
                                                <th>로그인타입</th>
                                                <th>닉네임</th>
                                                <th>아이디/이메일</th>
                                                <th>가입일자</th>
                                                <th>최종로그인</th>
                                                <th>접속 IP</th>
                                                <th>인증여부</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="x_content">
                                        <nav aria-label="Page navigation">
                                            <ul name="pagination" class="pagination justify-content-center">
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const membersInfo = $.parseJSON('{!! json_encode(Helper::membersInfo(), JSON_UNESCAPED_UNICODE) !!}');
        const loginType = ['게스트', '구글', '유니티', '플랫폼'];

        $(document).ready(function() {
            $('input[name=reg_date]').daterangepicker({
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
            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });

            goPage = function(page) {
                $('input[name=page]').val(page);
                $('#btn-search').click();
            }
            $('#btn-search').on('click', function() {
                $('#search-errors').html('');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/member/oneday',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val()
                      , keyword: $(':input:text[name=keyword]').val()
                      , reg_date: $('input[name=reg_date]').val()
                      , referrer: $('select[name=input_referrer] option:selected').val()
                      , page: $('input[name=page]').val()
                    },
                    dataType: 'json',
                    success: function(result) {
                        $("#search-error-bag").hide();
                        let tbody = $('tbody');
                        tbody.empty();
                        if(result.data.record_cnt == 0) {
                            tbody.append('<tr><td colspan="7">조회된 데이터가 없습니다.</td></tr>');
                            $('ul[name=pagination]').html(makePagination(result.data.pagination, 'goPage').join(''));
                            return;
                        }
                        $(result.data.records).each(function(idx, row) {
                            let html = [];
                            html.push('<tr>');
                            html.push('<td>' + row.user_seq + '</td>');
                            html.push('<td>' + loginType[row.login_type] + '</td>');
                            html.push('<td>' + row.nickname + '</td>');
                            html.push('<td>' + row.account + '</td>');
                            html.push('<td>' + row.reg_date + '</td>');
                            html.push('<td>' + (row.last_login_date  == null ? '' : row.last_login_date) + '</td>');
                            html.push('<td>' + (row.last_login_ip == null ? '' : row.last_login_ip) + '</td>');
                            html.push('<td>' + (row.cert == 1 ?
                                '<button type="button" class="btn btn-danger text-nowrap" data-cert="cancel" data-user="'+row.user_seq+'">인증취소</button>' :
                                '<button type="button" class="btn btn-success text-nowrap" data-cert="pass" data-user="'+row.user_seq+'">인증처리</button>'
                                ) + '</td>');
                            html.push('</tr>');
                            tbody.append(html.join(''));
                        });
                        $('ul[name=pagination]').html(makePagination(result.data.pagination, 'goPage').join(''));
                    },
                    error: function(result) {
                        if(result.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(result.responseText);
                        $('#search-errors').html('');
                        $.each(errors.messages, function(key, value) {
                            $('#search-errors').append('<li>' + value + '</li>');
                        });
                        $("#search-error-bag").show();
                    }
                });
            });


			$(document).on('click', '[data-cert]', function ()
			{
				let t = $(this).data('cert');
				let u = $(this).data('user');

				if (!confirm((t == 'pass' ? '인증처리' : '취소처리') + ' 하시겠습니까?'))
					return true;

				$.get(
					['/member/certification', u, t].join('/'),
					function (d)
					{
						if (d.message)
							alert(d.message);

						$('#btn-search').trigger('click');
					});
			});
        });
    </script>
@endsection
