@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('games.info') }}">게임조회</a></li>
                <li class="breadcrumb-item">토너먼트 조회</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>토너먼트</h2>
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
                                                <label class="col-md-1 col-sm-1 text-center">
                                                    <label class="col-form-label mt-2">조회기간 : </label>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-3 label-align">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" id="search_start_date" name="search_start_date" value="{{ $search['search_start_date'] }}">
                                                        <span class="input-group-addon" style="cursor:pointer;">
                                                            <i class="fa fa-calendar mt-1"></i>
                                                        </span>
                                                    </div>
                                                </label>
                                                <label class="col-form-label col-md-2 col-sm-3 label-align">
                                                    <div class="input-group date" id="datepicker2">
                                                        <input type="text" class="form-control" id="search_end_date" name="search_end_date" value="{{ $search['search_end_date'] }}">
                                                        <span class="input-group-addon" style="cursor:pointer;">
                                                            <i class="fa fa-calendar mt-1"></i>
                                                        </span>
                                                    </div>
                                                </label>
                                                <div class="col-md-2">
                                                    <label class="col-form-label">
                                                        <button type="button" class="btn btn-primary period-selector" period="1" target="">1일</button> &nbsp;
                                                        <button type="button" class="btn btn-primary period-selector" period="3" target="">3일</button> &nbsp;
                                                        <button type="button" class="btn btn-primary period-selector" period="7" target="">7일</button>
                                                    </label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="col-form-label">
                                                        <select class="form-control custom-select" name="money">
                                                            <option value=""{{ $search['money'] === '' ? ' selected' : '' }}>채널</option>
                                                            <option value="2016"{{ $search['money'] === '2016' ? ' selected' : '' }}>칩</option>
                                                            <option value="2018"{{ $search['money'] === '2018' ? ' selected' : '' }}>골드</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="col-form-label">
                                                        <button type="button" id="btn-search" class="btn btn-secondary">검색</button>
                                                    </label>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="x_panel">
                                    <div class="x_content">
                                        <table class="table table-bordered info-table text-center">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>토너먼트명</th>
                                                <th>보상타입</th>
                                                <th>상금</th>
                                                <th>토탈바이인</th>
                                                <th>채널</th>
                                                <th>시작시간</th>
                                                <th>바이인금액</th>
                                                <th>시작칩</th>
                                                <th>상태</th>
                                                <th>순위</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($records) === 0)
                                                <tr>
                                                    <td colspan="999">데이터가 없습니다.</td>
                                                </tr>
                                            @endif
                                            @foreach($records as $key => $row)
                                                <tr>
                                                    <td>{{ $numberStart-- }}</td>
                                                    <td>{{ $row->title }}</td>
                                                    <td>{{ $row->reward_type }}</td>
                                                    <td>{{ Helper::numberToKorean($row->reward) }}</td>
                                                    <td>{{ Helper::numberToKorean($row->total_buyin) }}</td>
                                                    <td>{{ $row->channel }}</td>
                                                    <td>{{ $row->start_date }}</td>
                                                    <td>{{ Helper::numberToKorean($row->buyin_cash) }}</td>
                                                    <td>{{ Helper::numberToKorean($row->buyin_offer_chip) }}</td>
                                                    <td>{{ $row->status }}</td>
                                                    <td>
                                                        @if(in_array($row->status, ['보상지급', '종료']))
                                                        <button  name="manage" class="btn btn-info btn-sm" data-toggle="modal" data-target="#rank" data-i="{{ $row->tid }}" data-j="{{ $row->title }}">순위보기</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($records)
                                        <div class="x_content">
                                            <ul class="pagination justify-content-center">{{ $records->links() }}</ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade font-sm" id="rank" tabindex="-1">
        <div class="modal-dialog modal-size">
            <div class="modal-content modal-size">
                <div class="modal-header">
                    <h6 class="modal-title">토너먼트 순위</h6>
                    <button type="button" class="close users" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="frmUser" enctype="multipart/form-data">
                        <div><h6></h6></div>
                        <div class="m-3"></div>
                        <div>
                            <div class="row mb-2">
{{--                                <div class="col-sm-3 mr-4"></div>--}}
{{--                                <div class="col-sm-3 pr-2 pl-4">--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-4 pr-2 pl-0"><input type="text" name="r_userid" class="form-control font-sm" placeholder="아이디검색"></div>--}}
{{--                                <div class="pl-0"><button type="button" name="r_search" class="btn btn-primary font-sm">검색</button></div>--}}
                            </div>
                            <table class="table table-bordered rank-table text-center font-sm">
                                <thead class="thead-light">
                                <tr>
                                    <th>닉네임</th>
                                    <th>순위</th>
                                </tr>
                                </thead>
                                <tbody class="users">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#search_start_date').daterangepicker({
                "singleDatePicker": true,
                "timePicker": false,
                "timePicker24Hour": false,
                "timePickerSeconds": false,
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ $search['search_start_date'] }}').format('YYYY-MM-DD')
            }, function(start, end, label) {
            });
            $('#search_end_date').daterangepicker({
                "singleDatePicker": true,
                "timePicker": false,
                "timePicker24Hour": false,
                "timePickerSeconds": false,
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "확인",
                    "cancelLabel": "취소",
                    "weekLabel": "W",
                    "daysOfWeek": ["일","월","화","수","목","금","토"],
                    "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
                },
                "startDate": moment('{{ $search['search_end_date'] }}').format('YYYY-MM-DD')
            }, function(start, end, label) {
            });

            $('.period-selector').off('click').on('click', function() {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                fillSearchDate(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
            });

            $('#btn-search').on('click', function() {
                $('#searchForm').submit();
            });

            $('#rank').on('show.bs.modal', function(e) {
                $('div.modal-body h6').text($(e.relatedTarget).data('j'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('games.tournament.rank') }}',
                    data: {
                        tid: $(e.relatedTarget).data('i')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.error === true) return;

                        let html = [];
                        data.records.forEach(function(row) {
                            html.push('<tr>');
                            html.push('<td>' + row.nickname + '</td>');
                            html.push('<td>' + row.rank + '</td>');
                            html.push('</tr>');
                        });
                        $('table.rank-table tbody').html(html);
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                    }
                });
            });
        });
    </script>
@endsection
