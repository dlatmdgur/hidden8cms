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
                                    <h2>베팅 로그</h2>
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


                        @if (!is_null($packetLogs))
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>{{ Helper::gameName($gameType) }} 베팅요약정보<small> 채널 : {{ Helper::channelName($gameType, $channel) }}, Room ID : {{ $roomId }}</small></h2>
                                    <ul class="nav navbar-right panel_toolbox" style="min-width: 30px">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table class="table table-bordered info-table" id="summary_info">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>회원번호</th>
                                            <th>닉네임</th>
                                            <th>유저게임머니</th>
                                            <th>배팅금액</th>
                                            <th>이전배팅금액</th>
                                            <th>전체배팅금액</th>
                                            <th>로그날짜</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>{{ Helper::gameName($gameType) }} 게임패킷<small> 채널 : {{ Helper::channelName($gameType, $channel) }}, Room ID : {{ $roomId }}</small></h2>
                                    <ul class="nav navbar-right panel_toolbox" style="min-width: 30px">
                                        <li><a class="collapse-link" id="logPannelBtn"><i class="fa fa-chevron-down"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content collapse">
                                    <table class="table table-bordered info-table" id="baccarat_table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>회원번호</th>
                                            <th>닉네임</th>
                                            <th>Room ID</th>
                                            <th>Packet Category</th>
                                            <th>Packet Index</th>
                                            <th>Packet Struct</th>
                                            <th>Server Type</th>
                                            <th>로그날짜</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $no = 0;
                                        @endphp
                                        @foreach ($packetLogs as $log)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $log->user_seq }}</td>
                                                <td>{{ $log->nickname }}</td>
                                                <td>{{ $log->room_id }}</td>
                                                <td>{{ $log->packet_category }}</td>
                                                <td class="packet_index" target="json_{{ $log->log_seq }}" userSeq="{{ $log->user_seq }}" nickname="{{ $log->nickname }}" logDate="{{ $log->reg_date }}">{{ $log->packet_index }}</td>
                                                <td style="word-break:break-all;text-align: left;">
                                                    <pre id="packet_{{ $log->log_seq }}"></pre>
                                                    <textarea class="jsons" id="json_{{ $log->log_seq }}" target="packet_{{ $log->log_seq }}" style="display: none">{{ $log->packet_struct }}</textarea>
                                                    {{--<textarea class="jsons" target="packet_{{ $log->log_seq }}" style="width: 0; height: 0;">{{ json_encode(json_decode($log->packet_struct, true), JSON_PRETTY_PRINT) }}</textarea>--}}
                                                </td>
                                                <td>{{ $log->server_type }}</td>
                                                <td>{{ $log->reg_date }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jsonBrowse -->
    <link href="/vendors/json-browse/jquery.json-browse.css" rel="stylesheet">
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
        .info-table td { text-align: center; vertical-align: middle; overflow: hidden; }
        .info-table td.number { text-align: right; }
    </style>

    <script>
        $(document).ready(function () {

            $('#closeDetail').off('click').on('click', function() {
                if (!confirm('게임 패킷 로그 창을 닫으시겠습니까?')) {
                    return false;
                } else {
                    window.close();
                }
            });

            $('.jsons').each(function() {
                let input = eval('(' + $(this).val() + ')');
                let target = $(this).attr('target');
                let options = {
                    collapsed: true,
                    withQuotes: false
                };
                $('#'+target).jsonBrowse(input, options);
            });

            let betNo = 0;
            let prevBetId = null;
            let prevTotalBetMoney = 0;
            $('.packet_index').each(function() {
                let checkIndex = $(this).text();
                let userSeq = $(this).attr('userSeq');
                let nickname = $(this).attr('nickname');
                let logDate = $(this).attr('logDate');

                // 결과
                if ( checkIndex === 'RESULT_SYN') {
                    $(this).css("color", "red");
                    $(this).css("font-weight", "bold");
                }

                // 배팅
                if ( checkIndex === 'UPDATE_ROOM_STATE_SYN' || checkIndex === 'G2C_UPDATE_ROOM_STATE_NTF') {
                    // check json has property
                    let target = $(this).attr('target');
                    let json = null;
                    try {
                        json = eval('(' + $('#'+target).val() + ')');
                    } catch (e) {
                        return;
                    }

                    if (json !== null) {
                        if (json.hasOwnProperty('m_BettingData')) {
                            if (json['m_BettingData'] !== null) {
                                let bettingLog = json['m_BettingData'];
                                let bettingId = json['m_TurnEndTime'];
                                let totalBetMoney = json['m_TotalBetMoney'];

                                if (bettingId !== prevBetId) {
                                    prevBetId = bettingId;
                                    $(this).css("color", "orange");
                                    $(this).css("font-weight", "bold");

                                    $('#summary_info tbody').append('<tr>' +
                                        '<td>' + (++betNo) + '</td>' +
                                        '<td>' + userSeq + '</td>' +
                                        '<td>' + nickname + '</td>' +
                                        '<td class="number">' + numberToKorean(bettingLog.m_UserGameMoney) + '</td>' +
                                        '<td class="number">' + numberToKorean(bettingLog.m_UserBetMoney) + '</td>' +
                                        '<td class="number">' + numberToKorean(totalBetMoney) + '</td>' +
                                        '<td class="number">' + numberToKorean(totalBetMoney + bettingLog.m_UserBetMoney) + '</td>' +
                                        '<td>' + logDate + '</td>' +
                                        '</tr>');
                                }
                            }
                        }
                    }
                }
            });

            $('#logPannelBtn').trigger('click');
        });
    </script>

    <!-- jsonBrowse -->
    <script src="/vendors/json-browse/jquery.json-browse.js"></script>


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
