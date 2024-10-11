@extends('layouts.mainlayout')

@section('content')

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">게임 설정</a></li>
				<li class="breadcrumb-item">슬롯별 RTP 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title pb-0">
							<div class="col margin-tb">
@include('rtps.nav')
							</div>
						</div>


						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-sm table-striped table-bordered table-hover mb-0">
									<thead class="thead-dark text-center align-top">
										<tr>
											<th>SLOT</th>
											<th>RTP SETTING</th>
											<th>LAST UPDATED</th>
										</tr>
									</thead>

									<tbody id="list" class="text-center text-nowrap align-middle">
@if (!empty($rtps) && count($rtps) > 0)
	@foreach ($rtps AS $key => $row)

										<tr class="text-center" data-slot_id="{{ $row->slot_id }}" data-slot_name="{{ $row->name_kr }}">
											<td>{{ $row->name_kr }} <span class="text-muted">{{ $row->slot_id }}</span></td>
											<td>
												<div class="input-group m-0 d-flex justify-content-center">
													<div class="input-group-prepend">
														<select name="rtp" class="form-control form-control-sm m-0">
															<option value="">▒ RTP 선택 ▒</option>
		@foreach ($rtp_range AS $key => $val)
															<option value="{{ $val.":".$key }}" {{ $row->rtp == $val ? 'SELECTED' : '' }}>{{ $val }} %</option>
		@endforeach
														</select>
													</div>
													<button type="button" data-method="append" class="btn btn-sm btn-success m-0" data-toggle="modal" data-target="#apply">적용</button>
												</div>
											</td>
											<td>{{ $row->updated ?? '-' }}</td>
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
				<form name="frmApply" method="post" action="{{ Route('rtp.slot.set') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="slot_id" value="" />
					<input type="hidden" name="rtp" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>SLOT RTP SETTING</b></h5></div>

					<div class="modal-body">
						<p class="m-1"><b>적용 슬롯 : </b><span id="slot-info" class="ml-3"></span></p>
						<p class="m-1"><b>적용 RTP : </b><span id="rtp" class="ml-3"></span></p>
						<hr />

						<p class="pt-3 pb-3 text-center text-danger">적용 하시겠습니까?</p>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" aria-label="apply">적용</button>
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



	//
	// 지급 모달 처리.
	//
	$('#apply').on('show.bs.modal', function (e)
	{

		let emb		= $(e.relatedTarget);
		let emf		= $(e.currentTarget);

		let emr		= emb.parents('tr');

		let slot_id		= emr.data('slot_id');
		let slot_name	= emr.data('slot_name');
		let rtp			= emr.find('select[name="rtp"]').val().split(':');

		emf.find('input[name="slot_id"]').val(slot_id);
		emf.find('input[name="rtp"]').val(rtp[0]);

		emf.find('#slot-info').text(slot_name +' ( '+ slot_id +' )');
		emf.find('#rtp').text(rtp[0] +' %');
	});



	//
	// 추가/변경 콜백 처리.
	//
	frm.on('callback', function (e, d)
	{
		if (d.msg)
			alert(d.msg);

		if (d.result != 0)
			return false;

		document.location.reload();
	});



});

</script>

@endsection
