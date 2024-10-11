@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('operations.chipGold') }}">운영정보</a></li>
                <li class="breadcrumb-item">가방 지급/회수</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>가방 지급/회수</h2>
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
                                                <!--<th rowspan="2" style="vertical-align: middle;">
                                                    <button type="button" id="force_close" class="btn btn-round btn-danger">강제종료</button>
                                                </th>-->
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
                                                <th width="200">상품선택</th>
                                                <td><select class="form-control" id="present_type" name="present_type">
@canany(['operation'])
                                                        <option value="">지급 상품 선택</option>
                                                        <option value="2003">아바타 카드</option>
                                                        <option value="2012">룰렛 티켓</option>
                                                        <option value="2014">초보 스타트 팩</option>
                                                        <option value="2016">칩</option>
                                                        <option value="2017">젬</option>
                                                        <option value="2018">골드</option>
                                                        <option value="2019">골드 티켓</option>
                                                        <option value="2025">이벤트보석</option>
                                                        <option value="3200">2.5만 칩토너먼트 티켓</option>
                                                        <option value="3201">10만 칩토너먼트 티켓</option>
                                                        <option value="3202">50만 칩토너먼트 티켓</option>
                                                        <option value="3240">100만 칩토너먼트 티켓</option>
                                                        <option value="3241">150만 칩토너먼트 티켓</option>
                                                        <option value="3242">250만 칩토너먼트 티켓</option>
                                                        <option value="3245">350만 칩토너먼트 티켓</option>
                                                        <option value="3243">500만 칩토너먼트 티켓</option>
                                                        <option value="3244">1000만 칩토너먼트 티켓</option>
                                                        <option value="3210">토너티켓_칩_기간제_2.5만</option>
                                                        <option value="3211">토너티켓_칩_기간제_10만</option>
                                                        <option value="3212">토너티켓_칩_기간제_50만</option>
                                                        <option value="3220">토너티켓_골드_2.5만(무제한)</option>
                                                        <option value="3221">토너티켓_골드_10만(무제한)</option>
                                                        <option value="3222">토너티켓_골드_50만(무제한)</option>
                                                        <option value="3230">2.5만 골드토너먼트 티켓</option>
                                                        <option value="3250">5만 골드토너먼트 티켓</option>
                                                        <option value="3231">10만 골드토너먼트 티켓</option>
                                                        <option value="3251">25만 골드토너먼트 티켓</option>
                                                        <option value="3232">50만 골드토너먼트 티켓</option>
                                                        <option value="40101">2월 칩 티켓</option>
                                                        <option value="40102">2월 골드 티켓</option>
                                                        <option value="40201">3월 칩 티켓</option>
                                                        <option value="40202">3월 골드 티켓</option>
                                                        <option value="40301">4월 칩 티켓</option>
                                                        <option value="40302">4월 골드 티켓</option>
                                                        <option value="40401">5월 칩 티켓</option>
                                                        <option value="40402">5월 골드 티켓</option>
                                                        <option value="40501">6월 칩 티켓</option>
                                                        <option value="40502">6월 골드 티켓</option>
                                                        <option value="40601">7월 칩 티켓</option>
                                                        <option value="40602">7월 골드 티켓</option>
                                                        <option value="40701">8월 칩 티켓</option>
                                                        <option value="40702">8월 골드 티켓</option>
                                                        <option value="40801">9월 칩 티켓</option>
                                                        <option value="40802">9월 골드 티켓</option>
                                                        <option value="40901">10월 칩 티켓</option>
                                                        <option value="40902">10월 골드 티켓</option>
                                                        <option value="41001">11월 칩 티켓</option>
                                                        <option value="41002">11월 골드 티켓</option>
                                                        <option value="41101">12월 칩 티켓</option>
                                                        <option value="41102">12월 골드 티켓</option>
                                                        <option value="41201">1월 칩 티켓</option>
                                                        <option value="41202">1월 골드 티켓</option>
                                                        <option value="3100">티켓이벤트25만</option>
                                                        <option value="3101">티켓이벤트50만</option>
                                                        <option value="3102">티켓이벤트100만</option>
                                                        <option value="3301">티켓이벤트300만</option>
                                                        <option value="3302">티켓이벤트500만</option>
