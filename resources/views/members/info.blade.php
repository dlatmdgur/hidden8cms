@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.list') }}">사용자조회</a></li>
                <li class="breadcrumb-item">회원 정보</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>회원 정보</h2>
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
                                                        <input type="radio" class="flat" name="search_type" id="search_user_seq" value="userSeq" /> 회원번호 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="radio" class="flat" name="search_type" value="email" /> 이메일
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
                                                <th>EMAIL</th>
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
                                                <td id="member_join_type"></td>
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

                                <div class="x_panel" id="same_di_panel" style="display: none">
                                    <div class="x_title">
                                        <h2>동일 본인인증 계정<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table" id="same_di_table">
                                            <thead>
                                            <tr>
                                                <th>가입구분</th>
                                                <th>ID / Email</th>
                                                <th>인증일</th>
                                                <th>인증취소</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel" id="ban_panel" style="display: none">
                                    <div class="x_title">
                                        <h2>제재정보 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table" id="ban_log">
                                            <thead>
                                            <tr>
                                                <th>가입구분</th>
                                                <th>Email</th>
                                                <th>닉네임</th>
                                                <th>회원번호</th>
                                                <th>칩</th>
                                                <th>골드</th>
                                                <th>금고 칩</th>
                                                <th>금고 골드</th>
                                                <th>유료 보석</th>
                                                <th>무료 보석</th>
                                                <th>날짜</th>
                                                <th>제재 사유</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>기본게임정보 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">레벨</th>
                                                    <th rowspan="2">경험치</th>
                                                    <th colspan="4">머니정보</th>
                                                    <th rowspan="2">티켓이벤트</th>
                                                    <th rowspan="2">멤버스정보</th>
                                                    <th rowspan="2">게임명</th>
                                                    <th rowspan="2">최종게임일</th>
                                                    <th colspan="2">보유보석</th>
                                                </tr>
                                                <tr>
                                                    <th width="10%">칩</th>
                                                    <th width="10%">골드</th>
                                                    <th width="10%">칩 금고</th>
                                                    <th width="10%">골드 금고</th>
                                                    <th>유료</th>
                                                    <th>무료</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="gameinfo_level" class="number"></td>
                                                    <td id="gameinfo_exp" class="number"></td>
                                                    <td id="gameinfo_chip" class="number"></td>
                                                    <td id="gameinfo_gold" class="number"></td>
                                                    <td id="gameinfo_safe_chip" class="number"></td>
                                                    <td id="gameinfo_safe_gold" class="number"></td>
                                                    <td id="gameinfo_seed_ticket" class="number"></td>
                                                    <td id="gameinfo_members"></td>
                                                    <td style="padding: 0;">
                                                        <table class="innerTable">
                                                            <tr>
                                                                <td class="text-nowrap">7포커</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">바둑이</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">하이로우</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">바카라</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">홀덤</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">오마하</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">블랙잭</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-nowrap">홀덤바둑이</td>
                                                            </tr>
                                                        </table></td>
                                                    <td style="padding: 0;">
                                                        <table class="innerTable">
                                                            <tr>
                                                                <td id="gameinfo_last_seven" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_badugi" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_highlow" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_baccarat" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_texasholdem" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_omaha" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_blackjack" class="text-nowrap"> - </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="gameinfo_last_badugiholdem" class="text-nowrap"> - </td>
                                                            </tr>
                                                        </table></td>
                                                    <td id="gameinfo_gem" class="number"></td>
                                                    <td id="gameinfo_gem_event" class="number"></td>
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

    <input type="hidden" id="initUserSeq" val="{{ $userSeq }}" />

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
        const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');
        const levelsExp = $.parseJSON('{!! json_encode(Helper::levelsExp()) !!}');
        const membersInfo = $.parseJSON('{!! json_encode(Helper::membersInfo()) !!}');

        // console.log('stateNames', stateNames);
        // console.log('levelsExp', levelsExp);
        // console.log('membersInfo', membersInfo);

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
                        from: 'info'
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
                        let banInfo = data.banInfo;
                        let gameInfo = data.gameInfo;
                        let loginLog = data.loginLog;
                        let adminLog = data.adminLog;
                        let gameLog = data.gameLog;
                        let diMember = data.diMember;
                        let loginTypeName = data.loginTypeName;

                        let email_id = accountInfo.account;

                        let join_type = '구글';
                        if (accountInfo.login_type === '3') {
                            join_type = '플랫폼'
                        } else if (accountInfo.login_type === '4') {
                            join_type = '애플'
                        }

                        if(data.is_donk9) {
                            join_type += ' (동크나인)';
                        }

                        // user info
                        $('#user_seq').val(accountInfo.user_seq);       // for force close

                        $('#member_join_type').text(join_type);
                        $('#member_id_email').text(email_id);
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

                        //same di member
                        if(diMember) {
                            $('#same_di_panel').show();
                            let html = [];
                            $(diMember).each(function(idx, row) {
                                html.push('<tr>');
                                html.push('<td>');
                                html.push(loginTypeName[row.login_type] + '계정');
                                html.push('</td>');
                                html.push('<td>');
                                html.push(row.account);
                                html.push('</td>');
                                html.push('<td>' + row.date + '</td>');
                                html.push('<td><button class="btn btn-danger" onclick="delDi(' + row.user_seq + ');">취소</button></td>');
                                html.push('</tr>');
                                $('#same_di_table tbody').html(html.join(''));
                            });

                            delDi = function(user_seq) {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                $.ajax({
                                    type: 'POST',
                                    url: '/member/delDi',
                                    data: {
                                        user_seq: user_seq
                                    },
                                    dataType: 'json',
                                    success: function(data) {
                                        if(data.result)
                                        {
                                            $('#same_di_table').remove();
                                            $('#btn-search').click();
                                        }
                                    }
                                });
                            };
                        }

                        // user ban info
                        //console.log('banInfo', banInfo);
                        $('#ban_log tbody').empty();
                        if (banInfo !== undefined && banInfo.length > 0) {
                            $('#ban_panel').show();
                            // add row
                            $(banInfo).each(function(index, log) {
                                $('#ban_log tbody').append('<tr>' +
                                    '<td>' + join_type + '</td>' +
                                    '<td>' + log.account + '</td>' +
                                    '<td>' + log.nickname + '</td>' +
                                    '<td>' + log.user_seq + '</td>' +
                                    '<td>' + numberToKorean(log.chip) + '</td>' +
                                    '<td>' + numberToKorean(log.gold) + '</td>' +
                                    '<td>' + numberToKorean(log.safe_chip) + '</td>' +
                                    '<td>' + numberToKorean(log.safe_gold) + '</td>' +
                                    '<td>' + numberFormat(log.gem) + '</td>' +
                                    '<td>' + numberFormat(log.event_gem) + '</td>' +
                                    '<td>' + getFormattedDatetime(log.date) + '</td>' +
                                    '<td>' + log.comment + '</td>' +
                                '</tr>');
                            });
                        } else {
                            $('#ban_panel').hide();
                        }

                        // user detail info
                        // console.log('userInfo', userInfo);
                        $('#gameinfo_level').text(userInfo.level);
                        $('#gameinfo_exp').text(getPercent(levelsExp, userInfo.level, userInfo.exp));
                        $('#gameinfo_chip').text(numberToKorean(userInfo.chip));
                        $('#gameinfo_gold').text(numberToKorean(userInfo.gold));
                        $('#gameinfo_safe_chip').text(numberToKorean(userInfo.safe_chip));
                        $('#gameinfo_safe_gold').text(numberToKorean(userInfo.safe_gold));
                        $('#gameinfo_seed_ticket').text(userInfo.seed_ticket);
                        let members_name = "없음";
                        if (userInfo.members_type > 0) {
                            if (checkMembers(userInfo.members_start_date, userInfo.members_period) === true) {
                                members_name = membersInfo[userInfo.members_type].name +
                                    '<br><span style="color:red">(잔여기간 : '+ membersEndDays(userInfo.members_start_date, userInfo.members_period) +' 일)</span>' +
                                    '<br>(만료시간 : '+ membersEndDate(userInfo.members_start_date, userInfo.members_period) +')';
                            } else {
                                members_name = membersInfo[userInfo.members_type].name +
                                    '<br><span style="color:red">(만료됨)</span>' +
                                    '<br>(만료시간 : '+ membersEndDate(userInfo.members_start_date, userInfo.members_period) +')';
                            }
                        }
                        $('#gameinfo_members').html(members_name);
                        $('#gameinfo_gem').text(numberFormat(userInfo.gem));
                        $('#gameinfo_gem_event').text(numberFormat(userInfo.gem_event));

                        // find by game type
                        $(gameLog).each(function(index, game) {
                            if (game.hasOwnProperty('blackjack')) {
                                if (game.blackjack !== null) {
                                    $('#gameinfo_last_blackjack').text(getFormattedDatetime(game.blackjack.log_date));
                                } else {
                                    $('#gameinfo_last_blackjack').text('-');
                                }
                            }
                            if (game.hasOwnProperty('baccarat')) {
                                if (game.baccarat !== null) {
                                    $('#gameinfo_last_baccarat').text(getFormattedDatetime(game.baccarat.log_date));
                                } else {
                                    $('#gameinfo_last_baccarat').text('-');
                                }
                            }
                            if (game.hasOwnProperty('badugi')) {
                                if (game.badugi !== null) {
                                    $('#gameinfo_last_badugi').text(getFormattedDatetime(game.badugi.reg_date));
                                } else {
                                    $('#gameinfo_last_badugi').text('-');
                                }
                            }
                            if (game.hasOwnProperty('highlow')) {
                                if (game.highlow !== null) {
                                    $('#gameinfo_last_highlow').text(getFormattedDatetime(game.highlow.reg_date));
                                } else {
                                    $('#gameinfo_last_highlow').text('-');
                                }
                            }
                            if (game.hasOwnProperty('sevenpoker')) {
                                if (game.sevenpoker !== null) {
                                    $('#gameinfo_last_seven').text(getFormattedDatetime(game.sevenpoker.reg_date));
                                } else {
                                    $('#gameinfo_last_seven').text('-');
                                }
                            }
                            if (game.hasOwnProperty('texasholdem')) {
                                if (game.texasholdem !== null) {
                                    $('#gameinfo_last_texasholdem').text(getFormattedDatetime(game.texasholdem.reg_date));
                                } else {
                                    $('#gameinfo_last_texasholdem').text('-');
                                }
                            }
                            if (game.hasOwnProperty('omaha')) {
                                if (game.omaha !== null) {
                                    $('#gameinfo_last_omaha').text(getFormattedDatetime(game.omaha.reg_date));
                                } else {
                                    $('#gameinfo_last_omaha').text('-');
                                }
                            }
                            if (game.hasOwnProperty('badugiholdem')) {
                                if (game.badugiholdem !== null) {
                                    $('#gameinfo_last_badugiholdem').text(getFormattedDatetime(game.badugiholdem.reg_date));
                                } else {
                                    $('#gameinfo_last_badugiholdem').text('-');
                                }
                            }

                        });

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

            // initial load
            let initUserSeq = '{{ $userSeq }}';
            if (initUserSeq !== '') {
                $("#search_user_seq").iCheck('check');
                $("input:text[name='keyword']").val(initUserSeq);

                $('#btn-search').trigger('click');
            }
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
