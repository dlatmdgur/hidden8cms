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


						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<div class="card d-flex flex-row justify-content-start p-2">
										<table class="table table-sm table-striped table-bordered" style="width:40%;">
											<colgroup>
												<col width="60%">
												<col width="40%">
											</colgroup>
											<thead class="text-center align-top">
												<tr class="table-primary" style="white-space:nowrap; word-break: break-all;">
													<th>GLOBAL JACKPOT</th>
													<th>ON / OFF</th>
												</tr>
												<tr>
													<td>ALL SLOTS</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="all-status" data-slot="all" data-target="모든" class="custom-control-input" value="0">
															<label class="custom-control-label" for="all-status"></label>
														</div>
													</td>
												</tr>
												@foreach ($slots as $slot)
												<tr>
													<td> {{ $slot->name }}</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="{{ $slot->id }}-status" data-slot="{{ $slot->id }}" data-target=" {{ $slot->name }}" class="custom-control-input"  {{ $slot->use_royal_jackpot ? 'checked' : '' }} value="{{ $slot->use_royal_jackpot }}">
															<label class="custom-control-label" for="{{ $slot->id }}-status"></label>
														</div>
													</td>
												</tr>
												@endforeach
											</thead>
										</table>
									</div>
								<table class="table table-sm table-striped table-bordered table-hover mb-0">
									<thead class="thead-dark text-center align-top">
										<tr>
											<th>JACKPOT TIER</th>
											<th>CURRENT POT</th>
											<th>ON / OFF</th>
											<th>HISTORY</th>
										</tr>
									</thead>

									<tbody id="list" class="text-center text-nowrap align-middle">
@if (!empty($tiers) && count($tiers) > 0)
	@foreach ($tiers AS $key => $row)
	@php ( $status_id = 'status-'.$row->idx )
										<tr class="text-center">
											<td>{{ strtoupper($row->tier) }}</td>
											<td class="text-right pr-5">{{ number_format($row->balance) }}</td>
											<td>
												<div class="custom-control custom-switch">
													<input type="checkbox" id="{{ $status_id }}" class="custom-control-input" data-tier="{{ $row->idx }}" value="{{ $row->idx }}" {{ intval($row->status) === 1 ? "checked=checked" : "" }}>
													<label class="custom-control-label" for="{{ $status_id }}"></label>
												</div>
											</td>
											<td>
												<button type="button" data-type="{{ $row->tier }}" data-toggle="modal" data-target="#history"  data-history class="btn btn-primary period-selector" >HISTORY</button>
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

	<!-- 로그 리스트: 시작 -->
	<!--	포인트 상세내역 : 시작						-->
	<div id="history" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md modal-xl">
			<div class="modal-content" style="overflow-y:auto; margin-top:50px; white-space:nowrap; word-break: break-all;">
				<form name="fmGet" method="get" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="page">
					<input type="hidden" name="offset" value="10">
					<input type="hidden" name="type">
					<div class="modal-header col-12"><h5 class="modal-title"><b>JACKPOT HISTORY</b></h5>
					<label class="col-5 col-md-4 col-sm-3 label-align ml-2">
														<select class="form-control fs-6" name="type">
														@foreach (App\HNH\Vars::$tiers as $tier)
															<option value="{{ $tier }}"> {{ $tier }}</option>
														@endforeach
														</select>
                                                    </label></div>

					<div class="modal-body" style="">

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
	var fm = $('form[name="fmGet"]');
	let slotChk = $('[data-slot]');

	$('#history').on('show.bs.modal', function(e){

		let emb = $(e.relatedTarget);
		let emf = $(e.currentTarget);

		fm.find('input[name="type"]').val(emb.data('type'));
		fm.find('select[name="type"]').val(emb.data('type'));

		let mb = emf.find('.modal-body');
		mb.empty();

		fm.submit();
	});


	fm.on('submit', function(e){

		e.preventDefault();

		let mb = $(this).find('.modal-body');

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

	fm.find('select[name="type"]').on('change', () => fm.submit());


	$('[data-slot]').off('click').on('click', function(e){

		let _this_ = $(this);
		let target = _this_.data('target');
		let confrm = confirm(`정말 ${target} 상태를 변경하시겠습니까?\n(변경으로 인한 불이익은 책임지지 않습니다.)`);

		if (! confrm) {
			e.preventDefault();
			return false;
		}

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.post({
				url: '/global/jackpot/switch/slot',
				data: {
					target: _this_.data('slot'),
					status: _this_.is(':checked') ? '1' : '0',
				},
				dataType: 'json',
				success: function (d)
				{

					if (d.msg)
						alert(d.msg);

					if (d.result != 0)
					{
						return false;
					}

					if (_this_.data('slot') === 'all') {
						for (let i in slotChk) {
							slotChk.eq(i).prop('checked', _this_.prop('checked'));
						}

					}
				}
			});
	});


	$('[data-tier]').on('click', function(e){

		let _this_ = $(this);
		let prevChecked = !_this_.prop('checked');

		e.preventDefault();

		$.get({
			url: ['/global/jackpot/switch', _this_.data('tier'), (prevChecked ? '0' : '1')].join('/'),
			dataType: 'json',
			success: function (d)
			{
				alert(d.msg);

				if (d.result != 0) {
					return false;
				}
				_this_.prop('checked', !prevChecked);
			}
		});
	});

});




</script>

@endsection
