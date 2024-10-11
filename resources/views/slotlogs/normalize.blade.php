@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">슬롯 로그</a></li>
				<li class="breadcrumb-item">슬롯 일자별 통계</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<div class="pull-left">
									<h2>슬롯 일자별 통계</h2>
								</div>
							</div>
						</div>


@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
@endif


						<form name="frmLogs" method="get" action="/slotlogs/daily">
						<div class="row">
							<div class="col-12 col-xl-3 mb-1">
								<select name="slot_id" class="form-control">
									<option value="">-- 슬롯 전체 --</option>
@if (count($slots) > 0)
	@foreach ($slots AS $row)
									<option value="{{ $row->slot_id }}" {{ $slot_id == $row->slot_id ? 'SELECTED' : '' }}>{{ $row->slot_name }}</option>
	@endforeach
@endif
								</select>
							</div>
							<div class="col-12 col-lg-6 col-xl-4 mb-1 input-group">
								<input type="date" name="start_date" value="{{ $start }}" class="form-control mb-2" data-check="true" data-msg-false="검색 시작일을 선택하세요." />
							</div>
							<div class="col-12 col-lg-6 col-xl-4 mb-1 input-group">
								<input type="date" name="end_date" value="{{ $end }}" class="form-control mb-2" data-check="true" data-msg-false="검색 종료일을 선택하세요." />
							</div>
							<div class="col-12 col-lg-12 col-xl-1 mb-1">
								<button type="submit" class="btn btn-primary">검색</button>
							</div>
						</div>
						</form>


						<div class="row">
							<div class="col p-0">
								<table class="table table-sm table-bordered table-hover mb-0">
									<thead class="thead-dark text-center">
										<tr>
											<th rowspan="2">일자</th>
											<th rowspan="2">슬롯</th>
											<th rowspan="2">환급률</th>
											<th rowspan="2">총 베팅</th>
											<th rowspan="2">총(A+B+C+D)</th>
											<th colspan="4">PAYOUT</th>
											<th colspan="2">플레이 수</th>
											<th colspan="2">평균배팅</th>
											<th colspan="3">프리게임중</th>
											<th colspan="4">게임 진입수</th>
											<th rowspan="2">스핀로그</th>
										</tr>

										<tr>
											<th>일반(A)</th>
											<th>프리(B)</th>
											<th>보너스(C)</th>
											<th>잭팟(D)</th>

											<th>플레이게임수</th>
											<th>프리게임수</th>

											<th>일반</th>
											<th>프리</th>

											<th>스핀상금</th>
											<th>보너스상금</th>
											<th>잭팟상금</th>

											<th>프리</th>
											<th>보너스</th>
											<th>프리(프리게임중)</th>
											<th>보너스(프리게임중)</th>
										</tr>
									</thead>

									<tbody id="data">
@if (count($data) > 0)
	<?php $useDate = null; ?>
	@foreach ($data AS $idx1 => $row)
		@if ($row['slot_id'] == 'TOTAL')
										<tr class="text-center bg-warning">
		@else
										<tr class="text-center">
		@endif

		@if ($row['date'] != $useDate)
			<?php $useDate = $row['date']; ?>
											<td rowspan="{{ $date[$row['date']] }}" class="table-light">{{ $row['date'] }}</td>
		@endif

											<td>{{ $row['slot_id'] }}</td>

											<td class="text-right pr-3">{{ $row['rate'] }}</td>
											<td class="text-right pr-3">{{ $row['bet_total'] }}</td>
											<td class="text-right pr-3">{{ $row['win_total'] }}</td>

		@foreach ($row['pay'] AS $val)
											<td class="text-right pr-1 text-break">{{ $val[0] }}<span class="text-info" style="font-size: 11px;">({{ $val[1] }})</span></td>
		@endforeach

											<td class="text-right pr-3">{{ $row['game_played'] }}</td>
											<td class="text-right pr-3">{{ $row['game_played_f'] }}</td>

		@foreach ($row['avg_rate'] AS $val)
											<td class="text-right pr-1 text-break">
			@if (is_array($val))
												{{ $val[0] }} <span class="text-info" style="font-size: 11px;">({{ $val[1] }})</span>
			@else
												{{ $val }}
			@endif
											</td>
		@endforeach

		@foreach ($row['fp'] AS $val)
											<td class="text-right pr-1 text-break">
			@if (is_array($val))
												{{ $val[0] }} <span class="text-info" style="font-size: 11px;">({{ $val[1] }})</span>
			@else
												{{ $val }}
			@endif
											</td>
		@endforeach

		@foreach ($row['enter'] AS $val)
											<td class="text-right pr-1 text-break">
			@if (is_array($val))
												{{ $val[0] }} <span class="text-info" style="font-size: 11px;">({{ $val[1] }})</span>
			@else
												{{ $val }}
			@endif
											</td>
		@endforeach


											<td></td>
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

<script>

$(function ()
{
	//
	// 슬롯 데이터.
	//
	let data = {{ json_encode($data) }};

	//
	// 목록에 출력할 컬럼.
	//
	let cols = [
		'slot_id', 'rate', 'bet_total', 'win_total',
		'pay',
		'game_played', 'game_played_f',
		'avg_rate',
		'fp',
		'enter',
		'group1'
	];

	//
	// 추가/변경 버튼 이벤트.
	//
	$('#modify, #drop').on('show.bs.modal', function (e)
	{
		let d = data[$(e.relatedTarget).data('i')];
		let f = $(e.currentTarget).find('form');

		let s = d == undefined;


		for (var i in cols)
		{
			var elem = f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]]).removeAttr('readonly');

			switch (elem.attr('type'))
			{
				case 'checkbox':
					elem.val(1).prop('checked', d != undefined && d[cols[i]] == 1 ? true : false);
					break;
			}
		}

		if (!s)
			f.find('[name="slot_id"]').attr('readonly', 'readonly');
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
