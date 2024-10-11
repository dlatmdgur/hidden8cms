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
								<h2><b>[ {{ $slot_id ? strtoupper($slot_id) : '' }} ] BET CUSTOMIZE</b></h2>

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
													<th rowspan="2" width="150">슬롯 ID</th>
													<th colspan="2">기준</th>
													<th rowspan="2" colspan="{{ $max_rate }}">배팅비율</th>
													<th rowspan="2" width="105">-</th>
												</tr>

												<tr>
													<th width="100">레벨</th>
													<th width="100">배팅금액</th>
												</tr>
											</thead>

											<tbody class="text-center text-nowrap align-middle">
@if (count($data) > 0)
	@foreach ($data AS $key => $row)

												<tr>
													<td>{{ $row->slot_id }}</td>

													<td class="text-right pl-3 pr-2" data-type="level">{{ $row->level }}</td>
													<td class="text-right pl-3 pr-2">{{ Helper::swNumberFormat($row->bet, 3) }}</td>

		@for ($i = 0; $i < count($row->bet_rate); $i++)
			@if (!empty($row->bet_rate[$i]))

													<td width="100">{{ $row->bet_rate[$i] }}<br />( {{ Helper::swNumberFormat($row->bet * $row->bet_rate[$i], 3) }} )</td>
			@else

													<td width="100">-</td>
			@endif
		@endfor

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



	<!--	배팅금액 추가/변경 : 시작						-->
	<div id="modify" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('slotgamesv2.set_betting') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<input type="hidden" name="slot_id" value="{{ $slot_id }}" />
				<div class="modal-header"><h5 class="modal-title">배팅데이터 추가/변경</h5></div>

				<div class="modal-body was-validated">
					<div class="form-group">
						<label for="level"><strong>기준 최소레벨</strong></label>
						<input type="number" class="form-control none-arrow" id="level" name="level" value="" placeholder="기준 최소레벨을 입력하세요." required >
						<div class="valid-feedback">게임에서 유저는 설정레벨 이상의 데이터를 사용합니다.</div>
						<div class="invalid-feedback">기준 최소레벨을 입력하세요.</div>
					</div>
					<div class="form-group">
						<label for="bet"><strong>기준 배팅금액</strong></label>
						<input type="number" class="form-control none-arrow" id="bet" name="bet" value="" placeholder="기준 배팅금액을 입력하세요." required >
						<div class="valid-feedback">배팅금액에 따라 배팅비율 구간이 달라집니다.</div>
						<div class="invalid-feedback">배팅금액을 입력하세요.</div>
					</div>
					<div class="form-group">
						<label for="bet_rate"><strong>배팅비율</strong></label>
						<input type="text" class="form-control none-arrow" id="bet_rate" name="bet_rate" value="" placeholder="배팅비율을 입력하세요." required >
						<div class="valid-feedback">기준 배팅금액에 (,)로 부분하는 배수를 곱한 값이 각 배팅금액입니다.</div>
						<div class="invalid-feedback">배팅비율을 입력하세요.</div>
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
				<form name="frmSet" method="post" action="{{ Route('slotgamesv2.drop_betting') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">배팅데이터 삭제</h5></div>
				<input type="text" name="slot_id" value="{{ $slot_id }}" />
				<input type="text" name="level" value="" />

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
	let cols = ['slot_id', 'level', 'bet', 'bet_rate'];


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
				case 'slot_id':
					break;

				case 'bet_rate':
					f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]].join(','));
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
	$('form[name="frmSet"]').on('callback', function (e, d)
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
