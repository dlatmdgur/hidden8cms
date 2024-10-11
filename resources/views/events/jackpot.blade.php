@extends('layouts.mainlayout')

@section('content')

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">게임 설정</a></li>
				<li class="breadcrumb-item">이벤트 잭팟 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-9 margin-tb">
								<div class="pull-left">
									<h2>이벤트 잭팟 설정</h2>
								</div>
							</div>
						</div>


						<form name="frm" method="get" action="{{ route('event.jackpot.search') }}">
						<div class="input-group">
							<div class="input-group-prepend">
								<select name="type" class="form-control">
									<option value="nickname" {{ $type == 'nickname' ? 'SELECTED' : '' }}>닉네임</option>
									<option value="id" {{ $type == 'id' ? 'SELECTED' : '' }}>유저ID</option>
									<option value="userseq" {{ $type == 'userseq' ? 'SELECTED' : '' }}>유저번호</option>
								</select>
							</div>
							<input type="text" name="keyword" value="{{ $keyword }}" class="form-control" />

							<div class="input-group-append">
								<button type="submit" class="btn btn-secondary">검색</button>
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
											<th>PLAY</th>
											<th>BET</th>
											<th>WIN</th>
											<th>RTP</th>
											<th>이벤트 딜레이</th>
											<th>지급 RTP</th>
											<th>-</th>
										</tr>
									</thead>

									<tbody id="list" class="text-center text-nowrap align-middle">
@if (!empty($result) && count($result) > 0)
	@foreach ($result AS $key => $row)

										<tr class="text-center" data-user-seq="{{ $row->user_seq }}" data-nickname="{{ $row->nickname }}">
											<td>{{ ++$key }}</td>
											<td>{{ $row->user_seq }}</td>
											<td>{{ $row->nickname }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->play) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->spend) }}</td>
											<td class="pl-3 pr-3 text-right">{{ number_format($row->win) }}</td>
											<td class="pl-3 pr-3 text-right" data-type="avg">{{ number_format($row->rtp, 2) }}</td>
											<td>
											<div class="input-group m-0 d-flex justify-content-center">
												<div class="input-group-prepend">
													<select name="event_delay_min" class="form-control form-control-sm m-0">
														@php $evtDelayTimes = explode(',' ,env('EVENT_DELAY_TIMES')); @endphp
														@foreach ($evtDelayTimes as $min)
														<option value="{{ $min }}"> {{ number_format($min) }}&nbsp;min</option>
														@endforeach
													</select>
												</div>
												</div>
											</td>

											<td>
												<div class="input-group m-0 d-flex justify-content-center">
													<div class="input-group-prepend">
														<select name="rate" class="form-control form-control-sm m-0">
		@foreach ($range AS $avg)
															<option value="{{ $avg }}">× {{ number_format($avg) }}</option>
		@endforeach
														</select>
													</div>

													<button type="button" data-method="append" class="btn btn-sm btn-success m-0" data-toggle="modal" data-target="#apply">지급</button>
												</div>
											</td>
											<td>
												<button data-toggle="modal" data-user-seq="{{ $row->user_seq }}"data-target="#event-log" id="view-log" class="btn btn-sm btn-info m-0">로그보기</button>
											</td>
										</tr>
	@endforeach
@else
										<tr>
											<td colspan="999" class="text-center p-3"><b><i>{{ empty($keyword) ? '검색해주세요.' : '회원을 찾을 수 없습니다.' }}</i></b></td>
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

	<!--	포인트 상세내역 : 시작						-->
	<div id="apply" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmApply" method="post" action="{{ Route('event.jackpot.apply') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />
					<input type="hidden" name="multiple" value="" />
					<input type="hidden" name="event_delay_min" value="60"/>


					<div class="modal-header"><h5 class="modal-title"><b>이벤트 잭팟 지급</b></h5></div>

					<div class="modal-body">
						<p class="m-1"><b>지급 대상 : </b><span id="nickname" class="ml-3">닉네임 ( 12345 )</span></p>
						<p class="m-1"><b>지급 배수 : </b><span id="multiple" class="ml-3">x100</span></p>
						<p class="m-1"><b>지급 지연시간(분) : </b><span id="event_delay_min" class="ml-3">x100</span></p>
						<hr />

						<p class="pt-3 pb-3 text-center text-danger">지급 하시겠습니까?</p>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" aria-label="apply">지급</button>
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--	포인트 상세내역 : 종료						-->

	<!-- 로그 리스트: 시작 -->
	 <!--	포인트 상세내역 : 시작						-->
	<div id="event-log" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md modal-xl">
			<div class="modal-content" style="overflow-y:auto;">
				<!-- <form name="frmApply" method="post" onsubmit="return false;" method-transfer="async" class="m-0"> -->
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>이벤트 잭팟 지급</b></h5></div>

					<div class="modal-body">

					</div>

					<div class="modal-footer">
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				<!-- </form> -->
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
