@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('operations.chipGold') }}">운영정보</a></li>
                <li class="breadcrumb-item">효력 수정</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>효력 수정</h2>
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
                                            <div class="col-md-2">
                                                <label class="col-form-label col-md-12">
                                                    <input type="text" class="form-control" id="search_input" name="keyword" placeholder="검색어" />
                                                    <input type="text" style="display:none" />
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="col-form-label">
                                                    <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                    <input type="hidden" id="user_seq" />
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
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker2">
                                                <input type="text" class="form-control" id="search_end_date" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
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
                                </div>
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
                                        <th>Email</th>
                                        <th>닉네임</th>
                                        <th>회원번호</th>
                                        <th>접속 IP</th>
                                        <th>가입일자</th>
                                        <th>최종로그인</th>
                                        <th>상태</th>
                                        <th>상태변경 메시지</th>
                                        <th rowspan="2" style="vertical-align: middle;">
                                            <button type="button" id="force_close" class="btn btn-round btn-danger">강제종료</button>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td id="member_id_email"></td>
                                        <td id="member_nickname"></td>
                                        <td id="member_seq"></td>
                                        <td id="member_remote_address"></td>
                                        <td id="member_created_date"></td>
                                        <td id="member_last_login_date"></td>
                                        <td id="member_status"></td>
                                        <td id="member_status_message">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="x_panel">
                            <div class="x_title">
                                <h2>카드덱 효력 조회<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table table-bordered info-table"  id="log_table">
                                    <thead>
                                    <tr>
                                        <th>효력명</th>
                                        <th>구매/획득 일시</th>
                                        <th>만료일시</th>
                                        <th>사용여부</th>
                                        <th>효력정보</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="x_content item_edit_panel">
                                <div class="x_title">
                                    <h2>카드덱 효력 수정<small></small></h2>
                                    <button type="button" id="btn-close-item" class="btn btn-secondary btn-sm" style="position:absolute; top: 0; right:10px;">닫기</button>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row col-md-12 col-sm-12">
                                    <label class="col-form-label col-md-1 col-sm-1 label-align item-title">
                                        효력명 :
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2 label-align">
                                        <input type="text" class="form-control" id="item_name" readonly style="background-color:#fff; border: 0" />
                                        <input type="hidden" id="item_seq" />
                                        <input type="hidden" id="item_id" />
                                    </label>
                                    <label class="col-form-label col-md-1 col-sm-1 label-align  item-title">
                                        사용/종료 일시 :
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2 label-align">
                                        <input type="text" class="form-control" id="item_end_date" readonly style="background-color:#fff; border: 0" />
                                    </label>
                                    <label class="col-form-label col-md-1 col-sm-1 label-align  item-title">
                                        종료일시 지정 :
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2 label-align">
                                        <div class="input-group date" id="datepicker3">
                                            <input type="text" class="form-control" id="edit_end_date" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2 label-align">
                                        <div class="input-group date" id="datepicker4">
                                            <input type="text" class="form-control" id="edit_end_time">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </label>

                                </div>
                                <table class="table table-bordered info-table">
                                    <tr>
                                        <th width="180">처리사유 선택 (1)</th>
                                        <td style="text-align: left;">
                                            <select class="form-control" id="log_type" name="log_type">
                                                <option value="">처리사유 선택</option>
                                                <option value="event">이벤트</option>
                                                <option value="maintenance">보상 - 장애/오류</option>
                                                <option value="correction">오처리</option>
                                                <option value="admin">기타 - 운영자처리</option>
                                                <option value="test">테스트</option>
                                            </select>
                                        </td>
                                        <th width="180">처리사유 입력 (2)</th>
                                        <td>
                                            <input type="text" class="form-control" id="log_reason" name="log_reason" value="" placeholder="처리사유 입력"/>
                                        </td>
                                        <td><button type="button" id="btn-item-edit" class="btn btn-primary edit-btn">수정</button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                        <div class="x_panel">
                            <div class="x_title">
                                <h2>멤버스 정보 조회<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table table-bordered info-table"  id="members_table">
                                    <thead>
                                    <tr>
                                        <th>멤버스 정보</th>
                                        <th>구매 / 획득 일시</th>
                                        <th>사용 만료 일시</th>
                                        <th>사용여부</th>
                                        <th>효력정보</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="tr_members">
                                        <td id="members_grade"></td>
                                        <td id="members_start_date">구매 / 획득 일시</td>
                                        <td id="members_end_date">사용 만료 일시</td>
                                        <td id="members_check">사용여부</td>
                                        <td><input type="hidden" id="members_period" />
                                            <input type="hidden" id="members_type" />
                                            <button type="button" class="btn btn-info btn-sm" id="btn-edit-members">효력수정</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="x_content members_edit_panel">
                            <div class="x_title">
                                <h2>멤버스 효력 수정<small></small></h2>
                                <button type="button" id="btn-close-members" class="btn btn-secondary btn-sm" style="position:absolute; top: 0; right:10px;">닫기</button>
                                <div class="clearfix"></div>
                            </div>
                            <div class="row col-md-12 col-sm-12">
                                <label class="col-form-label col-md-1 col-sm-1 label-align item-title">
                                    효력명 :
                                </label>
                                <label class="col-form-label col-md-2 col-sm-2 label-align">
                                    <input type="text" class="form-control" id="members_name" readonly style="background-color:#fff; border: 0" />
                                </label>
                                <label class="col-form-label col-md-1 col-sm-1 label-align  item-title">
                                    사용/종료 일시 :
                                </label>
                                <label class="col-form-label col-md-2 col-sm-2 label-align">
                                    <input type="text" class="form-control" id="members_end_date_" readonly style="background-color:#fff; border: 0" />
                                </label>
                                <label class="col-form-label col-md-1 col-sm-1 label-align  item-title">
                                    종료일시 지정 :
                                </label>
                                <label class="col-form-label col-md-2 col-sm-2 label-align">
                                    <div class="input-group date" id="datepicker5">
                                        <input type="text" class="form-control" id="members_edit_end_date" />
                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                    </div>
                                </label>
                                <label class="col-form-label col-md-2 col-sm-2 label-align">
                                    <div class="input-group date" id="datepicker6">
                                        <input type="text" class="form-control" id="members_edit_end_time">
                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                    </div>
                                </label>

                            </div>
                            <table class="table table-bordered info-table">
                                <tr>
                                    <th width="180">처리사유 선택 (1)</th>
                                    <td style="text-align: left;">
                                        <select class="form-control" id="members_log_type" name="members_log_type">
                                            <option value="">처리사유 선택</option>
                                            <option value="event">이벤트</option>
                                            <option value="maintenance">보상 - 장애/오류</option>
                                            <option value="correction">오처리</option>
                                            <option value="admin">기타 - 운영자처리</option>
                                            <option value="test">테스트</option>
                                        </select>
                                    </td>
                                    <th width="180">처리사유 입력 (2)</th>
                                    <td>
                                        <input type="text" class="form-control" id="members_log_reason" name="members_log_reason" value="" placeholder="처리사유 입력"/>
                                    </td>
                                    <td><button type="button" id="btn-members-edit" class="btn btn-primary edit-btn">수정</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
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

        .item_edit_panel { display: none; }
        .members_edit_panel { display: none; }
        .item-title { font-weight: bold;line-height: 35px; text-align: right; }
    </style>

    <script>
        const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');
        const membersInfo = $.parseJSON('{!! json_encode(Helper::membersInfo()) !!}');

        $(document).ready(function () {
            $('#datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker2').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker3').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker4').datetimepicker({
                format: 'HH:mm:ss'
            });
            $('#datepicker5').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker6').datetimepicker({
                format: 'HH:mm:ss'
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
                fillSearchDate(parseInt(period), 'search_start_date', 'search_end_date');
            });

            // Search & display
            $('#btn-search').off('click').on('click', function() {
                $('#search-errors').html('');

                let keyword = $('#search_input').val();
                let searchType = $(':input:radio[name=search_type]:checked').val();
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
                    url: '/game/inventory',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val(),
                        keyword: $(':input:text[name=keyword]').val(),
                        platform: $('select[name=login_type]').val(),
                        startDate: startDate,
                        endDate: endDate,
                        from: 'effect'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        reset();

                        let accountInfo = data.accountInfo;
                        let userInfo = data.userInfo;
                        let loginLog = data.loginLog;
                        let adminLog = data.adminLog;
                        $('#user_seq').val(userInfo.user_seq);

                        $('#member_id_email').text(accountInfo.account);
                        $('#member_nickname').text(accountInfo.nickname);
                        $('#member_seq').text(accountInfo.user_seq);
                        $('#member_created_date').text(getFormattedDatetime(userInfo.reg_date));
                        if (loginLog !== null) {
                            $('#member_last_login_date').text(getFormattedDatetime(loginLog.log_date));
                            $('#member_remote_address').text(loginLog.ip);
                        } else {
                            $('#member_last_login_date').text('');
                            $('#member_remote_address').text('');
                        }
                        $('#member_status').text(stateNames[accountInfo.user_state]);
                        if (adminLog !== null) {
                            $('#member_status_message').text(adminLog.reason);
                        } else {
                            $('#member_status_message').text('');
                        }

                        let inventory = data.inventory;

                        $('#log_table tbody').empty();
                        if (inventory.length > 0) {
                            $(inventory).each(function(index, inven) {
                                if (inven.item_type !== "101") {
                                    return true;
                                }

                                let use_date = inven.update_date;
                                let end_date = '-';
                                let in_use = 'N';
                                if (parseInt(inven.is_use) === 1) {
                                    in_use = 'Y';
                                    end_date = membersEndDate(inven.update_date, inven.period_time);
                                }
                                let tr = $('<tr id="tr_'+inven.inven_seq+'">' +
                                    '<td>' + inven.memo + '</td>' +
                                    '<td>' + use_date + '</td>' +
                                    '<td>' + end_date + '</td>' +
                                    '<td>' + in_use + '</td>' +
                                    '<td><button type="button" class="btn btn-info btn-sm btn-editable" ' +
                                    'data-name="'+inven.memo+'" data-date="'+inven.update_date+'" '+
                                    'data-period="'+inven.period_time+'" data-seq="'+inven.inven_seq+'" '+
                                    'data-itemId="'+inven.item_id+'">' +
                                    '효력수정</button></td>' +
                                    '</tr>');
                                $('#log_table tbody').append(tr);
                            });
                        } else {
                            $('#log_table tbody').append('<tr>' +
                                '<td colspan="5"> 검색 결과가 없습니다. </td>' +
                                '</tr>');
                        }

                        let members_name = "없음";
                        let members_start_date = "";
                        let member_end_date = "";
                        let members_check = "-";
                        if (userInfo.members_type > 0) {
                            members_name = membersInfo[userInfo.members_type].name;
                            members_start_date = userInfo.members_start_date;
                            member_end_date = membersEndDate(userInfo.members_start_date, userInfo.members_period);
                            if (checkMembers(userInfo.members_start_date, userInfo.members_period)) {
                                members_check = '사용중';
                            } else {
                                members_check = '만료';
                            }
                        }

                        $('#members_grade').text(members_name);
                        $('#members_type').val(userInfo.members_type);
                        $('#members_start_date').text(members_start_date);
                        $('#members_end_date').text(member_end_date);
                        $('#members_check').text(members_check);
                        $('#members_period').text(userInfo.members_period);

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

            // forceClose
            $('#force_close').unbind('click').bind('click', function() {
                // check user select
                let userSeq = $('#user_seq').val();
                if (userSeq.length === 0) {
                    let errors = {
                        error: true,
                        messages: ['먼저 사용자를 검색하세요.'],
                    };
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();

                    return false;
                } else {

                    if ( !confirm('해당회원의 상태가 정지 상태로 변경됩니다.\n\n해당회원을 강제 종료하시겠습니까?') ) {
                        return false;
                    }


                    // ajax call force close
                    $('#search-errors').html('');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: '/member/forceClose',
                        data: {
                            user_seq: userSeq,
                            from: 'info'
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.error === true) {
                                $('#search-errors').html('');
                                $('#search-errors').append('<li>' + data.messages + '</li>');
                                $("#search-error-bag").show();
                                return;
                            }

                            $("#search-error-bag").hide();

                            alert('해당 회원의 상태가 변경되었습니다.');
                            $('#btn-search').trigger('click');
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
                }
            });

            // action : 아이템 수정
            $('#btn-item-edit').off('click').on('click', function() {
                // check common
                let userSeq = $('#user_seq').val();
                let logType = $('#log_type option:selected').val();
                let logReason = $('#log_reason').val();
                let actionType = 'edit';
                let target = 'item';
                let invenSeq = $('#item_seq').val();
                let itemId = $('#item_id').val();
                let changeDate = $('#edit_end_date').val();
                let changeTime = $('#edit_end_time').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    return false;
                }

                if (logType.length === 0) {
                    alert('처리사유(1)를 선택하세요');
                    return false;
                }

                if (logReason.length === 0) {
                    alert('처리사유(2)를 입력하세요.');
                    return false;
                }

                let params = {
                    userSeq: userSeq,
                    actionType: actionType,
                    logType: logType,
                    logReason: logReason,
                    target: target,
                    invenSeq: invenSeq,
                    itemId: itemId,
                    membersType: -1,
                    changeDate: changeDate + ' ' + changeTime,
                };
                ajaxPresents(params);
            });

            // action : 멤버스 수정
            $('#btn-members-edit').off('click').on('click', function() {
                // check common
                let userSeq = $('#user_seq').val();
                let logType = $('#members_log_type option:selected').val();
                let logReason = $('#members_log_reason').val();
                let actionType = 'edit';
                let target = 'members';
                let membersType = $('#members_type').val();
                let changeDate = $('#members_edit_end_date').val();
                let changeTime = $('#members_edit_end_time').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    return false;
                }

                if (logType.length === 0) {
                    alert('처리사유(1)를 선택하세요');
                    return false;
                }

                if (logReason.length === 0) {
                    alert('처리사유(2)를 입력하세요.');
                    return false;
                }

                let params = {
                    userSeq: userSeq,
                    actionType: actionType,
                    logType: logType,
                    logReason: logReason,
                    target: target,
                    invenSeq: -1,
                    itemId: -1,
                    membersType: membersType,
                    changeDate: changeDate + ' ' + changeTime,
                };
                ajaxPresents(params);
            });

            $('#btn-close-item').off('click').on('click', function() {
                $('#log_table tr').children('td, th').css('background', '#FFF');
                $('.item_edit_panel').hide();
            });

            $('#btn-close-members').off('click').on('click', function() {
                $('#members_table tr').children('td, th').css('background', '#FFF');
                $('.members_edit_panel').hide();
            });

        });

        // show item edit panel
        $(document).on('click', '.btn-editable', function () {
            reset();
            $('.item_edit_panel').show();
            let data = $(this).data();
            console.log('data', data);
            let end_date = membersEndDate(data.date, data.period);
            let edit_date = end_date.substr(0, 10);
            let edit_time = end_date.substr(11, end_date.length);
            $('#item_name').val(data.name);
            $('#item_seq').val(data.seq);
            $('#item_id').val(data.itemId);
            $('#item_end_date').val(end_date);
            $('#edit_end_date').val(edit_date);
            $('#edit_end_time').val(edit_time);

            $('#tr_'+data.seq).children('td, th').css('background', '#FFB6C1');
        });

        // show members edit panel
        $(document).on('click', '#btn-edit-members', function () {
            reset();
            let name = $('#members_grade').text();
            let end_date = $('#members_end_date').text();
            let edit_date = end_date.substr(0, 10);
            let edit_time = end_date.substr(11, end_date.length);
            $('#members_name').val(name);
            $('#members_end_date_').val(end_date);
            $('#members_edit_end_date').val(edit_date);
            $('#members_edit_end_time').val(edit_time);

            if (end_date.length === 0) {
                alert('멤버스 정보가 없습니다.');
                return false;
            }

            $('.members_edit_panel').show();
            $('#tr_members').children('td, th').css('background', '#FFB6C1');
        });

        // Send
        function ajaxPresents(params) {
            console.log(params);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/operation/editEffect',
                data: {
                    userSeq: params.userSeq,
                    actionType: params.actionType,
                    logType: params.logType,
                    logReason: params.logReason,
                    target: params.target,
                    invenSeq: params.invenSeq,
                    itemId: params.itemId,
                    membersType: params.membersType,
                    changeDate: params.changeDate,
                    from: 'effect'
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }

                    $("#search-error-bag").hide();

                    alert('아이템/멤버스 효력 수정이 완료되었습니다.');
                    $('#btn-search').trigger('click');
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
        }

        function reset() {
            $('#log_table tr').children('td, th').css('background', '#FFF');
            $('#members_table tr').children('td, th').css('background', '#FFF');

            $('#log_type').val($("#log_type option:first").val());
            $('#log_reason').val('');

            $('#members_log_type').val($("#members_log_type option:first").val());
            $('#members_log_reason').val('');

            $('.item_edit_panel').hide();
            $('.members_edit_panel').hide();
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
    <!-- bootstrap-daterangepicker -->
    <script src="/vendors/moment/min/moment.min.js"></script>
    <script src="/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
@endsection
