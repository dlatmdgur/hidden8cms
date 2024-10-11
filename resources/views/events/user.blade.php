@extends('layouts.mainlayout')

@section('content')

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">슬롯 로그</a></li>
				<li class="breadcrumb-item">기간잭팟 유저</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-9 margin-tb">
								<div class="pull-left">
									<h2>기간잭팟 유저</h2>
								</div>
							</div>
						</div>


@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
@endif


						<form name="frmLogs" method="get" action="/slotlogsv2/jackpotusers">
						<input type="hidden" name="datetype" value="" />
						<div class="row">
							<div class="col-12 col-lg-3 mb-1">
								<select name="idx" id="idx" class="form-control">
									<option value="">▒ 기간잭팟 선택 ▒</option>
@if (!empty($groups) && count($groups))
	@foreach ($groups AS $status => $rows)

									<optgroup data-status="{{ $status }}" label="{{ $status == 1 ? '사용' : '중지' }}">
		@foreach ($rows AS $jidx)

										<option value="{{ $jidx }}" v2="{{ $idx }}" data-datetype="{{ $jackpots[$jidx]->datetype }}"{{ $idx == $jidx ? ' SELECTED' : '' }}>{{ $jackpots[$jidx]->summary }}</option>
		@endforeach

									</optgroup>
	@endforeach
@endif
								</select>
							</div>
							<div class="col-12 col-lg-6 mb-1" data-group="M" style="display: none;">
								<div class="row">
									<div class="col-12 col-sm-6 mb-1"><input type="month" name="smonth" value="{{ empty($smonth) ? date('Y-m') : $smonth }}" class="form-control" placeholder="검색 시작 월" /></div>
									<div class="col-12 col-sm-6 mb-1"><input type="month" name="emonth" value="{{ empty($emonth) ? date('Y-m') : $emonth }}" class="form-control" placeholder="검색 종료 월" /></div>
								</div>
							</div>
							<div class="col-12 col-lg-6 mb-1" data-group="W" style="display: none;">
								<div class="row">
									<div class="col-12 col-sm-6 mb-1"><input type="week" name="sweek" value="{{ empty($sweek) ? str_replace('-', '-W', date('Y-W')) : $sweek }}" class="form-control" placeholder="검색 시작 주" /></div>
									<div class="col-12 col-sm-6 mb-1"><input type="week" name="eweek" value="{{ empty($eweek) ? str_replace('-', '-W', date('Y-W')) : $eweek }}" class="form-control" placeholder="검색 종료 주" /></div>
								</div>
							</div>
							<div class="col-12 col-lg-6 mb-1" data-group="D" style="display: none;">
								<div class="row">
									<div class="col-12 col-sm-6 mb-1"><input type="date" name="sdate" value="{{ empty($sdate) ? date('Y-m-d') : $sdate }}" class="form-control" placeholder="검색 시작 일" /></div>
									<div class="col-12 col-sm-6 mb-1"><input type="date" name="edate" value="{{ empty($edate) ? date('Y-m-d') : $edate }}" class="form-control" placeholder="검색 종료 일" /></div>
								</div>
							</div>
							<div class="col-12 col-lg-6 mb-1" data-group="H" style="display: none;">
								<div class="row">
									<div class="col-12 col-sm-4 mb-1"><input type="date" name="date" value="{{ empty($date) ? date('Y-m-d') : $date }}" class="form-control" placeholder="검색 시작 일" /></div>
									<div class="col-12 col-sm-4 mb-1"><input type="number" name="stime" value="{{ empty($stime) ? 0 : $stime }}"  step="1" min="0" max="23" class="form-control" placeholder="검색 시작 시간" /></div>
									<div class="col-12 col-sm-4 mb-1"><input type="number" name="etime" value="{{ empty($etime) ? 23 : $etime }}" step="1" min="0" max="23" class="form-control" placeholder="검색 종료 시간" /></div>
								</div>
							</div>
							<div class="col-12 col-lg-3 mb-1">
								<button type="submit" class="btn btn-primary pl-4 pr-4">검색</button>
							</div>
						</div>
						</form>


						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-sm table-striped table-bordered table-hover mb-0">
									<thead class="thead-dark text-center align-top">
										<tr>
											<th>번호</th>
											<th>유저 SEQ</th>
											<th>닉네임</th>
											<th>BET</th>
											<th>WIN</th>
											<th>RTP</th>
											<th>-</th>
										</tr>
									</thead>

									<tbody id="list" class="text-center text-nowrap align-middle">
@if (!empty($users) && count($users) > 0)
	@foreach ($users AS $row)

										<tr>
											<td>{{ $row->idx }}</td>
											<td>{{ $row->jackpot_idx }}</td>
											<td class="text-left pl-3">{{ $jackpots[$row->jackpot_idx]->summary }}</td>
											<td>{{ $row->uid }}</td>
											<td>{{ $row->nowcode }}</td>
											<td>{{ $row->slot_id }}</td>
											<td>{{ $row->reward_rate }}</td>
											<td>{{ $row->created }}</td>
										</tr>
	@endforeach
@else

										<tr>
											<td colspan="999" class="text-center p-3"><b><i>{{ $msg }}</i></b></td>
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


<style>

select > optgroup[data-status="1"] { background-color:	rgba(128,192,128,.5); }
select > optgroup[data-status="0"] { background-color:	rgba(255,128,128,.5); }

</style>


<script>


$(function ()
{
	let frm = $('form[name="frmLogs"]');

	//
	// SELECT 선택 이벤트 정의.
	//
	frm.find('select[name="idx"]').on('change', function ()
	{
		let dt = $(this).find('[value="'+$(this).val()+'"]').data('datetype');

		frm.find('input[name="datetype"]').val(dt);

		frm.find('[data-group]').hide(0);
		frm.find('[data-group="'+ dt +'"]').show(0);

	}).trigger('change');

});



</script>

@endsection
