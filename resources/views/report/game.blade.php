@extends('layouts.mainlayout')
@section('content')

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('report.player') }}">리포트</a></li>
				<li class="breadcrumb-item">게임별</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-9 margin-tb">
								<div class="pull-left">
									<h2>게임별</h2>
								</div>
							</div>
						</div>


						<form name="frm" method="get" action="{{ route('report.game') }}">
						<div class="input-group">
							<div class="col-12 col-lg-3 col-xl-3 mb-1 input-group">
								<select type="date" name="slot_id" value="{{ $slot_id }}" class="form-control mb-2" data-check="true" data-msg-false="검색 시작일을 선택하세요.">
									<option value="">선택안함</option>
									<?php foreach ($slots as $id => $name): ?>
										<option value="{{ $id }}" {{ $slot_id === $id ? 'selected' : ''}}> {{ $name.' ('.$id.')'}}</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="col-12 col-lg-6 col-xl-4 mb-1 input-group">
								<input type="date" name="sdate" value="{{ $sdate }}" class="form-control mb-2" data-check="true" data-msg-false="검색 시작일을 선택하세요." />
							</div>
							<div class="col-12 col-lg-6 col-xl-4 mb-1 input-group">
								<input type="date" name="edate" value="{{ $edate }}" class="form-control mb-2" data-check="true" data-msg-false="검색 종료일을 선택하세요." />
							</div>
							<div class="col-12 col-lg-12 col-xl-1 mb-1">
								<button type="submit" class="btn btn-primary">검색</button>
							</div>
						</div>
						</form>


						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-sm table-striped table-bordered table-hover mb-0">
									<thead class="thead-dark text-center align-top">
										<tr>
											<th>DATE</th>
											<th>SLOT</th>
											<th>TOTAL_PLAY</th>
											<th>USERS</th>
											<th>PLAY/USERS</th>
											<th>TOTAL BET</th>
											<th>TOTAL PAYOUT</th>
											<th>WIN/LOSE</th>
											<th>WIN/RATE</th>
											<th>RTP</th>
										</tr>
									</thead>

									<tbody id="list" class="text-center text-nowrap align-middle">
									@forelse($result as $row)
										<tr class="text-center">
											<td>{{ $row->datecode }}</td>
											<td class="pl-3 pr-3 text-center">{{ $row->slot_name }}<small class="text-muted ml-2">{{$row->slot_id }}</small></td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->tot_play) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->user_cnt) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->play_users) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->tot_bet) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->tot_payout) }}</td>
											<td class="pl-3 pr-3 text-right text-{{ $row->win_lose > 0 ? 'success' : 'danger' }}">
												{{ ($row->win_lose > 0 ? '+' : '').number_format($row->win_lose) }}
											</td>
											<td class="pl-3 pr-3 text-right" data-type="avg">
												{{  number_format($row->win_rate, 2) }}
											</td>
											<td class="pl-3 pr-3 text-right" data-type="avg">
												{{  number_format($row->rtp, 2) }}
											</td>
										</tr>
									@empty
									<tr>
										<td colspan="999" class="data-not-found">데이터가 존재하지 않습니다</td>
									</tr>
									@endforelse
									<?php if (count($result) > 1): ?>
									<tr class="text-center bg-warning">
											<td colspan="2">TOTAL</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($total->play) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($total->user_cnt) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($total->play_users) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($total->bet)}}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($total->payout) }}</td>
											<td class="pl-3 pr-3 text-right text-{{ $total->win_lose > 0 ? 'success' : 'danger' }}">
												{{ ($total->win_lose > 0 ? '+' : '').number_format($total->win_lose) }}
											</td>
											<td class="pl-3 pr-3 text-right" data-type="avg">{{ number_format($total->win_rate, 2) }}</td>
											<td class="pl-3 pr-3 text-right" data-type="avg">{{ number_format($total->rtp, 2) }}</td>
									</tr>
									<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

<script src="/js/apis.js"></script>
<script>

$(function ()
{
	let frm = $('form[name="frmApply"]');

	$('#apply').on('show.bs.modal', function (e)
	{
		let emb		= $(e.relatedTarget);
		let emf		= $(e.currentTarget);

		let emr		= emb.parents('tr');


		let seq		= emr.data('user-seq');
		let nick	= emr.data('nickname');
		let multi	= emr.find('select[name="rate"]').val();
		let evtDelayMin = emr.find('select[name="event_delay_min"]').val();


		emf.find('#nickname').text(nick + ' ( '+ seq +' )');

		emf.find('input[name="user_seq"]').val(seq);
		emf.find('input[name="event_delay_min"]').val(evtDelayMin);

		emf.find('#multiple').text('× '+ multi);
		emf.find('input[name="multiple"]').val(multi);
		emf.find('#event_delay_min').text(evtDelayMin+'분(min)');
		// emf.find('input[name=""]').val(multi);

	});

	//
	// 추가/변경 콜백 처리.
	//
	frm.on('callback', function (e, d)
	{
		console.log('CALLBACK : ', d);

		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 0)
			return false;

		document.location.reload();
	});

	$('#event-log').on('show.bs.modal', function(e) {

		let emb		= $(e.relatedTarget);
		let emf		= $(e.currentTarget);

		emf.find('.modal-header .modal-title > b').text('이벤트로그')
		emf.find('.modal-body').empty();

		emf.find('.modal-content input[name="user_seq"]').val(emb.data('user-seq'));

		$.get({
				url: "/event/jackpot/"+ emb.data('user-seq'),
				dataType: 'json',
				success: function (d)
				{
					emf.find('.modal-body').append(d.html);
				}
			});

	});


});

</script>

@endsection
