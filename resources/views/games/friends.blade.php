@extends('layouts.popuplayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-10 margin-tb">
                                <div class="pull-left">
                                    <h2>주간 동반 플레이어 머니 조회</h2>
                                </div>
                            </div>
                            <div class="col-lg-2 margin-tb">
                                <div class="pull-right">
                                    <button id="closeDetail" class="btn btn-secondary btn-xs">닫기</button>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif


                        <div class="x_panel">
                            <div class="x_title input-group">
                                <h2 class="mt-2">조회 유저 정보 <small></small></h2>
                                <div class="clearfix"></div>
                                <div class="col-3">
                                    <select class="form-control" id="game_type" name="game_type">
                                        <option value="">전체 게임</option>
                                        <option value="4">바둑이</option>
                                        <option value="6">세븐포커</option>
                                        <option value="7">홀덤</option>
                                        <option value="9">오마하</option>
                                    </select>
                                </div>
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
                                        <th>보유골드</th>
                                        <th>상태</th>
                                    </tr>
                                    <tr>
                                        <td id="member_id_email"></td>
                                        <td id="member_nickname"></td>
                                        <td id="member_seq"></td>
                                        <td id="member_remote_address"></td>
                                        <td id="member_created_date"></td>
                                        <td id="member_last_login_date"></td>
                                        <td id="member_gold"></td>
                                        <td id="member_status"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="x_panel">
                            <div class="x_title">
                                <h2>일별 동반 플레이어 머니 조회<small> 총 <span id="total"></span> 명,  검색기간 : {{ $startDate }} ~ {{ $endDate }}</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table table-bordered info-table sticky-table" id="friends_table">
                                    <thead>
                                    <tr>
                                        <th class="sticky-header">No</th>
                                        <th class="sticky-header">날짜</th>
                                        <th class="sticky-header">게임종류</th>
                                        <th class="sticky-header">채널정보</th>
                                        <th class="sticky-header">회원정보</th>
                                        <th class="sticky-header">증감머니</th>
                                        <th class="sticky-header">현재보유머니</th>
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

    <input type="hidden" name="userSeq" id="userSeq" value="{{ $userSeq }}" />
    <input type="hidden" name="startDate" id="startDate" value="{{ $startDate }}" />
    <input type="hidden" name="endDate" id="endDate" value="{{ $endDate }}" />

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

        .sticky-table { position: relative; }
        .sticky-header {
            background: white;
            position: sticky;
            top: 0;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        }
        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
        .card_td { width: 280px; }
        .card_list { margin: 0; border: 0; width: 280px; line-height: 24px; text-align: left; overflow: hidden; }
        .card_td_small { width: 200px; }
        .card_list_small { margin: 0; border: 0; width: 200px; line-height: 24px; text-align: left; overflow: hidden; }
        .card_num { color: red; font-weight: bold; }

        .innerTable { width: 100%; }
        .innerTable > tbody > tr:first-child > td { border-top: 0; }
        .innerTable > tbody > tr:last-child > td { border-bottom: 0; }
        .innerTable td { border-left: 0; }
        .innerTable td.number { text-align: right; }
        .innerTable > tbody > tr > td:last-child { border-left: 0; border-right: 0; }
    </style>

    <script>
        const stateNames = $.parseJSON('{!! json_encode(Helper::userStates()) !!}');
        const gameNames = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');
        const gameChannels = $.parseJSON('{!! json_encode(Helper::gameChannels()) !!}');
        const gameResults = $.parseJSON('{!! json_encode(Helper::gameResults()) !!}');
        let targetGame = $('#game_type').val();

        let targetUser = null;

        $(document).ready(function () {

            $('#closeDetail').off('click').on('click', function() {
                if (!confirm('동반 플레이어 기록 창을 닫으시겠습니까?')) {
                    return false;
                } else {
                    window.close();
                }
            });

            $('#game_type').change(function () {
                targetGame = $('#game_type').val();

                console.log('select game', targetGame);
                initLoad();
            });

            initLoad();
        });


        function initLoad() {

            let userSeq = $('#userSeq').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/game/friendsLogs',
                data: {
                    userSeq: userSeq,
                    startDate: startDate,
                    endDate: endDate,
                    from: 'friends'
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }

                    $("#search-error-bag").hide();

                    let accountInfo = data.accountInfo;
                    let userInfo = data.userInfo;
                    let loginLog = data.loginLog;

                    $('#member_id_email').text(accountInfo.google_email);
                    $('#member_nickname').text(accountInfo.nickname);
                    $('#member_seq').text(accountInfo.user_seq);
                    $('#member_remote_address').text(loginLog.ip);
                    $('#member_created_date').text(userInfo.reg_date);
                    $('#member_last_login_date').text(loginLog.log_date);
                    $('#member_gold').text( numberFormat(parseInt(userInfo.gold) + parseInt(userInfo.safe_gold)) );
                    $('#member_status').text( stateNames[accountInfo.user_state] );


                    let frinedsLogs = data.friendsLogs;

                    $('#total').text(frinedsLogs.length);

                    let no = frinedsLogs.length;
                    $('#friends_table tbody').empty();
                    $(frinedsLogs).each(function (index, log) {
                        console.log(targetGame, log.game_type);
                        if (targetGame !== '' && targetGame !== log.game_type) {
                            return true;
                        }

                        let row =
                            '<tr>' +
                                '<td class="number">'+ no +'</td>' +
                                '<td>'+ log.log_date +'</td>' +
                                '<td>'+ gameNames[log.game_type].name +'</td>' +
                                '<td>'+ gameChannels[log.game_type][log.channel].name +'</td>' +
                                '<td>'+ log.nickname +'</td>' +
                                '<td class="number">'+ numberFormat(log.change_money) +'</td>' +
                                '<td class="number">'+ numberFormat(log.user_gold) +'</td>' +
                            '</tr>';


                        $('#friends_table tbody').append(row);
                        no--;
                    });


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
