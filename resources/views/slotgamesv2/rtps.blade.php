@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">슬롯 설정</a></li>
				<li class="breadcrumb-item">배팅정보 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb p-0">
								<h2><b>RTP BUFF CUSTOMIZE</b></h2>

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
													<th rowspan="3" width="100">버프 Index</th>
													<th rowspan="3" width="105">참고 순서</th>
													<th colspan="4">발동 조건</th>
													<th colspan="4">지급 조건</th>
													<th colspan="2" rowspan="2">버프 활성화</th>
													<th rowspan="3" width="60">활성화</th>
													<th rowspan="3" width="105">-</th>
												</tr>

												<tr>
													<th colspan="2">플레이</th>
													<th colspan="2">RTP</th>

													<th colspan="2">버프량(%)</th>
													<th colspan="2">유지시간</th>

												</tr>

												<tr>
													<th width="6%">최소</th>
													<th width="6%">최대</th>
													<th width="6%">최소</th>
													<th width="6%">최대</th>
													<th width="6%">최소</th>
													<th width="6%">최대</th>
													<th width="6%">최소</th>
													<th width="6%">최대</th>

													<th width="10%">날짜</th>
													<th width="10%">시간대</th>
												</tr>
											</thead>

											<tbody class="text-center text-nowrap align-middle">

@if (count($data) > 0)
	@foreach ($data AS $key => $row)

												<tr style="{{ intval(str_replace('-', '', $row->use_end)) < intval(date('Ymd')) ? 'background-color: #E0E0E0 !important; color: #999 !important;' : '' }}">
													<td>{{ $row->idx }}</td>

													<td class="text-right pl-3 pr-3">{{ $row->sorted }}</td>

													<td class="text-right pl-3 pr-3">{{ $row->play_min }}</td>
													<td class="text-right pl-3 pr-3">{{ $row->play_max }}</td>

													<td class="text-right pl-3 pr-3" data-type="avg">{{ $row->rtp_min }}</td>
													<td class="text-right pl-3 pr-3" data-type="avg">{{ $row->rtp_max }}</td>

													<td class="text-right pl-3 pr-3" data-type="avg">{{ $row->buff_min }}</td>
													<td class="text-right pl-3 pr-3" data-type="avg">{{ $row->buff_max }}</td>

													<td class="text-right pl-3 pr-3" data-type="seconds">{{ $row->expired_min }}</td>
													<td class="text-right pl-3 pr-3" data-type="seconds">{{ $row->expired_max }}</td>

													<td>{{ $row->use_start }} ~ {{ $row->use_end }}</td>
													<td>{{ $row->apply_start }} ~ {{ $row->apply_end }}</td>

													<td><span class="badge badge-{{ $row->status == 1 ? 'success' : 'danger' }} text-md p-2">{{ $row->status == 1 ? '사용' : '중지' }}</span></td>

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

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<!--	버프 추가/변경 : 시작						-->
	<div id="modify" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('slotgamesv2.set_rtp') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<input type="hidden" name="idx" value="" />
				<input type="hidden" name="buff_type" value="RTP" />
				<div class="modal-header"><h5 class="modal-title">RTP 버프 추가/변경</h5></div>

				<div class="modal-body was-validated">

					<label class="mb-0" for="sorted"><strong>검증 순서</strong></label>
					<div class="form-group">
						<input type="number" id="sorted" name="sorted" value="" class="form-control col-12 mb-1 none-arrow" placeholder="검증 순서" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback">버프를 우선 검증할 순서를 입력하세요.</div>
					</div>

					<hr class="mt-3" />

					<label class="mb-0" for="play_min"><strong>발동 조건 (플레이 횟수)</strong></label>
					<div class="form-group">
						<input type="number" id="play_min" name="play_min" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 플레이 횟수" required />
						<input type="number" id="play_max" name="play_max" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 플레이 횟수 ( 강제집행 )" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback">버프를 적용할 최소 플레이 횟수 또는 최대(무조건 적용) 값을 입력해야 합니다.</div>
					</div>

					<label class="mb-0" for="rtp_min"><strong>발동 조건 ( RTP 범위 )</strong></label>
					<div class="form-group">
						<input type="number" id="rtp_min" name="rtp_min" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 RTP" required />
						<input type="number" id="rtp_max" name="rtp_max" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 RTP" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback">버프를 적용할 RTP 범위를 입력하세요.</div>
					</div>

					<hr class="mt-3" />

					<label class="mb-0" for="buff_min"><strong>지급 조건 ( 버프량 % )</strong></label>
					<div class="form-group">
						<input type="number" id="buff_min" name="buff_min" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 버프량" required />
						<input type="number" id="buff_max" name="buff_max" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 버프량" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback">지급할 버프량을 입력하세요. 최소, 최대 사이의 임의값이 지급됩니다.</div>
					</div>

					<label class="mb-0" for="expired_min"><strong>지급 조건 ( 유지 시간 s )</strong></label>
					<div class="form-group">
						<input type="number" id="expired_min" name="expired_min" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최소 유지시간" required />
						<input type="number" id="expired_max" name="expired_max" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="최대 유지시간" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback">지급될 버프의 유지시간(s)을 입력하세요. 최소, 최대 사이의 임의 시간(s)값이 지급됩니다.</div>
					</div>

					<hr class="mt-3" />

					<label class="mb-0" for="use_start"><strong>버프 활성화 ( 날짜 )</strong></label>
					<div class="form-group">
						<input type="date" id="use_start" name="use_start" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="시작일" required />
						<input type="date" id="use_end" name="use_end" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="종료일" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback">버프를 사용할 날짜를 입력하세요.</div>
					</div>

					<label class="mb-0" for="apply_start"><strong>버프 활성화 ( 시간 )</strong></label>
					<div class="form-group">
						<input type="time" id="apply_start" name="apply_start" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="시작시간" required />
						<input type="time" id="apply_end" name="apply_end" value="" class="form-control col-12 col-sm-6 mb-1 none-arrow" placeholder="종료시간" required />
						<div class="valid-feedback">입력 완료</div>
						<div class="invalid-feedback"> 입력하세요.</div>
					</div>

					<hr class="mt-3" />

					<label for="status"><strong>활성화 여부</strong></label>
					<div class="form-group">
						<div class="input-group mb-1">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="status" name="status" value="1" />
								<label class="custom-control-label" for="status"></label>
							</div>
						</div>
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


	<!--	배팅금액 삭제 : 시작							-->
	<div id="drop" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('slotgamesv2.drop_rtp') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">배팅데이터 삭제</h5></div>
				<input type="text" name="idx" value="" />

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
				<form name="frmCmd" method="post" action="{{ Route('slotgamesv2.reset_rtp') }}" onsubmit="return false;" method-transfer="async" class="m-0">
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
	let cols = ['idx', 'sorted', 'play_min', 'play_max', 'rtp_min', 'rtp_max', 'buff_min', 'buff_max', 'expired_min', 'expired_max', 'use_start', 'use_end', 'apply_start', 'apply_end', 'status'];


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
				case 'use_start':
				case 'use_end':
					f.find('[name='+cols[i]+']').val(s ? '{{ date('Y-m-d') }}' : d[cols[i]]);
					break;


				case 'apply_start':
					f.find('[name='+cols[i]+']').val(s ? '00:00' : d[cols[i]].substr(0, 5));
					break;

				case 'apply_end':
					f.find('[name='+cols[i]+']').val(s ? '23:59' : d[cols[i]].substr(0, 5));
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
