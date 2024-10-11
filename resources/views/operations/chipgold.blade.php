@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('operations.chipGold') }}">운영정보</a></li>
                <li class="breadcrumb-item">칩/골드 지급/회수</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>칩/골드 지급/회수</h2>
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
                                                <div class="col-md-2">
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
                                                <th>Email</th>
                                                <th>닉네임</th>
                                                <th>회원번호</th>
                                                <th>접속 IP</th>
                                                <th>가입일자</th>
                                                <th>최종로그인</th>
                                                <th>상태</th>
                                                <th>상태변경 메시지</th>
                                                <th rowspan="2" style="vertical-align: middle;">
                                                    <input type="hidden" id="user_seq" value="" />
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
                                        <h2>지급정보 입력<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <tr>
                                                <th>처리사유 선택 (1)</th>
                                                <th>처리사유 입력 (2)</th>
                                            </tr>
                                            <tr>
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
                                                <td>
                                                    <input type="text" class="form-control" id="log_reason" name="log_reason" value="" placeholder="처리사유 입력"/>
                                                    <input type="hidden" id="user_seq" value="" />
                                                    <input type="hidden" id="origin_chip" value="" />
                                                    <input type="hidden" id="origin_gold" value="" />
                                                    <input type="hidden" id="origin_safe_chip" value="" />
                                                    <input type="hidden" id="origin_safe_gold" value="" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>보유정보 / 변경<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table"  id="log_table">
                                            <thead>
                                            <tr>
                                                <th colspan="2">보유정보</th>
                                                <th>처리 구분</th>
                                                <th>변경 수량</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th>소지 칩</th>
                                                <td id="user_chip" class="number" width="200">0</td>
                                                <td width="150"><select class="form-control" id="chip_action_type" name="chip_action_type">
                                                        <option value="give">지급</option>
                                                        <option value="revoke">회수</option>
                                                    </select></td>
                                                <td><input type="number" class="form-control" id="chip_change" name="chip_change" style="ime-mode:disabled;" value="" placeholder="금액입력" /></td>
                                                <td><button type="button" class="btn btn-primary action-btn" target="chip">칩/골드 지급/회수</button></td>
                                            </tr>
                                            <tr>
                                                <th>칩 금고</th>
                                                <td id="user_safe_chip" class="number">0</td>
                                                <td><select class="form-control" id="safe_chip_action_type" name="safe_chip_action_type">
                                                        <option value="give">지급</option>
                                                        <option value="revoke">회수</option>
                                                    </select></td>
                                                <td><input type="number" class="form-control" id="safe_chip_change" name="safe_chip_change" style="ime-mode:disabled;" value="" placeholder="금액입력" /></td>
                                                <td><button type="button" class="btn btn-info action-btn" target="safe_chip">금고 칩 지급/회수</button></td>
                                            </tr>
                                            <tr>
                                                <th>소지 골드</th>
                                                <td id="user_gold" class="number">0</td>
                                                <td><select class="form-control" id="gold_action_type" name="gold_action_type">
                                                        <option value="give">지급</option>
                                                        <option value="revoke">회수</option>
                                                    </select></td>
                                                <td><input type="number" class="form-control" id="gold_change" name="gold_change" style="ime-mode:disabled;" value="" placeholder="금액입력" /></td>
                                                <td><button type="button" class="btn btn-primary action-btn" target="gold">골드 지급/회수</button></td>
                                            </tr>
                                            <tr>
                                                <th>골드 금고</th>
                                                <td id="user_safe_gold" class="number">0</td>
                                                <td><select class="form-control" id="safe_gold_action_type" name="safe_gold_action_type">
                                                        <option value="give">지급</option>
                                                        <option value="revoke">회수</option>
                                                    </select></td>
                                                <td><input type="number" class="form-control" id="safe_gold_change" name="safe_gold_change" style="ime-mode:disabled;" value="" placeholder="금액입력" /></td>
                                                <td><button type="button" class="btn btn-info action-btn" target="safe_gold">금고 골드 지급/회수</button></td>
                                            </tr>
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
        .dataTables_filter > label { width: 100%; text-align: left; }
        .dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }
        .paging_full_numbers { width: auto; }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script>
        const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');

        $(document).ready(function () {
            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });

            // Search & display
            $('#btn-search').off('click').on('click', function() {
                $('#search-errors').html('');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/game/userInfo',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val(),
                        platform: $('select[name=login_type]').val(),
                        keyword: $(':input:text[name=keyword]').val(),
                        from: 'game'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

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

                        // origin
                        $('#origin_chip').val(userInfo.chip);
                        $('#origin_gold').val(userInfo.gold);
                        $('#origin_safe_chip').val(userInfo.safe_chip);
                        $('#origin_safe_gold').val(userInfo.safe_gold);
                        // table
                        $('#user_chip').text(numberToKorean(userInfo.chip));
                        $('#user_gold').text(numberToKorean(userInfo.gold));
                        $('#user_safe_chip').text(numberToKorean(userInfo.safe_chip));
                        $('#user_safe_gold').text(numberToKorean(userInfo.safe_gold));

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

            // forceClose
            $('#force_close').unbind('click').bind('click', function() {
                // check user select
                let userSeq = $('#user_seq').val();
                if (userSeq.length === 0) {
                    let errors = {
                        error: true,
                        messages: ['먼저 사용자를 검색하세요.'],
                    };
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
                            $('#search-errors').html('');
                            $.each(errors.messages, function(key, value) {
                                $('#search-errors').append('<li>' + value + '</li>');
                            });
                            $("#search-error-bag").show();
                        }
                    });
                }
            });

            // action
            $('.action-btn').off('click').on('click', function() {
               // check common
                let userSeq = $('#user_seq').val();
                let logType = $('#log_type option:selected').val();
                let logReason = $('#log_reason').val();

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

                // target check
                let target = $(this).attr('target');
                let origin = $('#origin_'+target).val();
                let actionType = $('#'+target+'_action_type option:selected').val();
                let changeAmount = $('#'+target+'_change').val();

                if (actionType.length === 0) {
                    alert('처리 구분을 선택하세요');
                    return false;
                }
                if (changeAmount.length === 0) {
                    alert('변경 수량을 입력하세요');
                    return false;
                }

                if (actionType === "revoke") {
                    if (parseInt(origin) - parseInt(changeAmount) < 0) {
                        alert('보유량보다 많이 회수 할 수 없습니다.');
                        return false;
                    }
                }

                if (!confirm('머니 지급/회수를 진행하시겠습니까?')) {
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/operation/editChipGold',
                    data: {
                        userSeq: userSeq,
                        actionType: actionType,
                        logType: logType,
                        logReason: logReason,
                        target: target,
                        changeAmount: changeAmount,
                        from: 'chipgold'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        $("#search-error-bag").hide();

                        let userInfo = data.userInfo;

                        // origin
                        $('#origin_chip').val(userInfo.chip);
                        $('#origin_gold').val(userInfo.gold);
                        $('#origin_safe_chip').val(userInfo.safe_chip);
                        $('#origin_safe_gold').val(userInfo.safe_gold);
                        // table
                        $('#user_chip').text(numberToKorean(userInfo.chip));
                        $('#user_gold').text(numberToKorean(userInfo.gold));
                        $('#user_safe_chip').text(numberToKorean(userInfo.safe_chip));
                        $('#user_safe_gold').text(numberToKorean(userInfo.safe_gold));

                        // reset
                        reset();
                        alert('머니 지급/회수가 완료되었습니다.');
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

        function reset() {
            $('#log_type').val($("#log_type option:first").val());
            $('#log_reason').val('');

            $('#chip_action_type').val($("#chip_action_type option:first").val());
            $('#gold_action_type').val($("#gold_action_type option:first").val());
            $('#safe_chip_action_type').val($("#safe_chip_action_type option:first").val());
            $('#safe_gold_action_type').val($("#safe_gold_action_type option:first").val());

            $('#chip_change').val('');
            $('#gold_change').val('');
            $('#safe_chip_change').val('');
            $('#safe_gold_change').val('');
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
@endsection
