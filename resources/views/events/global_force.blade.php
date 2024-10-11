@extends('layouts.mainlayout')

@section('content')

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">게임 설정</a></li>
				<li class="breadcrumb-item">글로벌 잭팟 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title pb-0">
							<div class="col margin-tb">
								<nav class="nav nav-boxs">
									<a class="nav-link {{ $active == 'panel' ? 'active' : '' }}" href="{{ route('global.jackpot.panel') }}">ON / OFF</a>
									<a class="nav-link {{ $active == 'force' ? 'active' : '' }}" href="{{ route('global.jackpot.force') }}">FORCE JACKPOT</a>
								</nav>
							</div>
						</div>


						<form name="frm" method="get" action="{{ route('global.jackpot.force') }}" class="mt-3">
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
											<th>JACKPOT TIER</th>
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
														<select name="tier" class="form-control form-control-sm m-0">
															<option value="">▒ 지급 선택 ▒</option>
		@foreach ($range AS $val)
															<option value="{{ $val }}">{{ $val }}</option>
		@endforeach
														</select>
													</div>

													<button type="button" data-method="append" class="btn btn-sm btn-success m-0" data-toggle="modal" data-target="#apply">지급</button>
												</div>
											</td>
											<td>
											{{-- <button class="btn btn-sm btn-info" data-popup="{{ route('global.jackpot.forcelog') }}?user_seq={{ $row->user_seq }}&page=1&offset=20" data-toggle="modal" data-target="#history">로그 보기</button> --}}

												<button class="btn btn-sm btn-info" data-history data-toggle="modal" data-target="#history">로그 보기</button>
											</td>
										</tr>
	@endforeach
@else
										<tr>
											<td colspan="999" class="text-center p-3"><b><i>잭팟 데이터가 없습니다.</i></b></td>
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
				<form name="frmApply" method="post" action="{{ Route('global.jackpot.apply') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />
					<input type="hidden" name="tier" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>글로벌 잭팟 지급</b></h5></div>

					<div class="modal-body">
						<p class="m-1"><b>지급 대상 : </b><span id="nickname" class="ml-3">닉네임 ( 12345 )</span></p>
						<p class="m-1"><b>지급 잭팟 : </b><span id="tier" class="ml-3">MINI</span></p>

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


	<!--	포인트 상세내역 : 시작						-->
	<div id="history" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<form name="fmGet" method="get" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />
					<input type="hidden" name="offset" value="10" />
					<input type="hidden" name="page" value=""/>
					<div class="modal-header"><h5 class="modal-title"><b>FORCE JACKPOT LOG</b></h5></div>

					<div class="modal-body">

					</div>

					<div class="modal-footer">
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--	포인트 상세내역 : 종료						-->



<script src="/js/apis.js"></script>
<script>

$(function ()
{
	let frm = $('form[name="frmApply"]');

	let fm = $('form[name="fmGet"]');

	//
	// 지급 모달 처리.
	//
	$('#apply').on('show.bs.modal', function (e)
	{

		let emb		= $(e.relatedTarget);
		let emf		= $(e.currentTarget);

		let emr		= emb.parents('tr');


		let seq		= emr.data('user-seq');
		let nick	= emr.data('nickname');
		let tier	= emr.find('select[name="tier"]').val();


		emf.find('#nickname').text(nick + ' ( '+ seq +' )');

		emf.find('input[name="user_seq"]').val(seq);

		emf.find('#tier').text(tier);
		emf.find('input[name="tier"]').val(tier);
	});



	//
	// 로그 팝업.
	//
	$('[data-popup]').on('click', function ()
	{
		let opts	= {};

		opts.width	= 900;
		opts.height	= 600;
		opts.left	= (parseInt($(window)[0].screen.availWidth) / 2) - (opts.width / 2) - 20;
		opts.top	= (parseInt($(window)[0].screen.availHeight) / 2) - (opts.height / 2) - 100;


		let strOpts = [];
		for (var i in opts)
			strOpts.push([i, opts[i]].join('='));

		window.open($(this).data('popup'), 'forcelog', strOpts.join(','));
	});


	fm.on('submit', function(e){
		$(e.preventDefault);
		const mb = $(this).find('.modal-body');
		mb.empty();
		$.get({
			url: ['/global/jackpot/forcelog', $(this).serialize()].join('?'),
			dataType: 'json',
			success: function(d)
			{
				mb.append(d.html);
			}
		});


	});
	//
	// 히스토리 모달 처리
	//
	$('#history').on('show.bs.modal', function (e)
	{

		let emb		= $(e.relatedTarget);
		let emf		= $(e.currentTarget);

		let emr		= emb.parents('tr');

		let seq		= emr.data('user-seq');

		emf.find('input[name="user_seq"]').val(seq);
		$(this).find('.modal-content .modal-body').empty();

		fm.submit();
	});


	//
	// 추가/변경 콜백 처리.
	//
	frm.on('callback', function (e, d)
	{
		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 0)
			return false;

		document.location.reload();
	});

});

</script>

@endsection
