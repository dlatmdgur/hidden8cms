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
                                    <h2>게임기록 상세</h2>
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
                            <div class="x_title">
                                <h2>게임 기록 상세 조회<small> 총 <span id="total"></span> 건,  검색기간 : {{ $startDate }} ~ {{ $endDate }}</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table table-bordered info-table sticky-table" id="all_game_table">
                                    <thead>
                                    <tr>
                                        <th class="sticky-header">No</th>
                                        <th class="sticky-header">게임명</th>
                                        <th class="sticky-header">종료시간</th>
                                        <th class="sticky-header">채널정보</th>
                                        <th class="sticky-header">회원정보</th>
                                        <th class="sticky-header">승패</th>
                                        <th class="sticky-header">기존머니</th>
                                        <th class="sticky-header">증감머니</th>
                                        <th class="sticky-header">보유머니</th>
                                        <th class="sticky-header">딜러비</th>
                                        <th class="sticky-header">최종상태</th>
                                        <th class="sticky-header">결과</th>
                                        <th class="sticky-header">배팅로그/디스커넥트</th>
                                        <th class="sticky-header">패킷로그</th>
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
    <input type="hidden" name="gameTypes" id="gameTypes" value="{{ $gameTypes }}" />
    <input type="hidden" name="betType" id="betType" value="{{ $betType }}" />
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
        const gameNames = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');
        const gameChannels = $.parseJSON('{!! json_encode(Helper::gameChannels()) !!}');
        const gameResults = $.parseJSON('{!! json_encode(Helper::gameResults()) !!}');
        const madeNames = $.parseJSON('{!! json_encode(Helper::madeNames()) !!}');

        let targetUser = null;
        let packetPopup = null;
        let bettingPopup = null;

        $(document).ready(function () {

            $('#closeDetail').off('click').on('click', function() {
                if (!confirm('게임 상세 기록 창을 닫으시겠습니까?')) {
                    return false;
                } else {
                    window.close();
                }
            });

            initLoad();
        });

        // packet log
        $(document).on('click', '.packetlog', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let logDate = $(this).attr('logDate');
            let gameType = $(this).attr('gameType');
            let channel = $(this).attr('channel');
            let roomId = $(this).attr('roomId');

            // post & new popup
            $.post('/game/packet', { logDate: logDate, gameType : gameType, channel: channel, roomId: roomId }, function(result) {
                // go detail game search : popup
                if (packetPopup !== null) {
                    packetPopup.close();
                }
                packetPopup = window.open("", "_packet", "toolbar=yes, scrollbars=yes, resizable=yes, top=150,left=150,width=1280, height=800");
                packetPopup.document.open()
                packetPopup.document.write(result);
                packetPopup.document.close();
            });
        });

        // betting log
        $(document).on('click', '.bettinglog', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let logDate = $(this).attr('logDate');
            let gameType = $(this).attr('gameType');
            let channel = $(this).attr('channel');
            let roomId = $(this).attr('roomId');
            let userSeq = $(this).attr('userSeq');

            // post & new popup
            $.post('/game/betting', { logDate: logDate, gameType : gameType, channel: channel, roomId: roomId, userSeq: userSeq }, function(result) {
                // go detail game search : popup
                if (bettingPopup !== null) {
                    bettingPopup.close();
                }
                bettingPopup = window.open("", "_betting", "toolbar=yes, scrollbars=yes, resizable=yes, top=250,left=550,width=1280, height=800");
                bettingPopup.document.open();
                bettingPopup.document.write(result);
                bettingPopup.document.close();
            });
        });

        function initLoad() {

            let userSeq = $('#userSeq').val();
            let gameTypes = $("#gameTypes").val();
            let betType = $("#betType").val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/game/detailExcel',
                data: {
                    userSeq: userSeq,
                    gameTypes: gameTypes,
                    betType: betType,
                    startDate: startDate,
                    endDate: endDate
                },
                dataType: 'json',
                success: function (data) {
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }

                    $("#search-error-bag").hide();

                    targetUser = data.targetUser.nickname;

                    let allGameLogs = data.allGameLogs;

                    // draw tables;
                    $('#total').text(allGameLogs.length);

                    let no = 0;
                    let prevUnique = 0;
                    let rows = '';
                    for (let i = 0; i < allGameLogs.length; i++) {
                        let log = allGameLogs[i];

                        if (prevUnique !== log.roomId) {
                            no++;
                        }
                        prevUnique = log.roomId;
                        let beforeMoney = parseInt(log.beforeMoney);
                        let afterMoney = parseInt(log.afterMoney);

                        let row = '<tr>' +
                            '<td class="room_idx">'+ no + ' <input type=hidden value="'+ log.roomId +'" /></td>' +
                            '<td class="room_game">'+ gameNames[log.gameType].name + ' <input type=hidden value="'+ log.roomId +'" /></td>' +
                            '<td class="room_logdate">'+ log.logDate + ' <input type=hidden value="'+ log.roomId +'" /></td>' +
                            '<td class="room_channel">'+ numberToKorean(gameChannels[log.gameType][log.channel].bet) + '<input type=hidden value="'+ log.roomId +'" /></td>' +
                            '<td class="nickname">'+ ( (log.nickname === targetUser)? ('<span style="color:blue; font-weight:bold;">' + targetUser + '</span>') : log.nickname ) + '<input type=hidden value="'+ log.roomId +'" /></td>' +
                            '<td>' + gameResults[log.gameResult] + '</td>' +
                            '<td class="number">'+ numberToKorean(beforeMoney)+'</td>' +
                            '<td class="number">'+ numberToKorean(log.changeMoney)+'</td>' +
                            '<td class="number">'+ numberToKorean(afterMoney)+'</td>';

                        if (log.gameType === 3) {
                            let baccaratBet = '';
                            if (parseInt(log.bet_player) > 0) {
                                baccaratBet += '<div style="margin:0; padding: 0; border-bottom: 1px solid #dee2e6; width:100%; height: 40px;line-height: 40px;">' +
                                    '<span style="display: inline-block; width: 70px; margin: 0; padding: 0 10px 0 0; border-right: 1px solid #dee2e6; text-align: right;">' +
                                    'Player(x1)</span>' +
                                    '<span style="display: inline-block; width: 80px; margin: 0; padding: 0 0 0 10px; text-align: right;">' +
                                    numberToKorean(log.bet_player)+'</span>'+
                                    '</div>';
                            }
                            if (parseInt(log.bet_banker) > 0) {
                                baccaratBet += '<div style="margin:0; padding: 0; border-bottom: 1px solid #dee2e6; width:100%; height: 40px;line-height: 40px;">' +
                                    '<span style="display: inline-block; width: 70px; margin: 0; padding: 0 10px 0 0; border-right: 1px solid #dee2e6; text-align: right;">' +
                                    'Banker(x1)</span>' +
                                    '<span style="display: inline-block; width: 80px; margin: 0; padding: 0 0 0 10px; text-align: right;">' +
                                    numberToKorean(log.bet_banker)+'</span>'+
                                    '</div>';
                            }
                            if (parseInt(log.bet_ppair) > 0) {
                                baccaratBet += '<div style="margin:0; padding: 0; border-bottom: 1px solid #dee2e6; width:100%; height: 40px;line-height: 40px;">' +
                                    '<span style="display: inline-block; width: 70px; margin: 0; padding: 0 10px 0 0; border-right: 1px solid #dee2e6; text-align: right;">' +
                                    'P.Pair(x11)</span>' +
                                    '<span style="display: inline-block; width: 80px; margin: 0; padding: 0 0 0 10px; text-align: right;">' +
                                    numberToKorean(log.bet_ppair)+'</span>'+
                                    '</div>';
                            }
                            if (parseInt(log.bet_tie) > 0) {
                                baccaratBet += '<div style="margin:0; padding: 0; border-bottom: 1px solid #dee2e6; width:100%; height: 40px;line-height: 40px;">' +
                                    '<span style="display: inline-block; width: 70px; margin: 0; padding: 0 10px 0 0; border-right: 1px solid #dee2e6; text-align: right;">' +
                                    'Tie(x8)</span>' +
                                    '<span style="display: inline-block; width: 80px; margin: 0; padding: 0 0 0 10px; text-align: right;">' +
                                    numberToKorean(log.bet_tie)+'</span>'+
                                    '</div>';
                            }
                            if (parseInt(log.bet_bpair) > 0) {
                                baccaratBet += '<div style="margin:0; padding: 0; border-bottom: 1px solid #dee2e6; width:100%; height: 40px;line-height: 40px;">' +
                                    '<span style="display: inline-block; width: 70px; margin: 0; padding: 0 10px 0 0; border-right: 1px solid #dee2e6; text-align: right;">' +
                                    'B.Pair(x11)</span>' +
                                    '<span style="display: inline-block; width: 80px; margin: 0; padding: 0 0 0 10px; text-align: right;">' +
                                    numberToKorean(log.bet_bpair)+'</span>'+
                                    '</div>';
                            }
                            // combine
                            row += '<td colspan="2" style="padding: 0;">' +
                                baccaratBet +
                                '</td>'+
                                '<td width="180">' +
                                '<div class="card_list">' +
                                '플레이어 : ' + trnasCardsFromJson(log.cardPlayer) + '<br>' +
                                '뱅커 : ' + trnasCardsFromJson(log.cardBanker) +
                                '</div>'+
                                '</td>'+
                                '<td class="number">' + ( (log.leave === 1)? '방나감' : '-' ) + '</td>';
                        } else if (log.gameType === 5) {
                            row += '<td class="number">'+ numberToKorean(log.dealerCharge)+'</td>' +
                                '<td>' + ( (log.highMadeList === "[]" && log.lowMadeList === "[]")? '다이' : '히든' ) + '</td>' +
                                '<td width="180">' +
                                '<div class="card_list">' +
                                '받은카드 : ' + trnasCardsFromJson(log.cardList) + '<br>' +
                                '하이 : ' + trnasCardsFromJson(log.highMadeList) + '<span class="card_num">(' +  madeNames[log.highMade] + ')</span><br>'+
                                '로우 : ' + trnasCardsFromJson(log.lowMadeList) + '<span class="card_num">(' +  madeNames[log.lowMade] + ')</span><br>'+
                                '</div>' +
                                '</td>'+
                                '<td class="room_betting"><button class="bettinglog primary" logDate="'+getDateNumber(log.logDate)+'" gameType="'+log.gameType+'" channel="'+log.channel+'" roomId="'+log.roomId+'" userSeq="'+log.userSeq+'">상세보기</button></td>';
                        } else {
                            row += '<td class="number">'+ numberToKorean(log.dealerCharge)+'</td>' +
                                '<td>' + ( (log.madeList === "[]")? '다이' : '히든' ) + '</td>' +
                                '<td width="180">' +
                                '<div class="card_list">' +
                                '받은카드 : ' + trnasCardsFromJson(log.cardList) + '<br>' +
                                '메이드 : ' + trnasCardsFromJson(log.madeList) + '<span class="card_num">(' +  madeNames[log.made] + ')</span><br>'+
                                '</div>' +
                                '</td>'+
                                '<td class="room_betting"><button class="bettinglog primary" logDate="'+getDateNumber(log.logDate)+'" gameType="'+log.gameType+'" channel="'+log.channel+'" roomId="'+log.roomId+'" userSeq="'+log.userSeq+'">상세보기</button></td>';
                        }

                        row += '<td class="room_packet"><div style="display: none;">'+ log.roomId +'</div><button class="packetlog primary" logDate="'+getDateNumber(log.logDate)+'" ' +
                            'gameType="'+log.gameType+'" channel="'+log.channel+'" roomId="'+log.roomId+'">상세보기</button></td>'+
                            '</tr>';

                        rows += row;
                    }
                    $('#all_game_table tbody').append(rows);

                    // mergeCells('all_game_table', 'room_idx');
                    // mergeCells('all_game_table', 'room_game');
                    // mergeCells('all_game_table', 'room_logdate');
                    // mergeCells('all_game_table', 'room_channel');
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
