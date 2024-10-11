@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgamesv2.slots') }}">슬롯 설정 v2.0</a></li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb p-0">
								<h2><b>슬롯 설정</b></h2>
								<div class="pull-right pr-0">
									<span class="text-dark mr-2">* 데이터 변경 후, 적용하려면 반드시 <b class="text-danger">데이터 반영</b>을 누르세요.</span>
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deploy">데이터 반영</button>
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
									<thead class="thead-dark text-center text-nowrap">
										<tr>
											<th rowspan="2" width="90">종류</th>
											<th rowspan="2" width="90">순서</th>
											<th colspan="2">그룹</th>
											<th rowspan="2" width="90">활성화</th>
											<th rowspan="2">슬롯ID</th>
											<th rowspan="2">슬롯명</th>
											<th rowspan="2" width="90">레벨</th>
											<th colspan="2">표시</th>
											<th rowspan="2" width="150">최근 업데이트</th>
											<th rowspan="2" width="220">-</th>
										</tr>
										<tr class="text-center text-nowrap">
											<th width="90">그룹</th>
											<th width="90">순서</th>

											<th width="90">NEW</th>
											<th width="90">잭팟</th>
										</tr>
									</thead>

									<tbody id="data">
@if (count($slots) > 0)
	@foreach ($slots AS $idx => $row)

										<tr class="text-center align-middle text-nowrap">
											<td>
		@if ($row->slot_type === 'V')

												<span class="badge badge-danger text-md p-2">비디오</span>
		@else

												<span class="badge badge-info text-md p-2">클래식</span>
		@endif

											</td>

											<td class="text-right pl-3 pr-2">{{ number_format($row->sorted) }}</td>

											<td>
		@if ($row->slot_group == 'T')

												<span class="badge badge-primary text-md p-2">탑</span>
		@elseif ($row->slot_group == 'A')

												<span class="badge badge-danger text-md p-2">올스타</span>
		@else

												<span class="badge badge-info text-md p-2">일반</span>
		@endif

											</td>

											<td class="text-right pl-3 pr-2">{{ number_format($row->group_sorted) }}</td>

											<td>
		@if ($row->opened == 1)

												<span class="badge badge-success text-md p-2">오픈</span>
		@else

												<span class="badge badge-danger text-md p-2">정지</span>
		@endif

											</td>

											<td class="text-left pl-2 -pr-3">{{ $row->slot_id }}</td>
											<td class="text-left pl-2 -pr-3">{{ $row->slot_name }}</td>
											<td class="text-right pr-2 pl-3" data-type="level">{{ $row->level }}</td>

											<td>
		@if ($row->is_new == 1)

												<span class="badge badge-success text-md p-2">사용</span>
		@else

												<span class="badge badge-warning text-md p-2">미사용</span>
		@endif

											</td>

											<td>
