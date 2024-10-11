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
                            <h2>토너먼트 상세보기</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-bordered info-table">
                                <thead>
                                <tr align="center">
                                    <th>토너먼트 번호</th>
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
{{--                                    <th>옵션</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                    <tr align="center">
                                        <td>{{ $record->tid }}</td>
                                        <td>{{ $record->title }}</td>
                                        <td>{{ $record->start_date }}</td>
                                        <td>{{ Helper::getTournamentStatus($record->status) }}</td>
                                        <td>{{ $record->people }}</td>
                                        <td>{{ $record->entry_min }} / {{ $record->entry_max }}</td>
                                        @if($record->reward_type == 1)
                                            <td>{{ Helper::numberToKorean(max($record->reward, $record->total_buyin)) }}{{ $record->money == '2018' ? '골드' : '칩' }}</td>
                                        @else
                                            <td>{{ $record->reward }}</td>
                                        @endif
                                        <td>{{ $record->re_buy }}</td>
                                        <td>{{ $record->re_entry }}</td>
                                        <td>{{ $record->addon }}</td>
                                        <td>{{ $record->blind_lvup_time }}</td>
                                        <td>{{ Helper::numberToKorean($record->buyin_offer_chip) }}</td>
                                        <td>{{ Helper::numberToKorean($record->buyin_cash) }}{{ $record->money == '2018' ? '골드' : '칩' }}</td>
                                        <td>{{ Helper::numberToKorean($record->total_buyin) }}{{ $record->money == '2018' ? '골드' : '칩' }}</td>
{{--                                        <td></td>--}}
                                    </tr>
                                </tbody>
                            </table>
                                <table class="table table-bordered info-table2 col-5">
                                    <tr align="center">
                                        <th>총상금</th>
                                        <td>{{ Helper::numberToKorean(max($record->reward, $record->total_buyin)) }}</td>
                                        <th>블라인드 타입</th>
                                        <td>{{ $record->blind_type }}</td>
                                        <th>최소 보상 지급 인원</th>
                                        <td>{{ $record->reward_min_player }}</td>
                                        <th>티켓 사용 여부</th>
                                        <td>{{ $record->use_buyin_ticket }}</td>
                                    </tr>
                                </table>
                        </div>
                    </div>
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>등록인원</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <form name="frmSearch" method="get" action="{{ route('managements.tournament') }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3 col-sm-4">
                                            <label class="col-12 ">
                                                <label class="col-form-label">
                                                    <input type="radio" class="flat" name="search_type" value="nickname"> 닉네임 &nbsp;
                                                </label>
                                                <label class="col-form-label">
                                                    <input type="radio" class="flat" name="search_type" value="userSeq"> 회원번호 &nbsp;
                                                </label>
                                                <label class="col-form-label">
                                                    <input type="radio" class="flat" name="search_type" value="email"> Email
                                                </label>
                                                <label class="col-form-label label-align ml-2">
                                                    <select class="form-control" name="login_type">
                                                        <option value="3">플랫폼</option>
                                                        <option value="1">구글</option>
                                                        <option value="4">애플로그인</option>
                                                    </select>
                                                </label>
                                            </label>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <label class="col-form-label col-md-6 col-sm-6">
                                                <input type="text" name="search_name" class="form-control">
                                            </label>
                                            <label class="col-form-label col-md-2 col-sm-2">
                                                <input type="hidden" name="tid" value="{{ $record->tid }}">
                                                <input type="hidden" name="page">
                                                <button type="button" name="searchMember" class="btn btn-secondary">조회</button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="x_content">
                            <table class="table table-bordered member-table">
                                <thead>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="x_content">
                            <ul class="pagination justify-content-center"></ul>
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
    <script>
    $(document).ready(function() {
        goPageMember = function(page) {
            $('input[name=page]').val(page);
            $('button[name=searchMember]').trigger('click');
        };
        $('button[name=searchMember]').on('click', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let param = {
                tid: $('input[name=tid]').val()
              , page: $('input[name=page]').val()
              , search_type: $('input[name=search_type]:checked').val()
              , login_type: $('select[name=login_type] option:selected').val()
              , keyword: $('input[name=search_name]').val()
            };

            $.ajax({
                type: 'POST',
                url: '/management/tournament/member/reg',
                data: param,
                dataType: 'json',
                success: function(result) {
                    let html_head = [];
                    let html = [];
                    html_head.push('<tr align="center">');
                    html_head.push('<th>순위</th>');
                    html_head.push('<th>닉네임</th>');
                    html_head.push('<th>등록한 시간</th>');
                    html_head.push('<th>참가형태</th>');
                    html_head.push('<th>회원번호</th>');
                    if(result.data.closed) {
                        html_head.push('<th>리바이 사용횟수</th>');
                        html_head.push('<th>리엔트리 사용횟수</th>');
                        html_head.push('<th>애드온 사용횟수</th>');
                        html_head.push('<th>획득상금</th>');
                        html_head.push('<th>보상날짜</th>');
                    } else {
                        html_head.push('<th>보유토너먼트 골드(칩)</th>');
                        html_head.push('<th>획득예상상금</th>');
                        html_head.push('<th>테이블번호</th>');
                    }
                    html_head.push('</tr>');
                    $('table.member-table thead').html(html_head.join(''));

                    if(result.data.record_cnt == 0) {
                        html.push('<tr>');
                        html.push('<td class="text-center pt-5 pb-5" colspan="999">등록된 인원이 없습니다.</td>');
                        html.push('</tr>');
                    } else {
                        result.data.records.forEach(function(row) {
                            html.push('<tr align="center">');
                            if(result.data.closed) {
                                html.push('<td>' + (row.rank == 99999 ? '-' : row.rank) + '</td>');
                                html.push('<td>' + row.nickname + '</td>');
                                html.push('<td>' + row.updatedate + '</td>');
                                html.push('<td>바이인 지불</td>');
                                html.push('<td>' + row.user_seq + '</td>');
                                html.push('<td>' + row.re_buy + '</td>');
                                html.push('<td>' + row.re_entry + '</td>');
                                html.push('<td>' + row.addon + '</td>');
                                html.push('<td>' + numberFormat(row.reward) + '</td>');
                                html.push('<td>' + row.reward_date + '</td>');
                            } else {
                                html.push('<td>-</td>');
                                html.push('<td>' + row.nickname + '</td>');
                                html.push('<td>' + row.updatedate + '</td>');
                                html.push('<td>바이인 지불</td>');
                                html.push('<td>' + row.user_seq + '</td>');
                                html.push('<td>' + row.chip + '</td>');
                                html.push('<td>-</td>');
                                html.push('<td>-</td>');
                            }
                            html.push('</tr>');
                        });
                    }
                    $('table.member-table tbody').html(html.join(''));
                    $('ul.pagination').html(makePagination(result.data.pagination, 'goPageMember').join(''));
                }
            });
        });
        goPageMember(1);
    });
    </script>
@endsection
