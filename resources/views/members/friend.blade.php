@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.list') }}">사용자조회</a></li>
                <li class="breadcrumb-item">친구 리스트</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>친구 리스트</h2>
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

                                        <form id="searchForm" action="{{ route('members.friend') }}">
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
                                <div class="row">
                                    <div class="col-6">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <h2>내가 친구 등록<small></small></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <table class="table table-bordered info-table">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>No</th>
                                                        <th>user_seq</th>
                                                        <th>닉네임</th>
                                                        <th>등록날짜</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($friends_i as $key => $friend_i)
                                                        <tr>
                                                            <td class="text-center">{{ count($friends_i) - $key }}</td>
                                                            <td class="text-center">{{ $friend_i->friend_seq }}</td>
                                                            <td class="text-center">{{ $friend_i->nickname }}</td>
                                                            <td class="text-center">{{ $friend_i->update_date }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <h2>나를 친구 등록 <small></small></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <table class="table table-bordered info-table">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>No</th>
                                                        <th>user_seq</th>
                                                        <th>닉네임</th>
                                                        <th>등록날짜</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($friends_me as $key => $friend_me)
                                                        <tr>
                                                            <td class="text-center">{{ count($friends_me) - $key }}</td>
                                                            <td class="text-center">{{ $friend_me->user_seq }}</td>
                                                            <td class="text-center">{{ $friend_me->nickname }}</td>
                                                            <td class="text-center">{{ $friend_me->update_date }}</td>
                                                        </tr>
                                                    @endforeach
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
        </div>
    </div>
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
@endsection
