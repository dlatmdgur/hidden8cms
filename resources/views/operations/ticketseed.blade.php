@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('operations.chipGold') }}">운영정보</a></li>
                <li class="breadcrumb-item">티켓이벤트 수정</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>티켓이벤트 수정</h2>
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
                                        <h2>보유 티켓이벤트<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table text-center">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>최근 업데이트</th>
                                                <th>상품명</th>
                                                <th>상품수량</th>
                                                <th>액션</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tickets as $key => $row)
                                                <tr>
                                                    <td>{{ $numberStart-- }}</td>
                                                    <td>{{ $row->update_date }}</td>
                                                    <td>{{ $row->item_name }}</td>
                                                    <td>{{ $row->item_ea }}</td>
                                                    <td>
                                                        <button name="delOne" data-i="{{ $row->inven_seq }}" class="btn btn-secondary">1개삭제</button>
                                                        <button name="delAll" data-i="{{ $row->inven_seq }}" class="btn btn-secondary">모두삭제</button>
                                                    </td>
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
    <script>
    $(document).ready(function () {
        $('#search_input').on('keyup', function(e) {
            var keyCode = e.keyCode || e.which;
            e.preventDefault();
            if (keyCode === 13) {
                $('#btn-search').trigger('click');
            }
        });

        $('#btn-search').on('click', function() {
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
