@extends('layouts.mainlayout')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('managements.version') }}">서버설정</a></li>
                <li class="breadcrumb-item"><a href="{{ route('managements.tournament') }}">토너먼트</a></li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>토너먼트 조회</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <form name="frmSearch" method="get" action="{{ route('managements.tournament') }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="col-form-label col-md-1 col-sm-1 label-align">
                                        <span style="font-weight: bold;line-height: 40px; text-align: right;"> 조회 기간 : </span>
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2">
                                        <button type="button" class="btn btn-primary period-selector" period="1" target="">1일</button>
                                        <button type="button" class="btn btn-primary period-selector" period="3" target="">3일</button>
                                        <button type="button" class="btn btn-success period-selector" period="7" target="">7일</button>
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2 label-align">
                                        <div class="input-group date" id="datepicker1">
                                            <input type="text" name="search_start_date" class="form-control">
                                            <span class="input-group-addon" style="cursor:pointer;">
                                                <i class="fa fa-calendar mt-1"></i>
                                            </span>
                                        </div>
                                    </label>
                                    <label class="col-form-label col-md-2 col-sm-2 label-align">
                                        <div class="input-group date" id="datepicker2">
                                            <input type="text" name="search_end_date" class="form-control">
                                            <span class="input-group-addon" style="cursor:pointer;">
                                                <i class="fa fa-calendar mt-1"></i>
                                            </span>
                                        </div>
                                    </label>
                                    <div class="col-md-4 col-sm-4">
                                        <label class="col-form-label col-md-2 col-sm-2" style="line-height: 40px;">
                                            <input type="radio" class="flat" name="search_money" value="all"{{ $search['money'] == 'all' ? ' checked' : ''}}> 전체
                                        </label>
                                        <label class="col-form-label col-md-2 col-sm-2" style="line-height: 40px;">
                                            <input type="radio" class="flat" name="search_money" value="2016"{{ $search['money'] == '2016' ? ' checked' : ''}}> 칩모드
                                        </label>
                                        <label class="col-form-label col-md-3 col-sm-3" style="line-height: 40px;">
                                            <input type="radio" class="flat" name="search_money" value="2018"{{ $search['money'] == '2018' ? ' checked' : ''}}> 골드모드
                                        </label>
                                        <div class="col-md-5 col-sm-5"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="col-form-label col-md-1 col-sm-1 label-align">
                                        <span style="font-weight: bold;line-height: 40px; text-align: right;">토너먼트 이름 : </span>
                                    </label>
                                    <label class="col-form-label col-md-6 col-sm-6">
                                        <input type="text" name="search_name" class="form-control" value="{{ $search['name'] }}">
                                    </label>

                                    <div class="col-md-4 col-sm-4">
                                        <label class="col-form-label col-md-2 col-sm-2">
                                            <button type="button" name="searchTournament" class="btn btn-secondary">조회</button>
                                        </label>
                                        <label class="col-form-label col-md-4 col-sm-4">
                                            <button type="button" name="excelTournament" class="btn btn-secondary">엑셀 다운받기</button>
                                        </label>
                                        <label class="col-form-label col-md-2 col-sm-2">
                                            <button type="button" name="regTournament" class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#cpModal">등록</button>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="x_panel">
                        <div class="x_content">
                            <table class="table table-bordered info-table">
                                <thead>
                                <tr align="center">
                                    <th>No.</th>
                                    <th>토너먼트 이름</th>
                                    <th>시작일</th>
                                    <th>상태</th>
                                    <th>현재 등록인원</th>
                                    <th>최소/최대 인원</th>
                                    <th>게런티</th>
                                    <th>리바이</th>
                                    <th>리엔트리</th>
                                    <th>애드온</th>
                                    <th>레벨업(분)</th>
                                    <th>시작칩</th>
                                    <th>바이인</th>
                                    <th>총 바이인 금액</th>
                                    <th>옵션</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $key => $row)
                                <tr align="center">
                                    <td>{{ $numberStart-- }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->start_date }}</td>
                                    <td>{{ Helper::getTournamentStatus($row->status) }}</td>
                                    <td>{{ $row->people }}</td>
                                    <td>{{ $row->entry_min }} / {{ $row->entry_max }}</td>
                                    @if($row->reward_type == 1)
                                    <td>{{ Helper::numberToKorean(max($row->reward, $row->total_buyin)) }}{{ $row->money == '2018' ? '골드' : '칩' }}</td>
                                    @else
                                    <td>{{ $row->reward }}</td>
                                    @endif
                                    <td>{{ $row->re_buy }}</td>
                                    <td>{{ $row->re_entry }}</td>
                                    <td>{{ $row->addon }}</td>
                                    <td>{{ $row->blind_lvup_time }}</td>
                                    <td>{{ Helper::numberToKorean($row->buyin_offer_chip) }}</td>
                                    <td>{{ Helper::numberToKorean($row->buyin_cash) }}{{ $row->money == '2018' ? '골드' : '칩' }}</td>
                                    <td>{{ Helper::numberToKorean($row->total_buyin) }}{{ $row->money == '2018' ? '골드' : '칩' }}</td>
                                    <td>
                                        <button name="detailTournament" class="btn btn-secondary" data-i="{{ $row->tid }}">상세보기</button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="x_content">
                            <ul class="pagination justify-content-center">{{ $records->links() }}</ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="cpModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form name="frmSet" method="post" action="/tournament/set_scheduler" onsubmit="return false;" class="m-0">
                    <div class="modal-header"><h5 class="modal-title">토너먼트 등록</h5></div>
                    <div class="modal-body">
                        <table class="table table-sm font-sm">
                            <colgroup>
                                <col width="15%">
                                <col width="33%">
                                <col width="4%">
                                <col width="15%">
                                <col width="33%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <th class="table-light align-middle">토너먼트 이름</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="title" class="form-control form-control-sm col-12" maxlength="30"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">재화</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <input type="radio" name="money" value="2016" checked="checked"> 칩모드
                                            <input type="radio" name="money" value="2018"> 골드모드
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">시작일</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="start_date" class="form-control form-control-sm col-12" value="{{ $now }}"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">테이블당 인원</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="player_cnt" class="form-control form-control-sm col-12" value="9" readonly></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">최소인원</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="entry_min" value="" class="form-control form-control-sm col-12" placeholder="최소 6명"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">최대인원</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="entry_max" value="" class="form-control form-control-sm col-12" placeholder="최대 1000명"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">바이인</th>
                                <td>
                                    <div class="row">
                                        <div class="col-8"><input type="number" name="buyin_cash" class="form-control form-control-sm col-12" placeholder="참가비"></div>
                                        <div class="col-4 mt-2"><input type="checkbox" name="free_role"> 프리롤</div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">시작칩</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="buyin_offer_chip" class="form-control form-control-sm col-12" placeholder="토너먼트 시작 칩"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">블라인드타입</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="number" name="blind_type" class="form-control form-control-sm col-12" placeholder="블라인드타입 입력">
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">레벨업 시간</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="blind_lvup_time" class="form-control form-control-sm col-12" placeholder="레벨업 시간(분)"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">브레이크 시간/주기</th>
                                <td>
                                    <div class="row">
                                        <div class="col-6"><input type="number" name="break_time" class="form-control form-control-sm col-12" placeholder="브레이크 시간(분)"></div>
                                        <div class="col-6">
                                            <input type="number" name="break_time_cycle" class="form-control form-control-sm col-12" placeholder="브레이크 주기(Lev)">
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">리바이</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="re_buy" class="form-control form-control-sm col-12" placeholder="리바이 가능 횟수"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">리엔트리</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="re_entry" class="form-control form-control-sm col-12" placeholder="리엔트리 가능 횟수"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">애드온</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="addon" class="form-control form-control-sm col-12" placeholder="애드온 가능 횟수"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">보상타입</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <select name="reward_type" class="form-control custom-select">
                                                <option value="1">개런티</option>
                                                <option value="2">논개런티</option>
                                                <option value="3">티켓이벤트</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">보상</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="reward" class="form-control form-control-sm col-12" placeholder="상금금액"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">Reward Ticket</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="reward_ticket" class="form-control form-control-sm col-12" placeholder="리워드 티켓"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">애드온 지급칩</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="addon_offer_chip" class="form-control form-control-sm col-12" placeholder="애드온 지급칩"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">최소 보상 지급 인원</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="reward_min_player" class="form-control form-control-sm col-12"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">티켓 사용 여부</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="use_buyin_ticket" class="form-control form-control-sm col-12" placeholder="1 or 0"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">헤드라인</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="headline" class="form-control form-control-sm col-12"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">헤드라인 색</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="color_headline" class="form-control form-control-sm col-12"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">토너먼트 이름색</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="color_title" class="form-control form-control-sm col-12"></div>
                                    </div>
                                </td>
                                <td></td>
                                <th class="table-light align-middle">table_resource</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="text" name="table_resource" class="form-control form-control-sm col-12"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light align-middle">애드온 참가비(캐시)</th>
                                <td>
                                    <div class="row">
                                        <div class="col-12"><input type="number" name="addon_cash" class="form-control form-control-sm col-12"></div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary regTmt">등록</button>
                        <button type="reset" class="btn btn-secondary reset">초기화</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- SheetJS js-xlsx -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
    <!-- FileSaver savaAs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
    <script>
        function s2ab(s) {
            let buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
            let view = new Uint8Array(buf);  //create uint8array as viewer
            for (let i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
            return buf;
        }
        function exportExcel(data, data_type, name_sheet, name_file) {
            let wb = XLSX.utils.book_new();
            let worksheet;
            switch(data_type) {
                case 'json':
                    worksheet = XLSX.utils.json_to_sheet(data);
                    break;
                case 'table':
                    worksheet = XLSX.utils.table_to_sheet(data);
                    break;
                default://array
                    worksheet = XLSX.utils.aoa_to_sheet(data);
                    break;
            }
            XLSX.utils.book_append_sheet(wb, worksheet, name_sheet);
            let wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});
            saveAs(new Blob([s2ab(wbout)],{type: 'application/octet-stream'}), name_file);
        }
        $(document).ready(function() {
            $('input[name=search_start_date]').daterangepicker({
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
                "startDate": moment('{{ $search['sdate'] }}').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });
            $('input[name=search_end_date]').daterangepicker({
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
                "startDate": moment('{{ $search['edate'] }}').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });
            $('input[name=start_date]').daterangepicker({
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
                "startDate": moment('{{ $now }}').format('YYYY-MM-DD HH:mm:ss')
            }, function(start, end, label) {
            });

            $('.period-selector').on('click', function () {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                setSearchDateTime(parseInt(period), 'input[name=search_start_date' + target + ']', 'input[name=search_end_date' + target + ']');
            });
            $('button[name=searchTournament]').on('click', function() {
                $('form[name=frmSearch]').submit();
            });
            $('button[name=excelTournament]').on('click', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/management/tournament/excel',
                    data: {
                        search_start_date: $('input[name=search_start_date]').val()
                      , search_end_date: $('input[name=search_end_date]').val()
                      , search_money: $('input[name=search_money]:checked').val()
                      , search_name: $('input[name=search_name]').val()
                    },
                    dataType: 'json',
                    success: function(result) {
                        exportExcel(result.records, 'array', 'tournament', 'tournament.xlsx');
                    }
                });
            });
            let entry_min = {{ env('APP_ENV') == 'production' ? 4 : 2 }};
            function checkParam(param) {
                if(param.title.length === 0) return {result: false, message: '토너먼트 이름을 입력해 주세요.'};
                if(param.entry_min.length === 0 || param.entry_max.length === 0) return {result: false, message: '최소/최대 인원을 입력해 주세요.'};
                if(parseInt(param.entry_min) < entry_min || parseInt(param.entry_min) > 999 || parseInt(param.entry_max) < 6 || parseInt(param.entry_max) > 999) {
                    return {result: false, message: '최소/최대 인원을 범위에 맞게 입력해 주세요.'};
                }
                if(param.buyin_cash.length === 0) return {result: false, message: '바이인 금액을 입력해 주세요.'};
                if(param.buyin_offer_chip.length === 0) return {result: false, message: '토너먼트 시작칩을 입력해 주세요.'};
                if(param.blind_lvup_time.length === 0) return {result: false, message: '레벨업 시간을 입력해 주세요.'};
                if(param.break_time.length === 0) return {result: false, message: '브레이크 시간을 입력해 주세요.'};
                if(param.re_buy.length === 0) return {result: false, message: '리바이 가능 횟수를 입력해 주세요.'};
                if(param.re_entry.length === 0) return {result: false, message: '리엔트리 가능 횟수를 입력해 주세요.'};
                if(param.addon.length === 0) return {result: false, message: '애드온 가능 횟수를 입력해 주세요.'};
                if(param.reward.length === 0) return {result: false, message: '보상을 입력해 주세요.'};
                if(param.reward_ticket.length === 0) return {result: false, message: '리워드 티켓을 입력해 주세요.'};
                if(param.addon_offer_chip.length === 0) return {result: false, message: '애드온 지급칩을 입력해 주세요.'};
                if(param.reward_min_player.length === 0) return {result: false, message: '최소 보상 지급 인원을 입력해 주세요.'};
                if(param.use_buyin_ticket.length === 0) return {result: false, message: '티켓 사용 여부를 입력해 주세요.'};
                return {result: true, message: ''};
            }
            $('input[name=free_role]').on('click', function() {
                if($(this).prop('checked')) {
                    $('input[name=buyin_cash]').val(0);
                    $('input[name=buyin_cash]').prop('readonly', true);
                } else {
                    $('input[name=buyin_cash]').prop('readonly', false);
                }
            });
            $('button.regTmt').on('click', function() {
                $(this).prop('disabled', true);
                let param = {
                    title: $('input[name=title]').val()
                  , money: $('input[name=money]:checked').val()
                  , start_date: $('input[name=start_date]').val()
                  , player_cnt: $('input[name=player_cnt]').val()
                  , entry_min: $('input[name=entry_min]').val()
                  , entry_max: $('input[name=entry_max]').val()
                  , buyin_cash: $('input[name=buyin_cash]').val()
                  , free_role: $('input[name=free_role]').prop('checked')
                  , buyin_offer_chip: $('input[name=buyin_offer_chip]').val()
                  , blind_type: $('input[name=blind_type]').val()
                  , blind_lvup_time: $('input[name=blind_lvup_time]').val()
                  , break_time: $('input[name=break_time]').val()
                  , break_time_cycle:  $('input[name=break_time_cycle]').val()
                  , re_buy: $('input[name=re_buy]').val()
                  , re_entry: $('input[name=re_entry]').val()
                  , addon: $('input[name=addon]').val()
                  , reward_type: $('select[name=reward_type] option:selected').val()
                  , reward: $('input[name=reward]').val()
                  , reward_ticket: $('input[name=reward_ticket]').val()
                  , addon_offer_chip: $('input[name=addon_offer_chip]').val()
                  , reward_min_player: $('input[name=reward_min_player]').val()
                  , use_buyin_ticket: $('input[name=use_buyin_ticket]').val()
                  , headline: $('input[name=headline]').val()
                  , color_headline: $('input[name=color_headline]').val()
                  , color_title: $('input[name=color_title]').val()
                  , table_resource: $('input[name=table_resource]').val()
                  , addon_cash: $('input[name=addon_cash]').val()
                };
                if(param.free_role) param.buyin_cash = '0';
                let check = checkParam(param);
                if(!check.result) {
                    $(this).prop('disabled', false);
                    alert(check.message);
                    return;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/management/tournament/reg',
                    data: param,
                    dataType: 'json',
                    success: function(record) {
                        if(record.result) {
                            alert('토너먼트가 등록되었습니다.');
                            $('button[name=searchTournament]').trigger('click');
                        }
                    }
                });
                $('button.regTmt').prop('disabled', false);
            });

            $('button[name=detailTournament]').on('click', function() {
                const tid = $(this).attr('data-i');
                document.location.href = '/management/tournament/detail/' + tid;
            });
        });
    </script>
@endsection
