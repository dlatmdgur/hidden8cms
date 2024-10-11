@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.list') }}">사용자조회</a></li>
                <li class="breadcrumb-item">회원 정보 변경</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>회원 정보 변경</h2>
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

                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>기본정보 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <tr>
                                                <th>가입구분</th>
                                                <th>계정명</th>
                                                <th>닉네임</th>
                                                <th>회원번호</th>
                                                <th>접속 IP</th>
                                                <th>가입일자</th>
                                                <th>최종로그인</th>
                                                <th>상태</th>
                                                <th>실명인증</th>
                                            </tr>
                                            <tr>
                                                <td id="member_join_type"></td>
                                                <td id="member_id_email"></td>
                                                <td id="member_nickname"></td>
                                                <td id="member_seq"></td>
                                                <td id="member_remote_address"></td>
                                                <td id="member_created_date"></td>
                                                <td id="member_last_login_date"></td>
                                                <td id="member_status"> - </td>
                                                <td id="member_identification"> - </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>변경 가능정보 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="alert alert-danger" id="edit-error-bag" style="display: none;">
                                            <ul id="edit-errors" style="padding-bottom: 0; margin-bottom: 0;">
                                            </ul>
                                        </div>

                                        <table class="table table-bordered edit-table">
                                            <tr>
                                                <th>닉네임</th>
                                                <td>
                                                    <label class="col-md-4">
                                                        <input type="text" class="form-control" id="edit-nick" name="nickname" placeholder="닉네임" />
                                                        <input type="hidden" class="form-control" id="old-nick" />
                                                    </label>
                                                    <label class="col-md-2">
                                                        <button type="button" id="btn-checkNick" class="btn btn-secondary">중복확인</button>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="15%">상태변경</th>
                                                <td><label class="col-md-3">
                                                        <select class="form-control" id="edit-state" name="change_state">
                                                            <option value="">변경할 상태를 선택해주세요.</option>
                                                            <option value="1">정상</option>
                                                            <option value="2">탈퇴진행중</option>
                                                            <option value="3">정지</option>
                                                            <option value="4">CS 정지</option>
                                                            <!--<option value="1">정상</option>
                                                            <option value="2">휴면</option>
                                                            <option value="3">3일 정지</option>
                                                            <option value="4">7일 정지</option>
                                                            <option value="5">30일 정지</option>
                                                            <option value="6">영구 정지</option>-->
                                                        </select>
                                                    </label>
                                                    <label class="col-md-3">
                                                        <!--<select class="form-control" id="edit-state" name="change_state">
                                                            <option value="">기간 선택.</option>
                                                            <option value="1">1일</option>
                                                            <option value="2">2일</option>
                                                            ...
                                                            <option value="6">영구 정지</option>
                                                        </select>-->
                                                    </label>
                                                    <label class="col-md-6">
                                                        <input type="hidden" class="form-control" id="before_state" name="before_state" style="border: 0; background-color: #fff;" readonly />
                                                        <input type="text" class="form-control" id="edit-reason" name="reaseon" placeholder="상태변경 사유를 입력하세요" />
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="text-align: right">
                                            <input type="hidden" id="user_seq" />
                                            <input type="hidden" id="nickname" />
                                            <button type="button" id="btn-edit" class="btn btn-secondary">변경</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>회원정보 변경 내역 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered log-table"  id="log_table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%">No</th>
                                                    <th style="width: 15%">변경 일시</th>
                                                    <th style="width: 15%">서비스 상태</th>
                                                    <th style="width: 15%">닉네임</th>
                                                    <th>상태변경 사유</th>
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

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter { display: none; }
        .paging_full_numbers { width: auto; }
        /*.dataTables_filter > label { width: 100%; text-align: left; }*/
        /*.dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }*/
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
        .edit-table th { text-align: center; vertical-align: middle; }
        .edit-table td { text-align: center; vertical-align: middle; }
        .edit-table td.number { text-align: right; }
        .log-table th { text-align: center; vertical-align: middle; }
        .log-table td { text-align: center; vertical-align: middle; }
        .log-table td.number { text-align: right; }

        .log-table td { max-height: 190px !important; }
        .reason { height: 60px }
        .descript { max-height: 190px !important; overflow-y: auto; text-align: left; }
    </style>

    <script>
        const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');

        $(document).ready(function() {
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
                            return '회원정보변경내역_' + $('#member_nickname').text();
                        }
                    }
                ],
            });

            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });;

            $('#btn-search').unbind('click').bind('click', function() {
                $('#search-errors').html('');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/member/search',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val(),
                        platform: $('select[name=login_type]').val(),
                        keyword: $(':input:text[name=keyword]').val(),
                        from: 'edit'
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data.error === true) {
                            // console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        let accountInfo = data.accountInfo;
                        let userInfo = data.userInfo;
                        let loginLog = data.loginLog;
                        let adminLog = data.adminLog;

                        let account_id = accountInfo.account;
                        let uuid = accountInfo.google_uuid;
                        if (accountInfo.google_email === '') {
                            email_id = accountInfo.platform_id;
                            uuid = accountInfo.platform_id;
                        }
                        let join_type = '구글';
                        if (accountInfo.login_type === '3') {
                            join_type = '플랫폼'
                        } else if (accountInfo.login_type === '4') {
                            join_type = '애플'
                        }

                        // user info
                        $('#member_join_type').text(join_type);
                        $('#member_id_email').text(account_id);
                        $('#member_nickname').text(accountInfo.nickname);
                        $('#member_seq').text(accountInfo.user_seq);
                        $('#member_created_date').text(getFormattedDatetime(userInfo.reg_date));
                        $('#member_last_login_date').text(getFormattedDatetime(loginLog.log_date));
                        $('#member_remote_address').text(loginLog.ip);
                        $('#member_status').text(stateNames[accountInfo.user_state]);

                        $('#member_identification').html(
                            data.is_cert == 1 ?
                            '<button type="button" class="btn btn-danger text-nowrap" data-cert="cancel" data-user="'+accountInfo.user_seq+'">인증취소</button>' :
                            '<button type="button" class="btn btn-success text-nowrap" data-cert="pass" data-user="'+accountInfo.user_seq+'">인증처리</button>'
                            );

                        // edit fill
                        $('#user_seq').val(accountInfo.user_seq);
                        $('#edit-nick').val(accountInfo.nickname);
                        $('#old-nick').val(accountInfo.nickname);
                        $('#before_state').val(stateNames[accountInfo.user_state]);

                        // admin logs
                        log_table.clear();
                        $('#log_table tbody').empty();
                        let no = 0;
                        $(adminLog).each(function(index, log) {
                             // console.log('log', log);
                            let tr = $('<tr>' +
                                '<td>' + (++no) + '</td>' +
                                '<td>'+getFormattedDatetime(log.updated_at)+'</td>' +
                                '<td>'+stateNames[log.after_state]+'</td>' +
                                '<td>'+log.nickname+'</td>' +
                                '<td><div class="descript">'+log.logMenu+': '+ log.reason+' '+log.extra+'<div></td>' +
                                '</tr>');
                            log_table.row.add(tr);
                        });
                        log_table.draw();

                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
                        // console.log(errors);
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

            $('#btn-edit').unbind('click').bind('click', function() {
                let user_seq = $('#user_seq').val();
                let nickname = $('#edit-nick').val();

                if ( user_seq.length === 0 ) {
                    alert('변경할 회원을 검색해주세요.');
                    $('#search_input').focus();
                    return;
                }

                if (!confirm( nickname + ' 회원의 정보를 변경하시겠습니까?')) {
                    return;
                }

                $('#edit-errors').html('');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/member/update',
                    data: {
                        user_seq: user_seq,
                        nickname: nickname,
                        menu: 'member',
                        action: 'edit',
                        state: $('#edit-state option:selected').val(),
                        reason: $('#edit-reason').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data.error === true) {
                            // console.log('checked error');
                            return;
                        }

                        $("#edit-error-bag").hide();

                        // success alert()
                        alert('해당 회원의 정보가 변경되었습니다.');
                        if(data.params.state == 2) document.location.reload();
                        else {
                            $('#edit-reason').val('');
                            $('#btn-search').trigger('click');
                        }
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
                        $('#edit-errors').html('');
                        $.each(errors.messages, function(key, value) {
                            $('#edit-errors').append('<li>' + value + '</li>');
                        });
                        $("#edit-error-bag").show();
                    }
                });
            });

            $('#btn-checkNick').click(function() {
                let userSeq = $('#user_seq').val();
                let oldNickname = $('#old-nick').val();
                let newNickname = $('#edit-nick').val();

                if ( userSeq.length === 0 ) {
                    alert('변경할 회원을 검색해주세요.');
                    $('#search_input').focus();
                    return;
                }

                if (newNickname.length === 0) {
                    showError('변경할 닉네임을 입력해주세요.');
                    $('#edit-nick').focus();
                    return;
                } else if (oldNickname === newNickname) {
                    showError('이전 닉네임과 동일합니다.');
                    $('#edit-nick').focus();
                    return;
                }

                // console.log(data);
                $('#edit-errors').html('');
                $("#edit-error-bag").hide();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/member/checkNickname',
                    data: {
                        userSeq: userSeq,
                        nickname: newNickname,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        alert(data.messages[0]);
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
                        $('#edit-errors').html('');
                        $.each(errors.messages, function(key, value) {
                            $('#edit-errors').append('<li>' + value + '</li>');
                        });
                        $("#edit-error-bag").show();
                    }
                });
            });
        });

        function showError(message) {
            $('#edit-errors').html('');
            $('#edit-errors').append('<li>' + message + '</li>');
            $("#edit-error-bag").show();
        }

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
