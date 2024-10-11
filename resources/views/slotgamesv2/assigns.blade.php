@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">슬롯 설정</a></li>
                <li class="breadcrumb-item">보정잭팟 설정</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb p-0">
                                <h2><b>보정잭팟 설정</b></h2>

                                <div class="pull-right pr-0">
                                    <button class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#modify"><b>추가</b></button>
                                    <button class="btn btn-danger" data-toggle="modal" data-size="modal-xl" data-target="#deploy"><b>데이터 반영</b></button>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif


                        <div class="row">
                            <div class="col p-0">
                                <div class="card">
                                    <div class="card-header">
                                    </div>

                                    <div class="card-body p-0 overflow-auto">
                                        <table class="table table-sm table-striped table-bordered table-hover mb-0">
                                            <thead class="thead-dark text-center align-top">
                                            <tr>
                                                <th rowspan="2" width="60">번호</th>
                                                <th rowspan="2" width="200">지정슬롯</th>
                                                <th rowspan="2" width="100">사용처</th>
                                                <th rowspan="2" width="120">지급확률</th>
                                                <th colspan="2">기준RTP</th>
                                                <th colspan="2">목표RTP</th>
                                                <th colspan="3">기대배수</th>
                                                <th rowspan="2" width="100">상태</th>
                                                <th rowspan="2" width="200">메모</th>
                                                <th rowspan="2" width="180">최근 업데이트</th>
                                                <th rowspan="2" width="180">-</th>
                                            </tr>

                                            <tr>
                                                <th>최소</th>
                                                <th>최대</th>

                                                <th>최소</th>
                                                <th>최대</th>

                                                <th>대상</th>
                                                <th>최소</th>
                                                <th>최대</th>
                                            </tr>
                                            </thead>

                                            <tbody class="text-center text-nowrap align-middle">

                                            @if (count($data) > 0)
                                                @foreach ($data AS $key => $row)

                                                    <tr>
                                                        <td>{{ $row->idx }}</td>

                                                        <td class="pl-3 pr-3">{{ $row->slot_name }}</td>

                                                        <td class="pl-3 pr-3">{{ $row->used == 'B' ? '구매스핀' : '일반스핀' }}</td>

                                                        <td class="text-right pl-3 pr-3">{{ $row->prob }} %</td>

                                                        <td class="text-right pl-3 pr-3" data-type="avg">{{ $row->rtp_min }}</td>
                                                        <td class="text-right pl-3 pr-3" data-type="avg">{{ $row->rtp_max }}</td>

                                                        <td class="text-right pl-3 pr-3" data-type="avg">{{ $row->range_min }}</td>
                                                        <td class="text-right pl-3 pr-3" data-type="avg">{{ $row->range_max }}</td>

                                                        <td class="text-right pl-3 pr-3">{{ $row->expected }}</td>
                                                        <td class="text-right pl-3 pr-3" data-type="multi">{{ $row->expected_min }}</td>
                                                        <td class="text-right pl-3 pr-3" data-type="multi">{{ $row->expected_max }}</td>

                                                        <td><span class="badge badge-{{ $row->status == 1 ? 'success' : 'danger' }} text-md p-2">{{ $row->status == 1 ? '사용' : '중지' }}</span></td>

                                                        <td class="text-right pl-3 pr-3">{{ $row->summary }}</td>
                                                        <td class="text-right pl-3 pr-3">{{ $row->updated }}</td>

                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-sm btn-primary" data-i="{{ $key }}" data-toggle="modal" data-size="modal-xl" data-target="#modify">변경</button>
                                                                <button type="button" class="btn btn-sm btn-secondary" data-i="{{ $key }}" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else

                                                <tr>
                                                    <td colspan="999" class="text-center p-3"><b><i>DATA NOT FOUND.</i></b></td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <ul class="alert alert-warning text-dark" role="alert">
                                        <li class="ml-2"><span>알림 작성 기준</span> {{ date('Y-m-d H:i:s')}}</li>
                                        <hr />
                                        <li class="ml-2"><span>기준RTP</span> 유저의 RTP가 기준RTP 최소 ~ 최대 사이의 조건 값을 만족 할 때 사용 가능.</li>
                                        <li class="ml-2"><span>지급확률</span> 기준RTP 조건을 만족할 때, 지급확률에 의해 목표RTP값을 계산.</li>
                                        <li class="ml-2"><span>목표RTP</span> 위 두가지 조건을 만족 시, 목표RTP 최대 ~ 최소 사이의 임의값을 정하여 유저의 현재 RTP값을 뺀 값으로 지급배수를 정함. ( 목표RTP - 유저RTP 값이 배수는 아님. )</li>
                                        <hr />
                                        <li class="ml-2"><span>RTP 1당 증가치</span> 유저RTP에 상응하는 누적 배팅금액 / 목표RTP * ( 목표RTP / 100 )</li>
                                        <li class="ml-2"><span>배수공식</span> RTP증가치 * ( 목표RTP - 시작RTP ) / 배팅금액</li>
                                        <hr />
                                        <li class="ml-2"><span class="text-danger">기대배수</span> <medium class="text-danger">기대배수는 대략 추측값으로 구간누적배팅금액에 따라 다를 수 있습니다.</medium></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--	보정잭팟 추가/변경 : 시작						-->
    <div id="modify" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmSet" method="post" action="{{ Route('slotgamesv2.set_assigns') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                    <input type="hidden" name="idx" value="" />
                    <div class="modal-header"><h5 class="modal-title">보정잭팟 추가/변경</h5></div>

                    <div class="modal-body was-validated">

                        <label class="mb-0" for="slot_id"><strong>선택 슬롯</strong></label>
                        <div class="form-row">
                            <select name="slot_id" for="slot_id" class="form-control">
                                <option value="">▒▒ 슬롯 전체 ▒▒</option>
                                @foreach($slots as $slot)
                                    <option value="{{ $slot->slot_id }}">{{ $slot->slot_name }} ( {{ $slot->slot_id }} )</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">선택하지 않으면 모든 슬롯에 적용됩니다.</small>
                        </div>

                        <hr class="mt-3" />

                        <label class="mb-0" for="used"><strong>사용처</strong></label>
                        <div class="form-row">
                            <select name="used" for="used" class="form-control" required>
                                <option value="">▒ 사용처 선택 ▒</option>
                                <option value="N">일반스핀</option>
                                <option value="B">구매스핀</option>
                            </select>
                            <div class="valid-feedback">선택 완료</div>
                            <div class="invalid-feedback">사용처는 필수 선택입니다.</div>
                        </div>

                        <hr class="mt-3" />

                        <label class="mb-0" for="prob"><strong>지급 확률</strong></label>
                        <div class="form-row">
                            <input type="number" id="prob" name="prob" value="" step="0.01" min="0" max="100" maxlength="3" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="지급 확률" required />
                            <div></div>
                            <div class="valid-feedback">입력 완료</div>
                            <div class="invalid-feedback">지급 확률은 0 ~ 100 사이의 값만 입력 가능합니다.</div>
                        </div>

                        <hr class="mt-3" />

                        <label class="mb-0" for="rtp_min"><strong>대상RTP 구간</strong></label>
                        <div class="form-row">
                            <input type="number" id="rtp_min" name="rtp_min" value="" step="1" min="0" max="9999" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 RTP" required />
                            <input type="number" id="rtp_max" name="rtp_max" value="" step="1" min="0" max="9999" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 RTP" required />
                            <div class="valid-feedback">입력 완료</div>
                            <div class="invalid-feedback"><strong>대상RTP 구간</strong> 에 포함된 게임만 지급확률에 의해 잭팟이 지급됩니다.</div>
                        </div>

                        <hr class="mt-3" />

                        <label class="mb-0" for="range_min"><strong>목표RTP 구간</strong></label>
                        <div class="form-row">
                            <input type="number" id="range_min" name="range_min" value="" step="1" min="0" max="9999" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 RTP" required />
                            <input type="number" id="range_max" name="range_max" value="" step="1" min="0" max="9999" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 RTP" required />
                            <div class="valid-feedback">입력 완료</div>
                            <div class="invalid-feedback"><strong>목표RTP 구간</strong> 은 "<strong>목표RTP(구간임의값) - 유저RTP</strong>" 차이의 값을 배수로 환산합니다.</div>
                            <small class="form-text text-muted">* 단, <strong>사용처</strong>가 <strong>일반스핀</strong> 일 경우,<br />&nbsp;&nbsp;&nbsp;<strong>목표RTP 최소값</strong>은 <strong>대상RTP 최대값</strong> 보다 작을 수 없습니다.</small>
                        </div>

                        <hr class="mt-3" />

                        <label class="mb-0" for="status"><strong>활성화 여부</strong></label>
                        <div class="form-row">
                            <div class="input-group mb-1">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" value="1">
                                    <label class="custom-control-label" for="status"></label>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-3" />

                        <label class="mb-0" for="summary"><strong>메모</strong></label>
                        <div class="form-row">
                            <input type="text" id="summary" name="summary" class="form-control col-12 mb-1 none-arrow" placeholder="메모" required="">
                            <div class="valid-feedback">입력 완료</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" aria-label="modify">추가(변경)</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--	보정잭팟 삭제 : 시작							-->
    <div id="drop" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmSet" method="post" action="{{ Route('slotgamesv2.drop_assigns') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                    <div class="modal-header"><h5 class="modal-title">배팅데이터 삭제</h5></div>
                    <input type="hidden" name="idx" value="" />

                    <div class="modal-body was-validated">
                        <p>삭제 하시겠습니까?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" aria-label="drop">삭제</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--	데이터 반영 : 시작								-->
    <div id="deploy" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmCmd" method="post" action="{{ Route('slotgamesv2.reset_assigns') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                    <div class="modal-header"><h5 class="modal-title">데이터 반영</h5></div>

                    <div class="modal-body text-center">
                        <input type="hidden" name="cmd" value="refereload" />
                        반영시 복구 불가능합니다.<br /><br />
                        반영하시겠습니까?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" aria-label="modify">반영</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="/js/apis.js"></script>
    <script>

        $(function ()
        {
            //
            // 배팅정보 데이터 정의.
            //
            let data = {!! $data !!};

            //
            // 목록에 출력할 컬럼.
            //
            let cols = ['idx', 'slot_id', 'used', 'prob', 'rtp_min', 'rtp_max', 'range_min', 'range_max', 'status', 'summary'];


            //
            // 변경/삭제 버튼 눌렀을때 이벤트.
            //
            $('#modify, #drop').on('show.bs.modal', function (e)
            {
                var t = $(e.relatedTarget).data('i');
                var d = data[t];
                var f = $(e.currentTarget).find('form');

                var s = t == undefined;

                for (var i in cols)
                {
                    switch (cols[i])
                    {
                        case 'used':
                            let used_option = f.find('[name='+cols[i]+'] option');
                            if(f.find('[name=idx]').val() == '') continue;
                            used_option.each(function(idx) {
                                if($(this).val() == d[cols[i]]) $(this).prop('selected', true);
                            });
                            break;
                        case 'status':
                            f.find('[name='+cols[i]+']').prop('checked', d != undefined && d[cols[i]] == 1 ? true : false);
                            break;
                        default:
                            f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]]);
                            break;
                    }
                }
            });

            //
            // 추가/변경 콜백 처리.
            //
            $('form').on('callback', function (e, d)
            {
                if (d.msg != undefined && d.msg != '')
                    alert(d.msg);

                if (d.result != 1)
                    return false;

                document.location.reload();
            });


        });

    </script>

@endsection
