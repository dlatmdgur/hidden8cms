@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.info') }}">게임조회</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.info') }}">게임정보 조회</a></li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>게임정보 조회</h2>
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
                                                        <input type="hidden" id="user_seq" />
                                                        <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                    </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="col-form-label">
                                                        <button type="button" id="btn-friends-search" class="btn btn-secondary">주간 동반 플레이어 보기</button>
                                                    </label>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>통합포커 게임정보<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="vertical-align: middle;">레벨</th>
                                                    <th rowspan="2" style="vertical-align: middle;">경험치</th>
                                                    <th colspan="4">머니정보</th>
                                                    <th colspan="2">리필정보</th>
                                                    <th rowspan="2" style="vertical-align: middle;">당월결제금액</th>
                                                    <th rowspan="2" style="vertical-align: middle;">타임보너스</th>
                                                    <th rowspan="2" style="vertical-align: middle;">무료동영상</th>
                                                    <th rowspan="2" style="vertical-align: middle;">최종로그인</th>
                                                </tr>
                                                <tr>
                                                    <th width="10%">칩</th>
                                                    <th width="10%">골드</th>
                                                    <th width="10%">칩 금고</th>
                                                    <th width="10%">골드 금고</th>
                                                    <th width="10%">칩</th>
                                                    <th width="10%">골드</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="userInfo_level"></td>
                                                    <td id="userInfo_exp"></td>
                                                    <td id="userInfo_chip" class="number"></td>
                                                    <td id="userInfo_gold" class="number"></td>
                                                    <td id="userInfo_safe_chip" class="number"></td>
                                                    <td id="userInfo_safe_gold" class="number"></td>
                                                    <td id="userInfo_refill_chip" class="number"></td>
                                                    <td id="userInfo_refill_gold" class="number"></td>
                                                    <td id="userInfo_monthly_billing_amount" class="number"></td>
                                                    <td id="userInfo_time_bonus"></td>
                                                    <td id="userInfo_free_video"></td>
                                                    <td id="userInfo_last_login"> - </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>전적조회 <small>누적</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table" id="all_game">
                                            <thead>
                                                <tr>
                                                    <th>게임명</th>
                                                    <th colspan="2">판수</th>
                                                    <th>승수</th>
                                                    <th>패수</th>
                                                    <th>승률</th>
                                                    <th>획득머니</th>
                                                    <th>올인횟수</th>
                                                    <th>최고연승횟수</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number"></td>
                                                    <td id="" class="number">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>전적조회 <small id="last_play_date">일별</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table class="table table-bordered info-table" id="daily_game">
                                            <thead>
                                            <tr>
                                                <th>게임명</th>
                                                <th colspan="2">판수</th>
                                                <th>승수</th>
                                                <th>패수</th>
                                                <th>승률</th>
                                                <th>획득머니</th>
                                                <th>올인횟수</th>
                                                <th>연승횟수</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="number">-</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>게임기록 상세 조회 <small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="col-form-label col-md-1 col-sm-1 label-align">
                                                    <span style="font-weight: bold;line-height: 35px; text-align: right;"> 조회 기간 : </span>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-2">
                                                    <button type="button" class="btn btn-primary period-selector" period="1" target="">1일</button> &nbsp;
                                                    <button type="button" class="btn btn-primary period-selector" period="3" target="">3일</button> &nbsp;
                                                    <button type="button" class="btn btn-success period-selector" period="7" target="">7일</button>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-2 label-align">
                                                    <div class="input-group date" id="datepicker">
                                                        <input type="text" class="form-control" id="search_start_date" />
                                                        <span class="input-group-addon" style="cursor:pointer;">
                                                            <i class="fa fa-calendar mt-1"></i>
                                                        </span>
                                                    </div>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-2 label-align">
                                                    <div class="input-group date" id="datepicker2">
                                                        <input type="text" class="form-control" id="search_end_date" />
                                                        <span class="input-group-addon" style="cursor:pointer;">
                                                            <i class="fa fa-calendar mt-1"></i>
                                                        </span>
                                                    </div>
                                                </label>

                                                <label class="col-form-label col-md-1 col-sm-1 label-align">
                                                    <span style="font-weight: bold;line-height: 24px; text-align: right;"> 채널 : </span>
                                                </label>
                                                <label class="col-form-label col-md-3 col-sm-3">
                                                    <input type="radio" class="flat" name="search_channel" value="all" checked="" required /> 전체
                                                    <input type="radio" class="flat" name="search_channel" value="chip" /> 칩 &nbsp;
                                                    <input type="radio" class="flat" name="search_channel" value="gold" /> 골드 &nbsp;
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="col-form-label col-md-1 col-sm-1 label-align">
                                                    <span style="font-weight: bold;line-height: 35px; text-align: right;">게임 선택 : </span>
                                                </label>
                                                <label class="col-form-label col-md-6 col-sm-6" style="line-height: 35px; font-size: 1rem">
                                                    <label class="col-form-label">
                                                        <input type="checkbox" id="check_all" name="gameType" value="all"> 전체 &nbsp; &nbsp; &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="checkbox" class="game_check" name="gameType" value="2" /> 블랙잭 &nbsp;
                                                    </label>
                                                    <label class="col-form-label">
                                                        <input type="checkbox" class="game_check" name="gameType" value="3" /> 바카라 &nbsp;
                                                    </label>
{{--                                                 <label class="col-form-label">
                                                        <input type="checkbox" class="game_check" name="gameType" value="4" /> 바둑이 &nbsp;
                                                    </label>--}}
{{--                                                    <label class="col-form-label">--}}
{{--                                                        <input type="checkbox" class="game_check" name="gameType" value="5" /> 하이로우 &nbsp;--}}
{{--                                                    </label>--}}
{{--                                                    <label class="col-form-label">--}}
{{--                                                        <input type="checkbox" class="game_check" name="gameType" value="6" /> 세븐포커 &nbsp;--}}
{{--                                                    </label>--}}
                                                    <label class="col-form-label">
                                                        <input type="checkbox" class="game_check" name="gameType" value="7" /> 홀덤 &nbsp;
                                                    </label>
{{--                                                    <label class="col-form-label">--}}
{{--                                                        <input type="checkbox" class="game_check" name="gameType" value="8" /> 다이사이 &nbsp;--}}
{{--                                                    </label>--}}
{{--                                                    <label class="col-form-label">--}}
{{--                                                        <input type="checkbox" class="game_check" name="gameType" value="9" /> 오마하 &nbsp;--}}
{{--                                                    </label>--}}
                                                    <label class="col-form-label">
                                                        <input type="checkbox" class="game_check" name="gameType" value="11" /> 홀덤바둑이 &nbsp;
                                                    </label>
                                                </label>

                                                <div class="col-md-4 col-sm-4" style="height: 62px; padding-top: 7px;">
                                                    <label class="col-form-label col-md-6 col-sm-6 label-align">
                                                        <button type="button" id="btn-detail-search" class="btn btn-secondary">게임기록 조회</button>
                                                    </label>
                                                    <!--<label class="col-form-label col-md-3 col-sm-3 label-align">
                                                        <button type="button" id="btn-detail-search3" class="btn btn-secondary">상세 new</button>
                                                    </label>-->
                                                    <label class="col-form-label col-md-6 col-sm-6 label-align">
                                                        <button type="button" id="btn-detail-excel" class="btn btn-secondary">엑셀 다운받기</button>
                                                    </label>
                                                    <!-- <label class="col-form-label col-md-3 col-sm-3 label-align">
                                                        <button type="button" id="btn-detail-search2" class="btn btn-secondary">게임 상세2</button>
                                                    </label> -->
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
    </div>
    <!-- SheetJS js-xlsx -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
    <!-- FileSaver savaAs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
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
        const gameTypes = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');

        const gameNames = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');
        const gameChannels = $.parseJSON('{!! json_encode(Helper::gameChannels()) !!}');
        const gameResults = $.parseJSON('{!! json_encode(Helper::gameResults()) !!}');
        const madeNames = $.parseJSON('{!! json_encode(Helper::madeNames()) !!}');

        let popup = null;
        let excelHandler;

        $(document).ready(function() {
            $('#search_start_date').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "locale": {
                    "format": "YYYY-MM-DD HH:mm:ss",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ date("Y-m-d") }} 00:00:00').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });
            $('#search_end_date').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "locale": {
                    "format": "YYYY-MM-DD HH:mm:ss",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ date("Y-m-d") }} 23:59:59').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });

            $('#search_input').on('keyup', function(e) {
                var keyCode = e.keyCode || e.which;
                e.preventDefault();
                if (keyCode === 13) {
                    $('#btn-search').trigger('click');
                }
            });

            $('#btn-search').off('click').on('click', function() {
                $('#search-errors').html('');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/game/search',
                    data: {
                        type: $(':input:radio[name=search_type]:checked').val(),
                        platform: $('select[name=login_type]').val(),
                        keyword: $(':input:text[name=keyword]').val(),
                        from: 'game'
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        let accountInfo = data.accountInfo;
                        let userInfo = data.userInfo;
                        let billing_limit = data.billing_limit;
                        let exp = (userInfo.level == 30)? 'Max' : getPercent(levelsExp, userInfo.level, userInfo.exp);

                        let blackjackGameInfo = data.blackjackGameInfo;
                        let baccaratGameInfo = data.baccaratGameInfo;
                        let badugiGameInfo = data.badugiGameInfo;
                        let highLowGameInfo = data.highLowGameInfo;
                        let sevenPokerGameInfo = data.sevenPokerGameInfo;
                        let texasHoldemGameInfo = data.texasHoldemGameInfo;
                        let taisaiGameInfo = data.taisaiGameInfo;
                        let omahaHoldemGameInfo = data.omahaHoldemGameInfo;
                        let badugiHoldemGameInfo = data.badugiHoldemGameInfo;

                        let dailyBlackjackInfo = data.dailyBlackjackInfo;
                        let dailyBaccaratInfo = data.dailyBaccaratInfo;
                        let dailyBadugiInfo = data.dailyBadugiInfo;
                        let dailyHighLowInfo = data.dailyHighLowInfo;
                        let dailySevenPokerInfo = data.dailySevenPokerInfo;
                        let dailyTexasHoldemInfo = data.dailyTexasHoldemInfo;
                        let dailyTaisaiInfo = data.dailyTaisaiInfo;
                        let dailyOmahaHoldemInfo = data.dailyOmahaHoldemInfo;
                        let dailyBadugiHoldemInfo = data.dailyBadugiHoldemInfo;

                        let loginLog = data.loginLog;
                        let freeChargeAdmob = data.freeChargeAdmob;
                        let members = membersInfo[userInfo.members_type];

                        // user info
                        $('#user_seq').val(accountInfo.user_seq);       // for force
                        $('#userInfo_level').text(userInfo.level);
                        $('#userInfo_exp').text(exp);
                        $('#userInfo_chip').text(numberToKorean(userInfo.chip));
                        $('#userInfo_gold').text(numberToKorean(userInfo.gold));
                        $('#userInfo_safe_chip').text(numberToKorean(userInfo.safe_chip));
                        $('#userInfo_safe_gold').text(numberToKorean(userInfo.safe_gold));
                        $('#userInfo_monthly_billing_amount').text(numberFormat(billing_limit));
                        $('#userInfo_refill_chip').text(userInfo.free_charge_count + ' / ' + members.refill_chip);
                        $('#userInfo_refill_gold').text(userInfo.free_charge_gold_count + ' / ' + members.refill_gold);
                        $('#userInfo_time_bonus').text(userInfo.timebonus_count + ' / ' + + members.time_bonus);
                        if (freeChargeAdmob !== null) {
                            $('#userInfo_free_video').text(freeChargeAdmob.count);
                        } else {
                            $('#userInfo_free_video').text('0');
                        }
                        if (loginLog !== null) {
                            $('#userInfo_last_login').text(getFormattedDatetime(loginLog.log_date));
                        } else {
                            $('#userInfo_last_login').text('-');
                        }

                        resetAll();

                        // all_game
                        $('#all_game tbody').empty();
                        $(blackjackGameInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;

                            $('#all_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#all_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        $(baccaratGameInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;

                            $('#all_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#all_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        $(badugiGameInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;

                            $('#all_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#all_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        // $(highLowGameInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;
                        //
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        // $(sevenPokerGameInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;
                        //
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        $(texasHoldemGameInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;

                            $('#all_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#all_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        // $(taisaiGameInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;
                        //
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        //
                        // $(omahaHoldemGameInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;
                        //
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#all_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        $(badugiHoldemGameInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.max_seq_win_count;

                            $('#all_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#all_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });


                        // daily
                        $('#daily_game tbody').empty();
                        $(dailyBlackjackInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;

                            $('#daily_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#daily_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        $(dailyBaccaratInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;

                            $('#daily_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#daily_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        $(dailyBadugiInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;

                            $('#daily_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#daily_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        // $(dailyHighLowInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;
                        //
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        // $(dailySevenPokerInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;
                        //
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        $(dailyTexasHoldemInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;

                            $('#daily_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#daily_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
                        });
                        // $(dailyTaisaiInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;
                        //
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        // $(dailyOmahaHoldemInfo).each(function(index, gamelog) {
                        //
                        //     let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                        //     let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                        //     let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                        //     let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                        //     let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;
                        //
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                        //         '<td>칩</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                        //         '<td>'+ chipWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                        //         '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                        //         '</tr>');
                        //     $('#daily_game tbody').append('<tr>' +
                        //         '<td>골드</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                        //         '<td>'+ goldWinRatio +'%</td>' +
                        //         '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                        //         '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                        //         '</tr>');
                        // });
                        $(dailyBadugiHoldemInfo).each(function(index, gamelog) {

                            let chipWinRatio = (parseInt(gamelog.chipPlayCount) === 0)? 0 : Math.round( gamelog.chipWinCount / gamelog.chipPlayCount * 100);
                            let goldWinRatio = (parseInt(gamelog.goldPlayCount) === 0)? 0 : Math.round( gamelog.goldWinCount / gamelog.goldPlayCount * 100);
                            let chipGainMoney = parseInt(gamelog.chipWinMoney) + parseInt(gamelog.chipLoseMoney);
                            let goldGainMoney = parseInt(gamelog.goldWinMoney) + parseInt(gamelog.goldLoseMoney);
                            let seqWinCount = ((parseInt(gamelog.chipPlayCount) + parseInt(gamelog.goldPlayCount)) === 0) ? 0 : gamelog.seq_win_count;

                            $('#daily_game tbody').append('<tr>' +
                                '<td rowspan="2">'+ gameTypes[gamelog.game_type].name +'</td>' +
                                '<td>칩</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipLoseCount) +'</td>' +
                                '<td>'+ chipWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(chipGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.chipAllinCount) +'</td>' +
                                '<td rowspan="2" class="number">'+ numberFormat(seqWinCount) +'</td>' +
                                '</tr>');
                            $('#daily_game tbody').append('<tr>' +
                                '<td>골드</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldPlayCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldWinCount) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldLoseCount) +'</td>' +
                                '<td>'+ goldWinRatio +'%</td>' +
                                '<td class="number">'+ numberToKorean(goldGainMoney) +'</td>' +
                                '<td class="number">'+ numberFormat(gamelog.goldAllinCount) +'</td>' +
                                '</tr>');
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

            // check all
            // $('#check_all').off('click').on('click', function() {
            //     $('.game_check').prop( 'checked', this.checked );
            // });

            //checkbox to radio
            $('input[name="gameType"]').on('click', function(){
                $('input[name="gameType"]').prop('checked', false);
                $(this).prop('checked', true);
            });

            // date search make period
            $('.period-selector').off('click').on('click', function () {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                fillSearchDateTime(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
            });

            // detail Search
            $('#btn-detail-search').off('click').on('click', function() {
                let userSeq = $('#user_seq').val();
                let period = $(':input:radio[name=search_period]:checked').val();
                let betType = $(':input:radio[name=search_channel]:checked').val();
                let gameTypes = $(':input:checkbox[name=gameType]:checked').map(function () {
                    return $(this).val();
                }).get();
                let allCheck = gameTypes.indexOf('all');
                let startDate = $('#search_start_date').val();
                let endDate = $('#search_end_date').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    $('#search_input').focus();
                    return false;
                }

                if (gameTypes.length === 0) {
                    alert('검색할 게임을 선택하세요');
                    return false;
                }

                if (allCheck >= 0) {
                    gameTypes = ['2', '3', '4', '5', '6', '7', '8', '9', '11'];
                }

                if (startDate.length === 0 || endDate.length === 0) {
                    alert('검색 기간을 선택하세요');
                    return false;
                }

                // $.post('/game/detail', { userSeq : userSeq, gameTypes : gameTypes, betType: betType, startDate: startDate, endDate: endDate }, function(result) {
                //     // go detail game search : popup
                //     if (popup !== null) {
                //         popup.close();
                //     }
                //     popup = window.open("", "_detail", "toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=0,width=1920, height=1080");
                //     popup.document.open();
                //     popup.document.write('');
                //     popup.document.write(result);
                //     popup.document.close();
                // });

                // popup = window.open("", "_detail", "toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=0,width=1920, height=1080");
                // let postData = { 'userSeq': userSeq, 'gameTypes': JSON.stringify(gameTypes), 'betType': betType, 'startDate': startDate, 'endDate': endDate };
                // postToUrl('/game/detail2', postData, '_detail', 'POST');

                popup = window.open("", "_detail", "toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=0,width=1920, height=1080");
                let postData = { 'userSeq': userSeq, 'gameTypes': JSON.stringify(gameTypes), 'betType': betType, 'startDate': startDate, 'endDate': endDate };
                postToUrl('/game/detail', postData, '_detail', 'POST');
            });

            // detail Search
            $('#btn-detail-search2').off('click').on('click', function() {

                let userSeq = $('#user_seq').val();
                let betType = $(':input:radio[name=search_channel]:checked').val();
                let gameTypes = $(':input:checkbox[name=gameType]:checked').map(function () {
                    return $(this).val();
                }).get();
                let allCheck = gameTypes.indexOf('all');
                let startDate = $('#search_start_date').val();
                let endDate = $('#search_end_date').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    $('#search_input').focus();
                    return false;
                }

                if (gameTypes.length === 0) {
                    alert('검색할 게임을 선택하세요');
                    return false;
                }

                if (allCheck >= 0) {
                    gameTypes = ['2', '3', '4', '5', '6', '7', '8', '9', '11'];
                }

                if (startDate.length === 0 || endDate.length === 0) {
                    alert('검색 기간을 선택하세요');
                    return false;
                }

                popup = window.open("", "_detail2", "toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=0,width=1920, height=1080");
                let postData = { 'userSeq': userSeq, 'gameTypes': JSON.stringify(gameTypes), 'betType': betType, 'startDate': startDate, 'endDate': endDate };
                postToUrl('/game/detail2', postData, '_detail2', 'POST');

            });

            $('#btn-detail-search3').off('click').on('click', function() {

                let userSeq = $('#user_seq').val();
                let betType = $(':input:radio[name=search_channel]:checked').val();
                let gameTypes = $(':input:checkbox[name=gameType]:checked').map(function () {
                    return $(this).val();
                }).get();
                let allCheck = gameTypes.indexOf('all');
                let startDate = $('#search_start_date').val();
                let endDate = $('#search_end_date').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    $('#search_input').focus();
                    return false;
                }

                if (gameTypes.length === 0) {
                    alert('검색할 게임을 선택하세요');
                    return false;
                }

                if (allCheck >= 0) {
                    gameTypes = ['2', '3', '4', '5', '6', '7', '8', '9', '11'];
                }

                if (startDate.length === 0 || endDate.length === 0) {
                    alert('검색 기간을 선택하세요');
                    return false;
                }

                popup = window.open("", "_detail3", "toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=0,width=1920, height=1080");
                let postData = { 'userSeq': userSeq, 'gameTypes': JSON.stringify(gameTypes), 'betType': betType, 'startDate': startDate, 'endDate': endDate };
                postToUrl('/game/detail3', postData, '_detail3', 'POST');

            });

            // detail Excel
            $('#btn-detail-excel').off('click').on('click', function() {

                let userSeq = $('#user_seq').val();
                let betType = $(':input:radio[name=search_channel]:checked').val();
                let gameTypes = $(':input:checkbox[name=gameType]:checked').map(function () {
                    return $(this).val();
                }).get();
                let allCheck = gameTypes.indexOf('all');
                let startDate = $('#search_start_date').val();
                let endDate = $('#search_end_date').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    $('#search_input').focus();
                    return false;
                }

                if (gameTypes.length === 0) {
                    alert('검색할 게임을 선택하세요');
                    return false;
                }

                if (allCheck >= 0) {
                    gameTypes = ['2', '3', '4', '5', '6', '7', '8', '9', '11'];
                }

                if (startDate.length === 0 || endDate.length === 0) {
                    alert('검색 기간을 선택하세요');
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '/game/detailExcel',
                    data: {
                        userSeq: userSeq,
                        gameTypes: JSON.stringify(gameTypes),
                        betType: betType,
                        startDate: startDate,
                        endDate: endDate
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log('forExcel',data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        // export Excel File

                        let wb = XLSX.utils.book_new();
                        wb.Props = {
                            Title: "Game Info Detail",
                            Subject: 'Game Info Detail : ' + startDate + ' ~ ' + endDate,
                            Author: "CMS SuperWinGame",
                            CreatedDate: new Date()
                        };

                        let excel_data = [];
                        excel_data.push(['No', '게임', '방고유번호', '종료시간', '채널정보', '회원번호', '회원닉네임', '승패', '기존머니', '증감머니', '보유머니', '딜러비', '최종상태', '결과']);   // header

                        let allGameLogs = data.allGameLogs;
                        let no = 0;
                        let prevUnique = 0;

                        for (let i = 0; i < allGameLogs.length; i++) {
                            let log = allGameLogs[i];
                            if (prevUnique !== log.roomId) {
                                no++;
                            }
                            prevUnique = log.roomId;
                            let beforeMoney = parseInt(log.beforeMoney);
                            let afterMoney = parseInt(log.afterMoney);

                            let col_data = [
                                no,
                                gameNames[log.gameType].name,
                                log.roomId,
                                log.logDate,
                                numberFormat(gameChannels[log.gameType][log.channel].bet),
                                log.userSeq,
                                log.nickname,
                                gameResults[log.gameResult],
                                numberFormat(log.beforeMoney),
                                numberFormat(log.changeMoney),
                                numberFormat(log.afterMoney),
                            ];
                            let result;
                            if (log.gameType === 2) {
                                result = ''
                                let blackjackBet = '';
                                if (parseInt(log.totalBet) > 0) {
                                    blackjackBet += ' Total Bet : ' + numberFormat(log.totalBet) + '\n';
                                }
                                if (parseInt(log.pairBet) > 0) {
                                    blackjackBet += ' Pair Bet : ' + numberFormat(log.pairBet) + '\n';
                                }
                                if (parseInt(log.insuranceBet) > 0) {
                                    blackjackBet += ' Insurance Bet : ' + numberFormat(log.insuranceBet) + '\n';
                                }

                                let game0 = (log.game0 !== '')? $.parseJSON(log.game0) : null;
                                result = blackjackBet + '\n game0 : ' + transCards(game0.m_Hands);
                                if (log.game1 !== '') {
                                    let game1 = (log.game1 !== '')? $.parseJSON(log.game1) : null;
                                    result += '\n game1 : ' + transCards(game1.m_Hands);
                                }
                                if (log.game2 !== '') {
                                    let game2 = (log.game2 !== '')? $.parseJSON(log.game2) : null;
                                    result += '\n game2 : ' + transCards(game2.m_Hands);
                                }
                                if (log.game3 !== '') {
                                    let game3 = (log.game3 !== '')? $.parseJSON(log.game3) : null;
                                    result += '\n game3 : ' + transCards(game3.m_Hands);
                                }
                                if (log.dealerGame !== '') {
                                    let dealer = (log.dealerGame !== '')? $.parseJSON(log.dealerGame) : null;
                                    result += '\n 딜러카드 : ' + transCards(dealer);
                                }
                                let lastState = (log.leave === 1)? '방나감' : '-';

                                col_data.push(0);   // no dealer charge
                                col_data.push(lastState);
                                col_data.push(result);

                            } else if (log.gameType === 3) {
                                result = ''
                                let baccaratBet = '';
                                if (parseInt(log.bet_player) > 0) {
                                    baccaratBet += 'Player(x1) : ' + numberFormat(log.bet_player) + '\n';
                                }
                                if (parseInt(log.bet_banker) > 0) {
                                    baccaratBet += 'Banker(x1) : ' + numberFormat(log.bet_banker) + '\n';
                                }
                                if (parseInt(log.bet_ppair) > 0) {
                                    baccaratBet += 'P.Pair(x11) : ' + numberFormat(log.bet_ppair) + '\n';
                                }
                                if (parseInt(log.bet_tie) > 0) {
                                    baccaratBet += 'P.Tie(x8) : ' + numberFormat(log.bet_tie) + '\n';
                                }
                                if (parseInt(log.bet_bpair) > 0) {
                                    baccaratBet += 'B.Pair(x8) : ' + numberFormat(log.bet_bpair) + '\n';
                                }
                                result = baccaratBet +
                                        '플레이어 : ' + trnasCardsFromJson(log.cardPlayer) + '\n' +
                                        '뱅커 : ' + trnasCardsFromJson(log.cardBanker);
                                let lastState = (log.leave === 1)? '방나감' : '-';

                                col_data.push(0);   // no dealer charge
                                col_data.push(lastState);
                                col_data.push(result);

                            } else if (log.gameType === 5) {
                                let lastState = (log.highMadeList === "[]" && log.lowMadeList === "[]")? '다이' : '히든';
                                let result = '받은카드 : ' + trnasCardsFromJson(log.cardList) + '\n' +
                                             '하이 : ' + trnasCardsFromJson(log.highMadeList) + ' (' +  madeNames[log.highMade] + ') \n'+
                                             '로우 : ' + trnasCardsFromJson(log.lowMadeList) + ' (' +  madeNames[log.lowMade] + ') \n';

                                col_data.push(numberFormat(log.dealerCharge));
                                col_data.push(lastState);
                                col_data.push(result);
                            } else {
                                let lastState = (log.madeList === "[]")? '다이' : '히든';
                                let result = '받은카드 : ' + trnasCardsFromJson(log.cardList) + '\n' +
                                             '메이드 : ' + trnasCardsFromJson(log.madeList) + ' (' +  madeNames[log.made] + ') \n';

                                col_data.push(numberFormat(log.dealerCharge));
                                col_data.push(lastState);
                                col_data.push(result);
                            }
                            excel_data.push(col_data);
                        }

                        wb.SheetNames.push("Detail");
                        ws = XLSX.utils.aoa_to_sheet(excel_data);
                        wb.Sheets["Detail"] = ws;
                        wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
                        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'game_detail.xlsx');
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


            // 동반 플레이어 조회
            $('#btn-friends-search').off('click').on('click', function() {
                let userSeq = $('#user_seq').val();

                if (userSeq.length === 0) {
                    alert('먼저 사용자를 검색하세요.');
                    $('#search_input').focus();
                    return false;
                }

                popup = window.open("", "_friends", "toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=0,width=1920, height=1080");
                let postData = { 'userSeq': userSeq };
                postToUrl('/game/friends', postData, '_friends', 'POST');
            });


        });

        function resetAll() {
            $('#check_all').prop( 'checked', false );
            $('.game_check').prop( 'checked', false );
        }

        function exportExcel(wb, data) {
            wb.SheetNames.push("Detail");
            ws = XLSX.utils.aoa_to_sheet(data);
            wb.Sheets["Detail"] = ws;
            wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
            saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'game_detail.xlsx');
        }

        function s2ab(s) {
            let buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
            let view = new Uint8Array(buf);  //create uint8array as viewer
            for (let i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
            return buf;
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
