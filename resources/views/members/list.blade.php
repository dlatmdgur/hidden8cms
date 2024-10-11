@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.list') }}">사용자조회</a></li>
                <li class="breadcrumb-item">접속회원 리스트</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>접속회원 리스트</h2>
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

                                        <form id="searchForm" action="/member/list">
                                            <div class="item form-group row">
                                                <label class="col-md-3 col-sm-3 ">
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="nickname" {{ (isset($search['search_type']) && $search['search_type'] == "nickname")? "checked" : "" }} required /> 닉네임 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="userSeq" {{ (isset($search['search_type']) && $search['search_type'] == "userSeq")? "checked" : "" }} /> 회원번호 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="email" {{ (isset($search['search_type']) && $search['search_type'] == "email")? "checked" : "" }} /> Email
                                                    </label>
                                                    <label class="col-form-label label-align ml-2">
                                                        <select class="form-control" name="login_type">
                                                            <option value="1" {{ (isset($search['login_type']) && $search['login_type'] === "1")? "selected" : "" }}>구글</option>
                                                            <option value="3" {{ (isset($search['login_type']) && $search['login_type'] === "3")? "selected" : "" }}>플랫폼</option>
                                                            <option value="2" {{ (isset($search['login_type']) && $search['login_type'] === "2")? "selected" : "" }}>유니티</option>
                                                            <option value="0" {{ (isset($search['login_type']) && $search['login_type'] === "0")? "selected" : "" }}>게스트</option>
                                                        </select>
                                                    </label>
                                                </label>
                                                <div class="col-md-3">
                                                    <label class="col-form-label col-md-12">
                                                        <input type="text" class="form-control" id="search_input" name="keyword" value="{{ $search['keyword'] }}" placeholder="검색어" />
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

                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>접속회원 리스트 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>가입구분</th>
                                                <th>로그인구분</th>
                                                <th>계정명</th>
                                                <th>닉네임</th>
                                                <th>회원번호</th>
                                                <th>접속IP</th>
                                                <th>가입일자</th>
                                                <th>최종로그인</th>
                                                <th>상태</th>
                                                <th>상태변경메시지</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($memberList as $index => $member)
                                                <tr>
                                                    <td class="text-center">{{ ($page - 1) * 20 + $index + 1 }}</td>
                                                    <td class="text-center">{{ \App\Helpers\Helper::marketTypeKor($member->login_type) }}</td>
                                                    <td class="text-center">{{ isset($login_type_name[$member->login_type]) ? $login_type_name[$member->login_type] : '' }}</td>
                                                    <td class="text-center"><a href="/member/info?user_seq={{ $member->user_seq }}">{{ $member->account }}</a></td>
                                                    <td class="text-center"><a href="/member/info?user_seq={{ $member->user_seq }}">{{ $member->nickname }}</a></td>
                                                    <td class="text-center"><a href="/member/info?user_seq={{ $member->user_seq }}">{{ $member->user_seq }}</a></td>
                                                    <td class="text-center">{{ $member->last_login_ip }}</td>
                                                    <td class="text-center">{{ $member->reg_date }}</td>
                                                    <td class="text-center">{{ $member->last_login_date }}</td>
                                                    <td class="text-center">{{ \App\Helpers\Helper::getUserState($member->user_state) }}</td>
                                                    <td class="text-center">{{ $member->admin_reason }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    {!! $memberList->appends($search)->render() !!}
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
    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter > label { width: 100%; text-align: left; }
        .dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }
        .paging_full_numbers { width: auto; }
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
        .innerTable { width: 100%; }
        .innerTable > tbody > tr:first-child > td { border-top: 0; }
        .innerTable > tbody > tr:last-child > td { border-bottom: 0; }
        .innerTable td { border-left: 0; border-right: 0; }
    </style>

    <script>
        $(document).ready(function() {

            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });

            $('#btn-search').unbind('click').bind('click', function() {
                $('#search-errors').html('');

                $('#searchForm').submit();
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

@endsection
