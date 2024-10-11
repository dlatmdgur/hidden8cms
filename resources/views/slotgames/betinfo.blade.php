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
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<div class="pull-left">
									<h2>배팅정보 설정</h2>
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
								<form name="frmSlot" method="post" action="/slotgames/slotlist" onsubmit="return false;" method-transfer="async" class="m-0">
								<table class="table table-sm table-bordered table-hover mb-0">
									<thead class="thead-dark">
										<tr class="text-center">
											<th rowspan="2">슬롯 ID</th>
											<th rowspan="2">등급</th>
											<th rowspan="2">VIP POINT</th>
											<th rowspan="2">레벨제한</th>
											<th colspan="2">배팅금액</th>
											<th rowspan="2" colspan="10">배팅배율</th>
											<th rowspan="2" width="105">-</th>
										</tr>
										<tr class="text-center">
											<th>최소</th>
											<th>최대</th>
										</tr>
									</thead>

									<tbody id="data">
@if (count($data) > 0)
	@foreach ($data AS $index => $row)

										<tr class="text-center">
											<td class="text-left pl-3">{{ $row->slot_id }}</td>
											<td>{{ $row->room }}</td>
											<td>{{ $row->vip_point }}</td>
											<td>{{ $row->lv_condition }}</td>
											<td>{{ $row->bet_min }}</td>
											<td>{{ $row->bet_max }}</td>
		@for ($i = 0; $i < 10; $i++)
			@if (!empty($row->bet_rate_array[$i]))

											<td>{{ $row->bet_rate_array[$i] }}<br />( {{ $row->bet_min * $row->bet_rate_array[$i] }} )</td>
			@else

											<td>-</td>
			@endif
		@endfor

											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-primary" data-i="{{ $index }}" data-toggle="modal" data-size="modal-xl" data-target="#modify">변경</button>
													<button type="button" class="btn btn-sm btn-secondary" data-i="{{ $index }}" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>
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
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div id="modify" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<form name="frmSet" method="post" action="/slotgames/betinfo_set" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">배팅정보 추가(변경)</h5></div>
				<div class="modal-body">
					<table class="table table-sm">
						<colgroup>
							<col width="20%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th class="table-dark">슬롯 ID</th>
								<td><input type="text" name="slot_id" value="{{ $slot_id }}" class="form-control form-control-sm col-12 col-sm-6" readonly /></td>
							</tr>
							<tr>
								<th class="table-dark">등급</th>
								<td>
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="room" value="novice" />novice</label>
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="room" value="vip" />vip</label>
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="room" value="amateur" />amateur</label>
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="room" value="expert" />expert</label>
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="room" value="master" />master</label>
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="room" value="rich" />rich</label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">VIP POINT</th>
								<td><input type="number" name="vip_point" value="0" class="form-control form-control-sm col-12 col-sm-6 none-arrow" /></td>
							</tr>
							<tr>
								<th class="table-dark">레벨 제한</th>
								<td><input type="number" name="lv_condition" value="0" class="form-control form-control-sm col-12 col-sm-6 none-arrow" /></td>
							</tr>
							<tr>
								<th class="table-dark">배팅금액 (최소/최대)</th>
								<td class="input-group">
									<input type="number" name="bet_min" value="1" class="form-control form-control-sm col-6 col-md-3 none-arrow" /></div>
									<input type="number" name="bet_max" value="1" class="form-control form-control-sm col-6 col-md-3 none-arrow" /></div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">배팅비율</th>
								<td class="row">
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
									<div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
                                    <div class="col-7 col-sm-4 col-md-3 col-xl-1 mb-1"><input type="number" name="rate[]" value="0" step="1" min="1" max="20000" class="form-control form-control-sm none-arrow" /></div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" aria-label="modify">추가(변경)</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	<div id="drop" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="/slotgames/betinfo_drop" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">배팅정보 삭제</h5></div>

				<div class="modal-body">
					<input type="hidden" name="slot_id" value="" />
					<input type="hidden" name="room" value="" />
					정말 삭제하시겠습니까?
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" aria-label="modify">삭제</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	<div id="deploy" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="/cmd/set" onsubmit="return false;" method-transfer="async" class="m-0">
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
	let cols = ['slot_id', 'room', 'vip_point', 'lv_condition', 'bet_min', 'bet_max', 'bet_rate_array'];


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

				case 'bet_rate_array':
					var em = f.find('input[name="rate[]"]');
					for (var j in em)
						em.eq(j).val(s ? 0 : d[cols[i]][j]);
					break;

				case 'room':
					var em = f.find('input[name="'+ cols[i] +'"]');

					if (em.attr('type') == 'radio')
					{
						for (var j in em)
						{
							if (s)
							{
								if (j == 0)
									em.eq(j).parent().button('toggle');
							}
							else
							{
								if (em.eq(j).val() == d[cols[i]])
									em.eq(j).parent().button('toggle');
							}
						}
					}
					else
					{
						em.val(s ? '' : d[cols[i]]);
					}
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