@if ($row->is_jackpot == 1)

												<span class="badge badge-success text-md p-2">사용</span>
		@else

												<span class="badge badge-warning text-md p-2">미사용</span>
		@endif

											</td>

											<td>{{ $row->updated }}</td>

											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-primary text-nowrap" data-i="{{ $idx }}" data-toggle="modal" data-target="#modify">변경</button>
		@if (auth()->user()->hasPermissionTo('master'))
													<button type="button" class="btn btn-sm btn-secondary text-nowrap" method-href="{{ Route('slotgamesv2.detail', $row->slot_id) }}">게임데이터</button>
													<button type="button" class="btn btn-sm btn-secondary text-nowrap" method-href="{{ Route('slotgamesv2.bettings', $row->slot_id) }}">배팅정보</button>
		@endif
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
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ route('slotgamesv2.set_slot') }}" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">슬롯 추가(변경)</h5></div>

				<div class="modal-body was-validated">
					<div class="form-group">
						<label for="slot_id"><strong>슬롯ID</strong></label>
						<input type="text" class="form-control" id="slot_id" name="slot_id" value="" placeholder="슬롯ID(을)를 입력하세요." required >
						<div class="valid-feedback">슬롯ID는 슬롯 구분을 위한 키로 사용됩니다.</div>
						<div class="invalid-feedback">슬롯ID를 입력하세요.</div>
					</div>

					<div class="form-group">
						<label for="slot_name"><strong>슬롯명</strong></label>
						<input type="text" class="form-control" id="slot_name" name="slot_name" value="" placeholder="슬롯명(을)를 입력하세요." required >
						<div class="invalid-feedback">슬롯명(을)를 입력하세요.</div>
					</div>

					<hr class="mt-5" />

					<div class="form-group">
						<label for="slot_type"><strong>슬롯 종류</strong></label>
						<div class="input-group">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-sm btn-outline-secondary"><input type="radio" id="slot_type" name="slot_type" value="V" required />VIDEO</label>
								<label class="btn btn-sm btn-outline-secondary"><input type="radio" id="slot_type" name="slot_type" value="C" required />CLASSIC</label>
							</div>
						</div>
						<small class="text-warning">비디오 또는 클래식 슬롯을 선택할 수 있습니다.</small>
					</div>

					<div class="form-group">
						<label for="sorted"><strong>순서</strong></label>
						<input type="number" class="form-control col-6" id="sorted" name="sorted" value="" min="0" max="9999" step="1"  placeholder="순서(을)를 입력하세요." required >
						<div class="invalid-feedback">순서(을)를 입력하세요.</div>
					</div>

					<div class="form-group">
						<label for="group_sorted"><strong>그룹</strong></label>
						<div class="input-group mb-1">
							<select name="slot_group" type="select" class="form-control" required >
								<option value="">▒ 그룹 선택 ▒</option>
								<option value="N" class="text-success">일반</option>
								<option value="A" class="text-danger">올스타</option>
								<option value="T" class="text-primary">탑슬롯</option>
							</select>
							<input type="number" id="group_sorted" name="group_sorted" value="" min="" max="9999" step="1" class="form-control" placeholder="그룹순서를 입력하세요." required >
							<div class="invalid-feedback">그룹선택 또는 순서를 입력하세요.</div>
						</div>
					</div>

					<hr class="mt-5" />

					<div class="form-group">
						<label for="is_new"><strong>신규여부</strong></label>
						<div class="input-group mb-1">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="is_new" name="is_new" value="1" />
								<label class="custom-control-label" for="is_new"></label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="is_jackpot"><strong>잭팟여부</strong></label>
						<div class="input-group mb-1">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="is_jackpot" name="is_jackpot" value="1" />
								<label class="custom-control-label" for="is_jackpot"></label>
							</div>
						</div>
					</div>

					<hr class="mt-5" />

					<div class="form-group">
						<label for="opened"><strong>오픈여부</strong></label>
						<div class="input-group mb-1">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="opened" name="opened" value="1" />
								<label class="custom-control-label" for="opened"></label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="level"><strong>오픈레벨</strong></label>
						<input type="number" class="form-control col-6" id="level" name="level" value="" min="0" max="9999" step="1"  placeholder="오픈레벨(을)를 입력하세요." required >
						<div class="invalid-feedback">오픈레벨(을)를 입력하세요.</div>
					</div>
				</div>

				<div class="modal-footer mt-3">
					<button type="submit" class="btn btn-primary" aria-label="modify">추가(변경)</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>


	<div id="deploy" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmCmd" method="post" action="{{ Route('slotgamesv2.reset') }}" onsubmit="return false;" method-transfer="async" class="m-0">
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
	// 슬롯 데이터.
	//
	let data = {!! $slots !!};

	//
	// 목록에 출력할 컬럼.
	//
	let cols = [
		'slot_type',
		'sorted',
		'slot_group',
		'group_sorted',
		'opened',
		'slot_id',
		'slot_name',
		'level',
		'is_new',
		'is_jackpot',
		'updated'
	];


	//
	// 추가/변경 버튼 이벤트.
	//
	$(document).on('show.bs.modal', '.modal', function (e)
	{
		let d = data[$(e.relatedTarget).data('i')];
		let f = $(e.currentTarget).find('form');

		let s = d == undefined;

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

				case 'text':
					elem.val(s ? '' : d[cols[i]]);
					break;

				case 'select':
					elem.find('option[value="'+ (s ? 'N' : d[cols[i]]) +'"]').prop('selected', true);
					f.find('[name=group_sorted]').val(s ? '' : d.group_sorted);
					break;
			}
		}

		if (!s)
			f.find('[name="slot_id"]').attr('readonly', 'readonly');
	});


	//
	// 추가/변경 콜백 처리.
	//
	$(document).on('callback', 'form', function (e, d)
	{
		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 1)
			return false;

		document.location.reload();
	});


	//
	// 데이터 적용
	//
	$('#reset').on('click', function ()
	{
		$.get(
			'/slotgames/reset',
			function (d)
			{
				d = d.result ? d : JSON.parse(d);

				alert(d.msg);
			});
	});
});

</script>



@endsection
