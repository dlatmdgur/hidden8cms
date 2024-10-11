@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotusers.search') }}">슬롯 유저정보</a></li>
				<li class="breadcrumb-item">유저 상세 정보</li>
			</ol>

			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-11">
								<h2><b>유저 상세 정보</b></h2>
							</div>
                            <div class="col-lg-1">
                                <button name="search" class="btn btn-sm btn-primary">검색으로</button>
                            </div>
						</div>

						<div class="row mb-3">
							<div class="col-12 col-sm-6 col-md-3 mb-1">
								<label class="font-weight-bold">MEMBER ID</label>
								<p>{{ $user->id }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3 mb-1">
								<label class="font-weight-bold">USER SEQ</label>
								<p>{{ $user->user_seq }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3 mb-1">
								<label class="font-weight-bold">USER ID</label>
								<p>{{ $user->userid }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3 mb-1">
								<label class="font-weight-bold">가입처</label>
								<p>{{ $user->join_site }}</p>
							</div>
						</div>

						<hr />

						<div class="row mb-3">
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">가입자 명</label>
								<p>{{ $user->name }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">닉네임 (사이트)</label>
								<p>{{ $user->nickname }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">닉네임 (게임)</label>
								<p>{{ $user->game_nickname }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">레벨(경험치)</label>
								<p>{{ $user->level }} ( {{ $user->exp }} )</p>
							</div>
						</div>

						<hr />

						<div class="row mb-3">
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">보석</label>
								<p>{{ number_format($user->gem) }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">칩</label>
								<p>{{ number_format($user->chip) }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">골드</label>
								<p>{{ number_format($user->gold) }}</p>
							</div>
						</div>

						<hr />

						<div class="row mb-3">
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">접속 IP</label>
								<p>{{ $user->login_ip }}</p>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<label class="font-weight-bold">접속 시간</label>
								<p>{{ $user->game_logined }}</p>
							</div>
						</div>


						<div class="row x_title mt-4">
							<div class="col-lg-12">
								<h2><b>RTP 내역</b></h2>
							</div>
						</div>

						<div class="row">
							<div class="col-12 table-responsive-xl">
								<table class="table table-sm table-bordered table-hover table-striped">
                                    <thead class="thead-dark text-center text-nowrap align-middle">
										<tr>
                                            <th rowspan="2" width="150">슬롯ID</th>
                                            @if(!empty($cols) && count($cols) > 0)
                                                @foreach($cols AS $bet)
                                                    <th>{{ number_format($bet / 1000) }}<span style="color: #afafaf; font-size:12px;">.000</span></th>
                                                @endforeach
                                            @endif
                                            <th rowspan="2" width="90">-</th>
										</tr>
									</thead>

									<tbody class="text-right align-middle">
                                    @if(!empty($rtps) && count($rtps))
                                        @foreach($rtps AS $slot_id => $row)
										<tr>
                                            <td class="text-left pl-3">{{ $slot_id }}</td>
                                            @if(!empty($cols) && count($cols) > 0)
                                                @foreach($cols AS $bet)
                                                    @if(empty($row[$bet]))
                                                        <td class="text-muted pl-3 pr-2">기록없음</td>
                                                    @else
                                                        <td data-type="avg" class="pl-3 pr-2">{{ number_format($row[$bet]['rtp'], 2) }}</td>
                                                    @endif
                                                @endforeach
                                            @endif
											<td>
                                                <button type="clear" class="btn btn-sm btn-danger text-nowrap" data-uid="{{ $user->user_seq }}" data-slot_id="{{ $slot_id }}" data-toggle="modal" data-target="#reset_rtp">초기화</button>
											</td>
										</tr>
	                                    @endforeach
                                    @endif
									</tbody>
								</table>
							</div>
						</div>


						<div class="row x_title mt-4">
							<div class="col-lg-12">
								<h2><b>버프 내역</b></h2>
								<div class="float-right" data-uid="{{ $user->user_seq }}">
									<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#set_buff">추가</button>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-12 table-responsive-xl">
								<table class="table table-sm table-bordered table-hover">
                                    <thead class="thead-dark text-center text-nowrap align-middle">
										<tr>
											<th>버프코드</th>
											<th>적용슬롯</th>
											<th>버프종류</th>
											<th>버프값</th>
											<th>버프만료일</th>
											<th>최근변경일</th>
											<th>생성일</th>
											<th width="120">-</th>
										</tr>
									</thead>

									<tbody class="text-center text-nowrap align-middle">
@if (count($buffs) > 0)
	@foreach ($buffs AS $key => $row)

		@if (time() - strtotime($row->expired) > 0)
										<tr style="background-color: #CCC; color: gray !important;">
		@else
										<tr>
		@endif

											<td>{{ $row->code }}</td>
											<td>{{ $row->slot_id }}</td>
											<td>{{ $row->target }}</td>
											<td class="text-right pl-3 pr-3">{{ number_format($row->amount) }}</td>
											<td>{{ $row->expired }}</td>
											<td>{{ $row->updated }}</td>
											<td>{{ $row->created }}</td>
											<td>
												<div class="btn-group" data-uid="{{ $row->uid }}" data-slot_id="{{ $row->slot_id }}" data-target="{{ $row->target }}" data-code="{{ $row->code }}" data-amount="{{ $row->amount }}" data-expired="{{ $row->expired }}">
													<button type="button" class="btn btn-sm btn-info text-nowrap" data-toggle="modal" data-target="#set_buff">변경</button>
													<button type="button" class="btn btn-sm btn-danger text-nowrap" data-toggle="modal" data-target="#drop_buff">삭제</button>
												</div>
											</td>
										</tr>
	@endforeach
@else

										<tr>
											<td colspan="999" class="text-center">등록된 버프가 없습니다.</td>
										</tr>
@endif

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div id="reset_rtp" class="modal fade" tabindex="-1" modal-type="rtp">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmReset" method="post" action="{{ route('slotusers.reset_rtp') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="uid" value="{{ $user->user_seq }}" />
					<input type="hidden" name="slot_id" value="" />

					<div class="modal-header"><h5 class="modal-title">RTP 초기화</h5></div>

					<div class="modal-body text-left">
						<p class="ml-3 mb-3"><b>복구 불가능합니다. 초기화 하시겠습니까?</b></p>

						<p class="ml-3 mb-0">대상 유저 : <span id="uid"></span></p>
						<p class="ml-3">대상 슬롯 : <span id="slot_id"></span></p>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger" aria-label="reset">초기화</button>
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div id="set_buff" class="modal fade" tabindex="-1" modal-type="buff">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmBuffSet" method="post" action="{{ route('slotusers.set_buff') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<input type="hidden" name="uid" value="{{ $user->user_seq }}" />
				<div class="modal-header"><h5 class="modal-title">버프 추가 ( 변경 )</h5></div>

				<div class="modal-body was-validated">
					<div class="form-group">
						<label for="inpcode"><strong>버프코드</strong></label>
						<div class="input-group">
							<select id="inpselect" class="form-control col-4" required >
								<option value="EMPTY">직접입력</option>
								<option value="ALL">구분없음(모두)</option>
								<option value="RECOMMEND">추천인</option>
							</select>
							<input type="text" class="form-control" id="inpcode" name="code" value="" placeholder="버프코드를 입력하세요." readonly required >
						</div>
						<small class="form-text text-muted">버프종류를 세분화를 통해 관리하기 위함.</small>
						<div class="valid-feedback">Please choose a username.</div>
					</div>
					<div class="form-group">
						<label for="inptarget"><strong>버프종류</strong></label>
						<select name="target" id="inptarget" class="form-control" required >
							<option value="" selected>▒ 버프종류 ▒</option>
							<option value="RTP">슬롯 당첨확률 증가</option>
						</select>
						<small class="form-text text-muted">같은 버프종류는 합산하여 적용됩니다.</small>
					</div>
					<div class="form-group">
						<label for="inpslotid"><strong>대상슬롯</strong></label>
						<select name="slot_id" id="inpslotid" class="form-control" data-type="slots" required >
							<option value="ALL" selected>▒ 모든슬롯 ▒</option>
@if (count($slots) > 0)
	@foreach ($slots AS $row)
							<option value="{{ $row->slot_id }}">{{ $row->slot_name }} ( {{ $row->slot_id }} )</option>
	@endforeach
@endif
						</select>
						<small class="form-text text-muted">같은 버프종류는 합산하여 적용됩니다.</small>
					</div>
					<div class="form-group">
						<label for="inpamount"><strong>입력값</strong></label>
						<input type="number" class="form-control" id="inpamount" name="amount" value="" step="0.01" min="-99999" max="99999" placeholder="가산(감산)할 값을 입력하세요." required >
						<small class="form-text text-muted">유저의 행동에 따른 가산(양수) / 감산(음수) 값을 입력해야 합니다.</small>
					</div>
					<div class="form-group">
						<label for="inpexpired"><strong>만료일</strong></label>
						<input type="date" class="form-control" id="inpexpired" name="expired_date" value="{{ date('Y-m-d') }}" required >
						<small class="form-text text-muted mb-2">버프가 종료되는 날짜를 입력하세요.</small>
						<input type="time" class="form-control" id="inpexpired" name="expired_time" value="{{ date('H:i') }}" required >
						<small class="form-text text-muted">버프가 종료되는 날짜의 시간을 입력하세요.</small>
					</div>

					<p class="text-danger"><b>수정의 경우, "버프코드", "버프종류", "대상슬롯" 이 변경되면<br />새로 등록될 수 있습니다.</b></p>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" aria-label="modify">적용</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>


	<div id="drop_buff" class="modal fade" tabindex="-1" modal-type="buff">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmBuffDrop" method="post" action="{{ route('slotusers.drop_buff') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<input type="hidden" name="uid" value="{{ $user->user_seq }}" />
				<input type="hidden" name="slot_id" value="" />
				<input type="hidden" name="target" value="" />
				<input type="hidden" name="code" value="" />
				<div class="modal-header"><h5 class="modal-title">버프 삭제</h5></div>

				<div class="modal-body text-left">
						<p class="ml-3 mb-3"><b>복구 불가능합니다. 삭제 하시겠습니까?</b></p>

						<p class="ml-3 mb-0">대상 슬롯 : <span id="slot_id"></span></p>
						<p class="ml-3 mb-0">종류 버프 : <span id="target"></span></p>
						<p class="ml-3">버프 코드 : <span id="code"></span></p>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" aria-label="modify">삭제</button>
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
    $('button[name=search]').on('click', function() {
        document.location.href = '/slotusers/search';
    });
	$('[modal-type="rtp"]').on('show.bs.modal', function (e)
	{
		let em_b = $(e.relatedTarget);
		let d = em_b.data();

		$(this).find('input[name="uid"], #uid').text(d.uid).val(d.uid);
		$(this).find('input[name="slot_id"], #slot_id').text(d.slot_id).val(d.slot_id);
	});


	$('[modal-type="buff"]').on('show.bs.modal', function (e)
	{
		let t = $(this);
		let em_b = $(e.relatedTarget).parent();
		let d = em_b.data();


		$.each(['uid', 'slot_id', 'target', 'code', 'amount', 'expired'], function (n, f)
		{
			t.find('input[name="'+ f +'"], #'+f).text(d[f] ? d[f] : '').val(d[f] ? d[f] : '').attr('data-before', d[f]);

			switch (f)
			{
				case 'slot_id':
				case 'target':
					t.find('select[name="'+ f +'"] > option').removeAttr('selected');
					t.find('select[name="'+ f +'"] > option[value="'+ d[f] +'"]').prop('selected', true);
					break;

				case 'code':
					t.find('select[id="inpselect"] > option').removeAttr('selected').trigger('change');
					if (d[f])
					{
						t.find('select[id="inpselect"] > option').each(function ()
						{
							console.log($(this).val(), d[f]);
							if ($(this).val() == d[f])
								$(this).prop('selected', true);
						});
						t.find('select[id="inpselect"]').trigger('change');
					}
					break;

				case 'expired':
					let dt = (d.expired ? d.expired : new Date().format('Y-m-d H:i:s')).split(' ');
					t.find('input[name="expired_date"]').text(dt[0]).val(dt[0]);
					t.find('input[name="expired_time"]').text(dt[1].substr(0, 5)).val(dt[1].substr(0, 5));
					break;
			}
		});
	});


	$('#inpselect').on('change', function ()
	{
		if ($(this).val() == 'EMPTY')
		{
			$(this).next().removeAttr('readonly').val($(this).next().attr('data-before'));
		}
		else
		{
			$(this).find('option[value="'+ $(this).val() +'"]').prop('selected', true);
			$(this).next().prop('readonly', true).val($(this).val());
		}
	});


	//
	// 추가/변경 콜백 처리.
	//
	$('form').on('callback', function (e, d)
	{
		console.log(d);

		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 1)
			return false;

		document.location.reload();
	});



});

</script>

@endsection
