@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">웹뷰슬롯 설정</a></li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>웹뷰슬롯 설정</h2>
								<div class="pull-right pr-0">
									<button class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#modify" data-corp="superwin"><b>추가</b></button>
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
								<form name="frmSlot" method="post" action="/refer/slotlist" onsubmit="return false;" method-transfer="async" class="m-0">
									<table class="table table-sm table-bordered table-hover table-striped mb-0">
										<thead class="thead-dark">
										<tr class="text-center">
											<th class="align-top text-nowrap">슬롯ID</th>
											<th class="align-top text-nowrap">연결사</th>
											<th class="align-top text-nowrap">썸네일</th>
											<th class="align-top text-nowrap">슬롯명</th>
											<th class="align-top text-nowrap">슬롯명(EN)</th>
											<th class="align-top text-nowrap">순서</th>
											<th class="align-top text-nowrap">긴배너여부</th>
											<th class="align-top text-nowrap">스파인애니메이션여부</th>
											<th class="align-top text-nowrap">ON/OFF 옵션A</th>
											<th class="align-top text-nowrap">ON/OFF 옵션B</th>
											<th class="align-top text-nowrap">등록일</th>
											<th class="align-top text-nowrap">-</th>
										</tr>
										</thead>

										<tbody id="data">
										@if(count($records) > 0)
											@foreach ($records as $key => $row)
												<tr class="text-center">
													<td class="text-nowrap">{{ $row['slot_id'] }}</td>
													<td class="text-nowrap">{{ $row['provider'] }}</td>
													<td>
													@if(!empty($row['thumbnail']))
														<img src="{{ $row['thumbnail'] }}" width="35">
													@endif
													</td>
													<td>{{ $row['name_kr'] }}</td>
													<td>{{ $row['name_en']}}</td>

													<td class="text-nowrap">{{ $row['sorted'] }}</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="is_long{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['is_long'] == 1) checked  @endif disabled>
															<label class="custom-control-label" for="is_long{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="is_spine{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['is_spine'] == 1) checked  @endif disabled>
															<label class="custom-control-label" for="is_spine{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="available{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['status'] == 1) checked  @endif disabled>
															<label class="custom-control-label" for="available{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="option_view{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['view_open'] == 1) checked @endif disabled>
															<label class="custom-control-label" for="option_view{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td class="text-nowrap">{{ $row['created'] }}</td>
													<td>
														<div class="btn-group">
															<button type="button" class="btn btn-sm btn-primary" data-corp="{{ $row['provider'] }}" data-i="{{ $key }}" data-toggle="modal" data-size="modal-xl" data-target="#modify">변경</button>
															<button type="button" class="btn btn-sm btn-secondary" data-corp="{{ $row['provider'] }}" data-i="{{ $key }}" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>
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

			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>외부슬롯 설정</h2>
							</div>
						</div>

						<div class="row">
							<div class="col p-0">
								<form name="frmSlot" method="post" action="/refer/slotlist" onsubmit="return false;" method-transfer="async" class="m-0">
									<table class="table table-sm table-bordered table-hover table-striped mb-0">
										<thead class="thead-dark">
										<tr class="text-center">
											<th class="align-top text-nowrap">슬롯ID</th>
											<th class="align-top text-nowrap">연결사</th>
											<th class="align-top text-nowrap">썸네일</th>
											<th class="align-top text-nowrap">슬롯명</th>
											<th class="align-top text-nowrap">슬롯명(EN)</th>
											<th class="align-top text-nowrap">순서</th>
											<th class="align-top text-nowrap">긴배너여부</th>
											<th class="align-top text-nowrap">스파인애니메이션여부</th>
											<th class="align-top text-nowrap">ON/OFF 옵션A</th>
											<th class="align-top text-nowrap">ON/OFF 옵션B</th>
											<th class="align-top text-nowrap">등록일</th>
											<th class="align-top text-nowrap">-</th>
										</tr>
										</thead>

										<tbody id="data">
										@if(count($externals) > 0)
											@foreach ($externals as $key => $row)
											<tr class="text-center">
													<td class="text-nowrap">{{ $row['slot_id'] }}</td>
													<td class="text-nowrap">{{ $row['provider'] }}</td>
													<td>
													@if(!empty($row['thumbnail']))
														<img src="{{ $row['thumbnail'] }}" width="35">
													@endif
													</td>
													<td>{{ $row['name_kr'] }}</td>
													<td>{{ $row['name_en']}}</td>

													<td class="text-nowrap">{{ $row['sorted'] }}</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="is_long{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['is_long'] == 1) checked  @endif disabled>
															<label class="custom-control-label" for="is_long{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="is_spine{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['is_spine'] == 1) checked  @endif disabled>
															<label class="custom-control-label" for="is_spine{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="available{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['status'] == 1) checked  @endif disabled>
															<label class="custom-control-label" for="available{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td>
														<div class="custom-control custom-switch">
															<input type="checkbox" id="option_view{{ $row['slot_id'] }}" class="custom-control-input" value="1" @if($row['view_open'] == 1) checked @endif disabled>
															<label class="custom-control-label" for="option_view{{ $row['slot_id'] }}"></label>
														</div>
													</td>
													<td class="text-nowrap">{{ $row['created'] }}</td>
													<td>
														<div class="btn-group">
															<button type="button" class="btn btn-sm btn-primary" data-corp="{{ $row['provider'] }}" data-i="{{ $key }}" data-toggle="modal" data-size="modal-xl" data-target="#modify">변경</button>
															<button type="button" class="btn btn-sm btn-secondary" data-corp="{{ $row['provider'] }}" data-i="{{ $key }}" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>
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
				<form name="frmSet" method="post" action="{{ route('slotgames.wv_slot_set') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<div class="modal-header"><h5 class="modal-title">웹뷰슬롯 추가(변경)</h5></div>

					<div class="modal-body">
						<table class="table table-sm">
							<input type="hidden" name="id">
							<colgroup>
								<col width="20%">
								<col width="*">
							</colgroup>
							<tbody>
							<tr>
								<th class="table-dark">슬롯 ID</th>
								<td><input type="text" name="slot_id" value="" class="form-control form-control-sm col-12 col-md-6" data-check data-msg-false="슬롯 ID를 입력하세요." /></td>
							</tr>
							<tr>
								<th class="table-dark">슬롯 명 (kr)</th>
								<td><input type="text" name="name_kr" value="" class="form-control form-control-sm" /></td>
							</tr>
							<tr>
								<th class="table-dark">슬롯 명 (en)</th>
								<td><input type="text" name="name_en" value="" class="form-control form-control-sm" /></td>
							</tr>
							<tr>
								<th class="table-dark">순서</th>
								<td><input type="number" name="sort" value="999" min="0" max="9999" step="1" class="form-control form-control-sm col-12 col-sm-3" data-check data-msg-false="순서를 입력하세요." /></td>
							</tr>
							<tr>
								<th class="table-dark" rowspan="4">ON/OFF</th>
								<td>
									<div class="form-group row">
										<div class="col-sm-1 pt-1">
											<div class="custom-control custom-switch">
												<input type="checkbox" id="is_long" name="is_long" class="custom-control-input" value="1">
												<label class="custom-control-label" for="is_long"></label>
											</div>
										</div>
										<label for="is_long" class="col-sm-10 col-form-label"><b>긴배너여부 :</b> 긴 배너 여부를 결정합니다. </label>
									</div>
								</td>
							</tr>
								<td>
									<div class="form-group row">
										<div class="col-sm-1">
											<div class="custom-control custom-switch">
												<input type="checkbox" id="is_spine" name="is_spine" class="custom-control-input" value="1">
												<label class="custom-control-label" for="is_spine"></label>
											</div>
										</div>
										<label for="is_spine" class="col-sm-10 col-form-label"><b>스파인애니메이션여부 :</b> 스파인 애니메이션 여부를 결정합니다.</label>
									</div>
								</td>
							</tr>
							</tr>
								<td>
									<div class="form-group row">
										<div class="col-sm-1">
											<div class="custom-control custom-switch">
												<input type="checkbox" id="status" name="status" class="custom-control-input" value="1">
												<label class="custom-control-label" for="status"></label>
											</div>
										</div>
										<label for="status" class="col-sm-10 col-form-label"><b>옵션 A :</b> 옵션 A :</b> 활성화 되어 있는 경우, " 옵션 B " 여부와 상관 없이 테스트로 등록된 유저는 모두 노출됩니다. </label>
									</div>
								</td>
							</tr>
							</tr>
								<td>
									<div class="form-group row">
										<div class="col-sm-1">
											<div class="custom-control custom-switch">
												<input type="checkbox" id="view_open" name="view_open" class="custom-control-input" value="1">
												<label class="custom-control-label" for="view_open"></label>
											</div>
										</div>
										<label for="view_open" class="col-sm-10 col-form-label"><b>옵션 B :</b> 모든 유저에 노출할 수 있는 최소한의 옵션입니다. ( 옵션 A,B 모두 활성화 되어야 일반 유저에 노출됩니다. )</label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">슬롯 아이콘 주소</th>
								<td>
									<input type="text" id="thumbnail" name="thumbnail" class="form-control form-control-sm">
									<label for="thumbnail"> * 목록에서 보여질 슬롯의 썸네일 이미지 주소를 입력해 주세요.</label>
								</td>
							</tr>
							<tr>
								<th class="table-dark">연결사</th>
								<td><input type="text" name="provider" value="" class="form-control form-control-sm" /></td>
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
				<form name="frmDrop" method="post" action="{{ Route('slotgames.wv_slot_drop') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<div class="modal-header"><h5 class="modal-title">슬롯 삭제</h5></div>
					<input type="hidden" name="id" value="">
					<input type="hidden" name="corp" value="" />

					<div class="modal-body was-validated">
						<p class="red"></p>
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
			// 슬롯 데이터.
			//
			let data = @json($records);
			let externals = @json($externals);

			//
			// Form에 출력할 컬럼.
			//
			let cols = [
				'provider',
				'slot_id',
				'thumbnail',
				'name_kr',
				'name_en',
				'is_long',
				'is_spine',
				'sorted',
				'status',
				'view_open',
				'created',
			];

			//
			// 추가/변경 버튼 이벤트.
			//
			$('#modify, #drop').on('show.bs.modal', function (e)
			{
				let corp = $(e.relatedTarget).data('corp');

				let d = null;

				switch (corp) {
					case 'hidden8':
						d = data[$(e.relatedTarget).data('i')];
						break;
					default:
						d = externals[$(e.relatedTarget).data('i')];
				}
				let f = $(e.currentTarget).find('form');

				let s = d == undefined;

				if($(this).attr('id') === 'drop') {
					$(this).find('.was-validated p:eq(0)').text('슬롯명 : ' + d['name_kr']);
				}

				for (var i in cols)
				{
					var elem = f.find('[name='+cols[i]+']').removeAttr('readonly');

					switch (elem.attr('type'))
					{
						case 'checkbox':
							elem.val(1).prop('checked', d != undefined && d[cols[i]] == 1 ? true : false);
							break;

						case 'radio':
							elem.each(function (n, e)
							{
								$(this).removeAttr('checked').parent().removeClass('active');
								if (!s && d[cols[i]] == $(this).val())
									$(this).parent().button('toggle');
							});
							break;

						case 'number':
							elem.val(s ? 0 : d[cols[i]]);
							break;

						case 'hidden':
						case 'text':

							var va = '';
							switch (cols[i])
							{
								case 'corp':	va = corp;		break;
							}

							elem.val(s ? va : d[cols[i]]);
							break;

						case 'select':
							elem.find('option[value="'+ (s ? 'N' : d[cols[i]]) +'"]').prop('selected', true);
							f.find('[name=group_sorted]').val(s ? '' : d.group_sorted);
							break;
					}
				}
			});

			//
			// 추가/변경/삭제 콜백 처리.
			//
			$('form[name="frmSet"], form[name=frmDrop]').on('callback', function (e, d)
			{
				if (d.msg != undefined && d.msg != '')
					alert(d.msg);

				if (d.result != 0)
					return false;

				document.location.reload();
			});
		});
	</script>

	<style>
		form tbody td {
			vertical-align: middle !important;
		}

		[data-method="remove"] {
			cursor: help;
		}
	</style>

@endsection
