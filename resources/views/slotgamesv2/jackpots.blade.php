@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">슬롯 설정</a></li>
				<li class="breadcrumb-item">기간잭팟 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb p-0">
								<h2><b>기간잭팟 설정</b></h2>

								<div class="pull-right pr-0">
									<button class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#modify"><b>추가</b></button>
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
													<th rowspan="2">번호</th>
													<th rowspan="2">집계 구분</th>
													<th rowspan="2">기간</th>

													<th rowspan="2">지정슬롯</th>

													<th colspan="3">필요 조건</th>

													<th colspan="2">지급 배수</th>

													<th rowspan="2">지급 유저 수</th>

													<th colspan="2">날짜 코드</th>

													<th rowspan="2">한줄 설명</th>

													<th rowspan="2">사용여부</th>
													<th rowspan="2">최근 업데이트</th>
													<th rowspan="2">-</th>
												</tr>

												<tr>
													<th>플레이 횟수 (최소)</th>
													<th>배팅 금액 (최소)</th>
													<th>기준RTP (최대)</th>

													<th>최소</th>
													<th>최대</th>

													<th>현재</th>
													<th>다음</th>
												</tr>
											</thead>

											<tbody class="text-center text-nowrap align-middle">

											@if (count($data) > 0)
												@foreach ($data AS $key => $row)

												<tr>
													<td>{{ $row->idx }}</td>

													<td style="{{ empty($datetype[$row->datetype]) ? '-' : $datetype[$row->datetype][1] }}">{{ $datetype[$row->datetype][0] }}</td>
													<td>{{ number_format($row->term) }}</td>

													<td class="pl-3 pr-3">{{ empty($row->slot_id) ? '전체' : $slots[$row->slot_id]->slot_name }} <small>{{ $row->slot_id }}</small></td>

													<td class="text-right pr-3 pl-3">{{ number_format($row->required_play) }}</td>
													<td class="text-right pr-3 pl-3">{{ number_format($row->required_bet) }}</td>
													<td class="text-right pr-3 pl-3" data-type="avg">{{ number_format($row->required_rtp, 2) }}</td>

													<td class="text-right pr-3 pl-3" data-type="multi">{{ number_format($row->reward_min) }}</td>
													<td class="text-right pr-3 pl-3" data-type="multi">{{ number_format($row->reward_max) }}</td>
													<td class="text-right pr-3 pl-3">{{ number_format($row->reward_user) }}</td>

													<td class="text-right pr-3 pl-3">{{ $row->usecode }}</td>
													<td class="text-right pr-3 pl-3">{{ $row->nextcode }}</td>

													<td class="text-left pr-3 pl-3">{{ $row->summary }}</td>

													<td><span class="badge badge-{{ $row->status == 1 ? 'success' : 'danger' }} text-md p-2">{{ $row->status == 1 ? '사용' : '중지' }}</span></td>

													<td>{{ $row->updated }}</td>

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

									<div class="card-footer">
										<ul class="alert alert-warning text-dark" role="alert">
											<li class="ml-2"><span>알림 작성 기준</span> {{ date('Y-m-d H:i:s')}}</li>
											<li class="ml-2"><span>집계구분</span> 집계구분은 시/일/주/월 로 설정 가능하고 날짜코드(현재) + 기간 기준으로 다음 회차가 정해집니다.</li>
											<li class="ml-2"><span>필요조건</span> 모두 충족해야 지급가능합니다. ( 사용안할 시  플레이횟수=0, 배팅금액=0, RTP=9999 입력바람 )</li>
											<li class="ml-2"><span>지급배수</span> 최소 ~ 최대 사이의 임의값으로 상금을 지급합니다.</li>
											<hr />
											<li class="ml-2"><span>날짜코드</span> 등록시 입력값(시작시간코드)를 기준으로 만들어지며, 다음회차의 코드는 집계구분|기간에 의해 자동 산정됩니다.</li>
										</ul>
									</div>

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
				<form name="frmSet" method="post" action="{{ Route('slotgamesv2.set_jackpot') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="idx" value="" />

					<div class="modal-header"><h5 class="modal-title">기간잭팟 추가/변경</h5></div>

					<div class="modal-body was-validated">

						<label class="mb-0" for="datetype"><strong>집계구분</strong></label>
						<div class="form-row mb-2">
							<select name="datetype" for="datetype" class="form-control" required></select>
							<div class="invalid-feedback">집계구분은 필수 입력입니다.</div>
						</div>

						<label class="mb-0" for="term"><strong>집계 기간</strong></label>
						<div class="form-row mb-2">
							<input type="number" id="term" name="term" value="0" step="1" min="0" max="99" maxlength="2" class="form-control col-12 col-sm-6 mb-1 text-right" placeholder="집계 기간" required />
							<div class="invalid-feedback">집계기간은 필수 입력입니다.</div>
						</div>

						<label class="mb-0" for="usecode"><strong>시작 시간 코드</strong></label>
						<div class="form-row mb-2" data-datetype="M" style="display: none;">
							<input type="month" id="usecode" name="month" value="" class="form-control col-12 col-sm-6 mb-1" placeholder="시작 월" required />
							<div class="invalid-feedback">시작할 월을 선택하세요. ( 시작할 날짜에 집계기간을 더해 기간이 정해집니다. )</div>
						</div>

						<div class="form-row mb-2" data-datetype="W" style="display: none;">
							<input type="week" id="usecode" name="week" value="" class="form-control col-12 col-sm-6 mb-1" placeholder="시작 주" required />
							<div class="invalid-feedback">시작할 주를 선택하세요. ( 시작할 날짜에 집계기간을 더해 기간이 정해집니다. )</div>
						</div>

						<div class="form-row mb-2" data-datetype="D" style="display: none;">
							<input type="date" id="usecode" name="date" value="" class="form-control col-12 col-sm-6 mb-1" placeholder="시작 날짜" required />
							<div class="invalid-feedback">시작할 날짜를 선택하세요. ( 시작할 날짜에 집계기간을 더해 기간이 정해집니다. )</div>
						</div>

						<div class="form-row mb-2" data-datetype="H" style="display: none;">
							<input type="date" id="usecode" name="date" value="" class="form-control col-12 col-sm-6 mb-1" placeholder="시작 날짜" required />
							<input type="number" name="hour" value="" step="1" min="0" max="23" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="시작 시" required />
							<div class="invalid-feedback">시작할 날짜와 시간을 선택(입력)하세요. ( 시작할 날짜에 집계기간을 더해 기간이 정해집니다. )</div>
						</div>

						<hr class="mt-3" />

						<label class="mb-0" for="slot_id"><strong>선택 슬롯</strong></label>
						<div class="form-row mb-2">
							<select name="slot_id" for="slot_id" class="form-control"></select>
							<small class="form-text text-muted">선택하지 않으면 모든 슬롯에 적용됩니다.</small>
						</div>

						<hr class="mt-3" />

						<label class="mb-0" for="required_play"><strong>필요 플레이 횟수 ( 최소 )</strong></label>
						<div class="form-row mb-2">
							<input type="number" id="required_play" name="required_play" value="0" step="1" min="0" max="10000000000" maxlength="6" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="필요 플레이 횟수" required />
							<div class="invalid-feedback">최소 요구조건으로 입력한 값 이상 플레이 해야 기간잭팟이 지급됩니다.<br /><b>사용안함 = 0</b></div>
						</div>

						<label class="mb-0" for="required_bet"><strong>필요 배팅 금액 ( 최소 )</strong></label>
						<div class="form-row mb-2">
							<input type="number" id="required_bet" name="required_bet" value="0" step="1" min="0" max="10000000000000000000" maxlength="20" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="필요 배팅 금액" required />
							<div class="invalid-feedback">최소 요구조건으로 입력한 값 이상 배팅금액으로 소비해야 기간잭팟이 지급됩니다.<br /><b>사용안함 = 0</b></div>
						</div>

						<label class="mb-0" for="required_rtp"><strong>필요 기준RTP ( 이하 )</strong></label>
						<div class="form-row mb-2">
							<input type="number" id="required_rtp" name="required_rtp" value="0" step="1" min="1" max="9999" maxlength="4" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="필요 기준RTP" required />
							<div class="invalid-feedback">RTP 요구조건으로 유저의 RTP가 입력한 값 이하여야 기간잭팟이 지급됩니다.<br /><b>사용안함 = 9999</b></div>
						</div>

						<hr class="mt-3" />

						<label class="mb-0" for="reward_min"><strong>지급 배수</strong></label>
						<div class="form-row mb-2">
							<input type="number" id="reward_min" name="reward_min" value="" step="1" min="10" max="2500" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 지급 배수" required />
							<input type="number" id="reward_max" name="reward_max" value="" step="1" min="10" max="2500" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 지급 배수" required />
							<div class="invalid-feedback">입력된 <strong>지급배수(최소/최대)</strong> 사이의 임의값이 지급됩니다.</div>
						</div>

						<label class="mb-0" for="reward_user"><strong>지급 유저 수</strong></label>
						<div class="form-row mb-2">
							<input type="number" id="reward_user" name="reward_user" value="0" step="1" min="1" max="999" maxlength="3" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="지급 유저 수" required />
							<div class="invalid-feedback"><strong>지급 유저</strong>는 최소 1 이상 입력하셔야 합니다.</div>
						</div>

						<hr class="mt-3" />

						<label for="status"><strong>활성화 여부</strong></label>
						<div class="form-row mb-2">
							<div class="input-group mb-1">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="status" name="status" value="1" />
									<label class="custom-control-label" for="status"></label>
								</div>
							</div>
						</div>

						<label for="summary"><strong>한줄 설명</strong></label>
						<div class="form-row mb-2">
							<input type="text" id="summary" name="summary" value="" maxlength="50" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="50자 내외 설명 추가" required />
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
				<form name="frmSet" method="post" action="{{ Route('slotgamesv2.drop_jackpot') }}" onsubmit="return false;" method-transfer="async" class="m-0">
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
			let cols = ['idx', 'datetype', 'term', 'slot_id', 'required_play', 'required_bet', 'required_rtp', 'reward_min', 'reward_max', 'reward_user', 'usecode', 'nextcode', 'summary', 'status', 'updated', 'group'];

			//
			// 표기 스타일.
			//
			let styles = {
				datetype: {
					'H':	{	id: 'H',	name: '시',		css: 'color: #FF8000 !important; text-shadow: 0 0 5px #FFC080 !important;'	},
					'D':	{	id: 'D',	name: '일',		css: 'color: #008000 !important; text-shadow: 0 0 5px #80C080 !important;'},
					'W':	{	id: 'W',	name: '주',		css: 'color: #0000FF !important; text-shadow: 0 0 5px #8080FF !important;'	},
					'M':	{	id: 'M',	name: '월',		css: 'color: #FF0000 !important; text-shadow: 0 0 5px #FF8080 !important;'	},
				}
			};


			//
			// 집계구분 변경 이벤트 처리.
			//
			$('select[name="datetype"]').on('change', function ()
			{
				let val = $(this).val();

				$.each($('[data-datetype]'), function ()
				{
					if ($(this).data('datetype') == val)
					{
						$(this).show(0);
						$(this).find('input').prop('disabled', false);
					}
					else
					{
						$(this).hide(0);
						$(this).find('input').prop('disabled', true);
					}
				});
			});



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
						case 'datetype':
							let fem = f.find('[name='+cols[i]+']');
							fem.html('');
							fem.append($('<option>').val('').text('▒ 집계구분 선택 ▒'));

							for (var x in styles[cols[i]])
								fem.append($('<option>').val(x).text(styles[cols[i]][x].name));

							if (data[t] != undefined &&
								data[t][cols[i]] != undefined &&
								data[t][cols[i]] != null)
								fem.find('option[value="'+ data[t][cols[i]] +'"]').attr('selected', true);

							fem.trigger('change');

							if (!s)
							{
								if (data[t].month)
									f.find('input[name="month"]').val(data[t].month);
								if (data[t].weekst)
									f.find('input[name="week"]').val(data[t].weekst);
								if (data[t].date)
									f.find('input[name="date"]').val(data[t].date);
								if (data[t].hour)
									f.find('input[name="hour"]').val(data[t].hour);
							}
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