@elsecanany(['outer0'])
                                                        <option value="2018">골드</option>
@endcan
                                                    </select>
                                                </td>
                                                <th width="200">상품수량</th>
                                                <td>
                                                    <input type="number" class="form-control" id="present_amount" name="present_amount" value="" min="0" max="99999999999" step="1" placeholder="상품수량 입력" required />
                                                    <p style="margin: 5px 0 0 7px; font-size: 13px; font-weight: 900; text-align: left; letter-spacing: 0.08rem;"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>처리사유 선택 (1)</th>
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
                                                <th>처리사유 입력 (2)</th>
                                                <td>
                                                    <input type="text" class="form-control" id="log_reason" name="log_reason" value="" placeholder="처리사유 입력"/>
                                                    <input type="hidden" id="user_seq" value="" />
                                                    <input type="hidden" id="origin_gem" value="" />
                                                    <input type="hidden" id="origin_event_gem" value="" />
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="border-left:0; border-right:0; border-bottom:0; text-align: right;"><button type="button" id="btn-give" class="btn btn-primary give-btn">지급</button></div>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>가방 조회/회수<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table"  id="log_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>받은 날짜</th>
                                                <th>상품설명</th>
                                                <th>보낸이</th>
                                                <th>상품수량</th>
                                                <th>획득경로</th>
                                                <th>회수</th>
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
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter { display: none; }
        .paging_full_numbers { width: auto; }

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script src="/js/apis.js"></script>
    <script>
        const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');

        $(document).ready(function () {
            let log_table = $('#log_table').DataTable({
                aaSorting: [],
                bSort: false,
                pageLength: 10,
                pagingType: 'full_numbers',
                language: {
                    "emptyTable": "데이터가 없습니다."
                },
            });

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
                        from: 'posts'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

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

                        // origin
                        $('#origin_gem').val(userInfo.gem);
                        $('#origin_event_gem').val(userInfo.gem_event);

                        // table
                        $('#user_gem').text(numberToKorean(userInfo.gem));
                        $('#user_event_gem').text(numberToKorean(userInfo.gem_event));

                        // log table
                        let presents = data.presents;
                        log_table.clear();
                        $('#log_table tbody').empty();
                        if (presents.length > 0) {
                            let no = 0;
                            $(presents).each(function(index, log) {
                                let tr = $('<tr id="tr_'+log.present_seq+'">' +
                                    '<td>' + (++no) + '</td>' +
                                    '<td>' + log.update_date + '</td>' +
                                    '<td>' + log.item_name + '</td>' +
                                    '<td>시스템</td>' +
                                    '<td class="number">' + numberToKorean(log.item_ea) + '</td>' +
                                    '<td>' + log.reason + '</td>' +
                                    '<td><button type="button" class="btn btn-info btn-sm btn-revoke" present_type="'+log.item_id+'" present_seq="'+log.present_seq+'">회수</button></td>' +
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

            $('#present_type').on('change', function() {
                let limitTypes = [2003, 2012, 2014, 2019];
                let presentType = $('#present_type option:selected').val();
                // 아이템별 지급 수량 제한
                if (limitTypes.indexOf(parseInt(presentType)) >= 0) {
                    $('#present_amount').val(1);
                }
            });

            $('#present_amount').on('keyup', function (){ $(this).next().text($(this).val() == '' ? '' : parseFloat($(this).val()).addComma(0)); });

            // action : 지급
            $('.give-btn').off('click').on('click', function() {
                // check common
                let userSeq = $('#user_seq').val();
                let logType = $('#log_type option:selected').val();
                let logReason = $('#log_reason').val();
                let actionType = 'give';
                let presentType = $('#present_type option:selected').val();
                let changeAmount = $('#present_amount').val();
                let limitTypes = [2003, 2012, 2014, 2019];

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    return false;
                }

                if (presentType.length === 0) {
                    alert('지급 상품을 선택하세요');
                    return false;
                }

                if (changeAmount.length === 0) {
                    alert('상품 수량을 입력하세요');
                }

                if (logType.length === 0) {
                    alert('처리사유(1)를 선택하세요');
                    return false;
                }

                if (logReason.length === 0) {
                    alert('처리사유(2)를 입력하세요.');
                    return false;
                }

                // 아이템별 지급 수량 제한
                if (limitTypes.indexOf(parseInt(presentType)) >= 0) {
                    $('#present_amount').val(1);
                    changeAmount = 1;
                }

                let params = {
                    userSeq: userSeq,
                    actionType: actionType,
                    logType: logType,
                    logReason: logReason,
                    presentType: presentType,
                    changeAmount: changeAmount,
                    presentSeq: -1,
                }
                ajaxPresents(params);
            });

        });

        // action : 회수
        $(document).on('click', '.btn-revoke', function() {
            // check common
            let userSeq = $('#user_seq').val();
            let actionType = 'revoke';
            let logType = $('#log_type option:selected').val();
            let logReason = $('#log_reason').val();
            let presentType = $(this).attr('present_type');
            let presentSeq = $(this).attr('present_seq');

            $('#tr_'+presentSeq).children('td, th').css('background', '#FFB6C1');

            if (userSeq.length === 0) {
                setTimeout(function() {
                    alert('먼저 사용자를 검색하세요.');
                    $('#tr_'+presentSeq).children('td, th').css('background', '#FFF');
                }, 300);
                return false;
            }

            if (logType.length === 0) {
                setTimeout(function() {
                    alert('처리사유(1)를 선택하세요');
                    $('#tr_'+presentSeq).children('td, th').css('background', '#FFF');
                }, 300);
                return false;
            }

            if (logReason.length === 0) {
                setTimeout(function() {
                    alert('처리사유(2)를 입력하세요');
                    $('#tr_'+presentSeq).children('td, th').css('background', '#FFF');
                }, 300);
                return false;
            }

            setTimeout(function() {
                if (!confirm('가방 지급/회수를 진행하시겠습니까?')) {
                    $('#tr_'+presentSeq).children('td, th').css('background', '#FFF');
                    return false;
                } else {
                    let params = {
                        userSeq: userSeq,
                        actionType: actionType,
                        logType: logType,
                        logReason: logReason,
                        presentType: presentType,
                        changeAmount: 0,
                        presentSeq: presentSeq,
                    }
                    ajaxPresents(params);
                }
            }, 300);
            // if (!confirm('가방 지급/회수를 진행하시겠습니까?')) {
            //     $('#tr_'+presentSeq).children('td, th').css('background', '#FFF');
            //     return false;
            // }

        });

        // Send
        function ajaxPresents(params) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/operation/editPresent',
                data: {
                    userSeq: params.userSeq,
                    actionType: params.actionType,
                    logType: params.logType,
                    logReason: params.logReason,
                    presentType: params.presentType,
                    changeAmount: params.changeAmount,
                    presentSeq: params.presentSeq,
                    from: 'present'
                },
                dataType: 'json',
                success: function(data) {
                    if(data.error === true) return;

                    $("#search-error-bag").hide();

                    alert('가방 지급/회수가 완료되었습니다.');
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

        function reset() {
            $('#present_type').val($("#present_type option:first").val());
            $('#present_amount').val('');

            $('#log_type').val($("#log_type option:first").val());
            $('#log_reason').val('');

            $('#log_table tr').children('td, th').css('background', '#FFF');
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
