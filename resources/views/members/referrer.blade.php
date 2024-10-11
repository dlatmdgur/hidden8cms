@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.list') }}">사용자조회</a></li>
                <li class="breadcrumb-item">추천코드별 가입자</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>추천코드별 가입자</h2>
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
                                                <div class="col-md-3">
                                                    <label class="col-form-label col-md-12">
                                                        <input type="text" class="form-control" name="referrer_id" placeholder="추천인아이디" />
                                                        <input type="text" style="display:none" />
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

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <script src="/vendors/switchery/dist/switchery.min.js"></script>
    <style>
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter > label { width: 100%; text-align: left; }
        .dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }
        .switchery>small { width:20px;height:20px }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
        .innerTable > tbody > tr:first-child > td { border-top: 0; }
        .innerTable > tbody > tr:last-child > td { border-bottom: 0; }
        .innerTable td { border-left: 0; border-right: 0; }
    </style>

    <script>
        const membersInfo = $.parseJSON('{!! json_encode(Helper::membersInfo(), JSON_UNESCAPED_UNICODE) !!}');
        const loginType = ['게스트', '구글', '유니티', '플랫폼'];

        $(document).ready(function() {
            $('input[name=referrer_id]').on('keyup', function(e) {
                let keyCode = e.keyCode || e.which;
                e.preventDefault();
                if(keyCode === 13) $('#btn-search').trigger('click');
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
                    url: '/member/referrer',
                    data: {
                        referrer_id: $('input[name=referrer_id]').val()
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
                            html.push('<td>' + (row.platform_id == '' ? row.google_email : row.platform_id) + '</td>');
                            html.push('<td>' + row.created_at + '</td>');
                            html.push('<td>' + row.login_date + '</td>');
                            html.push('<td>' + row.login_ip + '</td>');
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
        });
    </script>
@endsection
