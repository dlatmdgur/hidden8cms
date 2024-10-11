@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">슬롯 설정 v1.0</a></li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>슬롯 설정</h2>
								<div class="pull-right">
									<span class="text-danger mr-2">* <span class="bg-primary text-white" style="padding: 7px 10px; border-radius: 3px;">파란색버튼</span> → 파란색버튼은 변경 후, 데이터 적용 눌러주세요.</span>
									<button id="reset" class="btn btn-danger" data-host="{{ $slot_host }}">데이터 적용</button>
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
											<th class="align-top text-nowrap" rowspan="2">종류</th>
											<th class="align-top text-nowrap" rowspan="2">순서</th>
											<th class="align-top text-nowrap" rowspan="2">그룹</th>
											<th class="align-top text-nowrap" rowspan="2">활성화</th>
											<th class="align-top text-nowrap" rowspan="2">슬롯ID</th>
											<th class="align-top text-nowrap" rowspan="2">슬롯명</th>
											<th class="align-top text-nowrap" rowspan="2">레벨</th>
											<th class="align-top text-nowrap" colspan="2">표시</th>
<?php /*
											<th class="align-top text-nowrap" rowspan="2" width="155">확률변경</th>
											<th class="align-top" colspan="2">UID</th>
*/ ?>
											<th class="align-top" rowspan="2" width="220">데이터</th>
										</tr>
										<tr class="text-center">
											<th class="align-top text-nowrap" rowspan="2">NEW</th>
											<th class="align-top text-nowrap" rowspan="2">잭팟</th>
<?php /*
											<th class="align-top" width="70">높음</th>
											<th class="align-top" width="70">낮음</th>
*/ ?>
										</tr>
									</thead>

									<tbody id="data">
@if (count($slots) > 0)
	@foreach ($slots AS $index => $slot)
										<tr class="text-center">
											<td class="text-nowrap">{{ $slot->slot_type == 'V' ? '비디오' : '클래식' }}</td>
											<td>{{ $slot->sorted }}</td>
											<td class="text-nowrap">{{ $slot->slot_group == 'T' ? '탑슬롯' : '일반' }}</td>
											<td><span class="badge badge-{{ $slot->opened != 0 ? 'success' : 'warning' }}" style="font-size: 0.75rem;">{{ $slot->opened != 0 ? '사용' : '미사용' }}</span></td>
											<td class="text-left text-nowrap pl-2 pr-2">{{ $slot->slot_id }}</td>
											<td class="text-left text-nowrap pl-2 pr-2">{{ $slot->slot_name }}</td>
											<td data-type="level">{{ $slot->open_level }}</td>
											<td><span class="badge badge-{{ $slot->badge_new != 0 ? 'success' : 'warning' }}" style="font-size: 0.75rem;">{{ $slot->badge_new != 0 ? '사용' : '미사용' }}</span></td>
											<td><span class="badge badge-{{ $slot->badge_jackpot != 0 ? 'success' : 'warning' }}" style="font-size: 0.75rem;">{{ $slot->badge_jackpot != 0 ? '사용' : '미사용' }}</span></td>
<?php /*
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-{{ $slot->ver == 3 ? '' : 'outline-' }}info text-nowrap" data-i="{{ $index }}" data-ver="3" data-toggle="modal" data-target="{{ $slot->ver == 3 ? '' : '#probability' }}">높음</button>
													<button type="button" class="btn btn-sm btn-{{ $slot->ver == 4 ? '' : 'outline-' }}info text-nowrap" data-i="{{ $index }}" data-ver="4" data-toggle="modal" data-target="{{ $slot->ver == 4 ? '' : '#probability' }}">중간</button>
													<button type="button" class="btn btn-sm btn-{{ $slot->ver == 5 ? '' : 'outline-' }}info text-nowrap" data-i="{{ $index }}" data-ver="5" data-toggle="modal" data-target="{{ $slot->ver == 5 ? '' : '#probability' }}">낮음</button>
												</div>
											</td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-primary text-nowrap" data-i="{{ $index }}" data-toggle="modal" data-target="#uids" data-ver="2">확률↑</button>
												</div>
											</td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-primary text-nowrap" data-i="{{ $index }}" data-toggle="modal" data-target="#uids" data-ver="1">확률↓</button>
												</div>
												</td>
*/ ?>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-primary text-nowrap" data-i="{{ $index }}" data-toggle="modal" data-target="#modify">변경</button>
		@if (auth()->user()->hasPermissionTo('master'))
													<button type="button" class="btn btn-sm btn-secondary text-nowrap" method-href="/slotgames/gamedata/{{ $slot->slot_id }}">게임데이터</button>
													<button type="button" class="btn btn-sm btn-secondary text-nowrap" method-href="/slotgames/betinfo/{{ $slot->slot_id }}">배팅정보</button>
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
				<form name="frmSet" method="post" action="/slotgames/slot_set" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">슬롯 추가(변경)</h5></div>

				<div class="modal-body">
					<table class="table table-sm">
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
								<th class="table-dark">슬롯 명</th>
								<td><input type="text" name="slot_name" value="" class="form-control form-control-sm" /></td>
							</tr>
							<tr>
								<th class="table-dark">슬롯 종류</th>
								<td>
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="slot_type" value="V" />VIDEO</label>
										<label class="btn btn-sm btn-outline-secondary"><input type="radio" name="slot_type" value="C" />CLASSIC</label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">순서</th>
								<td><input type="number" name="sorted" value="0" min="0" max="9999" step="1" class="form-control form-control-sm col-12 col-sm-3" data-check data-msg-false="순서를 입력하세요." /></td>
							</tr>
							<tr>
								<th class="table-dark">그룹</th>
								<td>
									<div class="input-group mb-1">
										<select name="slot_group" type="select" class="form-control form-control-sm">
											<option value="N">일반</option>
											<option value="T" class="text-primary">탑슬롯</option>
										</select>
										<input type="number" name="group_sorted" value="" min="" max="9999" step="1" class="form-control form-control-sm" placeholder="그룹 순서" />
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">NEW</th>
								<td>
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="badge_new" name="badge_new" value="1" />
										<label class="custom-control-label" for="badge_new"></label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">잭팟 사용</th>
								<td>
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="badge_jackpot" name="badge_jackpot" value="1" />
										<label class="custom-control-label" for="badge_jackpot"></label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">오픈 여부</th>
								<td>
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="opened" name="opened" value="1" />
										<label class="custom-control-label" for="opened"></label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">오픈 레벨</th>
								<td><input type="number" name="open_level" value="0" min="0" max="9999" step="1" class="form-control form-control-sm col-3" data-check data-msg-false="오픈레벨을 입력하세요." /></td>
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



	<div id="probability" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="/slotgames/slot_probability" onsubmit="return false;" method-transfer="async" class="m-0">
				<input type="hidden" name="slot_id" value="" />
				<div class="modal-header"><h5 class="modal-title">슬롯 확률 변경</h5></div>

				<div class="modal-body">
					<div class="row">
						<div class="col-6">변경할 확률</div>
						<div class="col-6"><input type="hidden" name="ver" value="" readonly /><span></span></div>
					</div>

					<p class="mt-5 text-center">확률을 변경 하시겠습니까?</p>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success" aria-label="modify">변경</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>


	<div id="uids" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form name="frmSet" method="post" action="/slotgames/uidset" onsubmit="return false;" method-transfer="async" class="m-0">
				<input type="hidden" name="slot_id" value="" />
				<input type="hidden" name="ver" value="" />
				<div class="modal-header"><h5 class="modal-title">UID 변경</h5></div>

				<div class="modal-body">
					<div class="mb-5">
						<label for="uidAdd" class="form-label">UID 추가 <span class="ml-3" style="color: #AAA;">, 로 구분하여 여러 UID를 한번에 등록할 수 있습니다.</span></label>
						<div class="input-group mb-3">
							<input type="text" class="form-control" id="uidAdd" placeholder="[UID]를 입력하세요." />
							<div class="input-group-append">
								<button class="btn btn-info" type="button" id="btnAddUid">추가</button>
							</div>
						</div>

					</div>
					<div class="mb-5">
						<label for="uids" class="form-label">추가된 UID <span class="ml-3" style="color: #AAA;">아래 추가된 UID를 클릭하면 해제됩니다.</span></label>
						<div class="sticker"></div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success" aria-label="modify">변경</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>


	<div id="deploy" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmCmd" method="post" action="/cmd/set" onsubmit="return false;" method-transfer="async" class="m-0">
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
		'open_level',
		'badge_new',
		'badge_jackpot',
		'updated'
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
			var elem = f.find('[name='+cols[i]+']').removeAttr('readonly');

			switch (elem.attr('type'))
			{
				case 'checkbox':
					elem.val(1).prop('checked', d != undefined && d[cols[i]] == 1 ? true : false);
					break;

				case 'radio':
					console.log(elem);
					elem.each(function (n, e)
					{
						$(this).removeAttr('checked').parent().removeClass('active');
						console.log(cols[i], d[cols[i]], $(this).val(), $(this));
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
	// 확률 변경 폼 이벤트 정의.
	//
	$('#probability').on('show.bs.modal', function (e)
	{
		let d = data[$(e.relatedTarget).data('i')];
		let f = $(e.currentTarget).find('form');

		let s = d == undefined;

		f.find('[name="slot_id"]').val(d.slot_id);
		f.find('[name="ver"]').val($(e.relatedTarget).data('ver')).next().text($(e.relatedTarget).text());
	});


	//
	// sticker 이벤트 정의.
	//
	$('.sticker').on('click', 'span', function ()
	{
		$(this).remove();
	});
	$('.sticker').on('clear', function ()
	{
		$(this).html('');
	});


	//
	// UID 변경 폼 이벤트 정의.
	//
	$('#uids').on('show.bs.modal', function (e)
	{
		let d = data[$(e.relatedTarget).data('i')];
		let v = $(e.relatedTarget).data('ver');
		let f = $(e.currentTarget).find('form');
		let u = f.find('.sticker');

		f.find('button[type="submit"]').removeClass().addClass($(e.relatedTarget).attr('class')).removeClass('btn-sm');

		u.trigger('clear');

		f.find('[name="slot_id"]').val(d.slot_id);
		f.find('[name="ver"]').val(v);

		f.find('#uidAdd').off('keypress').on('keypress', function (e)
		{
			if (e.keyCode !== 13)
				return;

			f.find('#btnAddUid').trigger('click');
			return false;
		});

		f.find('#btnAddUid').off('click').on('click', function ()
		{
			let ua = f.find('#uidAdd');

			if (ua.val().toString().trim() == '')
				return;

			let uids = ua.val().toString().split(',');
			for (var i in uids)
			{
				let em = $('input[name="uids[]"]');

				for (var j = 0; j < em.length; j++)
				{
					if (parseInt(em.eq(j).val()) == parseInt(uids[i]))
					{
						alert('[ '+ uids[i] +' ] 는 이미 등록되어 있습니다.');
						return false;
					}
				}
			}

			f.trigger('uid', {uids:uids});
			ua.val('');
			return false;
		});

		f.off('uid').on('uid', function (e, d)
		{
			for (var i in d.uids)
				u.append('<span data-method="remove"><input type="hidden" name="uids[]" value="'+d.uids[i].toString().trim()+'" readonly />'+d.uids[i]+'</span>');
		});

		$.ajax({
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				type: 'get',
				url: ['', 'slotgames', 'gamedata', d.slot_id, v].join('/'),
				dataType: 'json',
				error: function(d){ console.log('AJAX ERROR: ', d) },
				success: function(d)
				{
					if (d.data.length <= 0 ||
						d.data[0].game_data == undefined ||
						typeof d.data[0].game_data != 'object')
						return false;

					let inf = d.data[0].game_data;

					if (inf.alts == undefined ||
						inf.alts.length <= 0 ||
						inf.alts[0].condition == undefined ||
						inf.alts[0].condition.uids == undefined ||
						inf.alts[0].condition.uids.length <= 0)
						return false;

					f.trigger('uid', {uids:inf.alts[0].condition.uids});
				}
			});

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

<style>
.sticker {
	display: flex;
	flex-wrap: wrap;
	padding: 10px;

}
.sticker > span {
	display: inline;
	padding: 2px 10px;
	margin: 0 10px 5px 0;
	box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
	border-radius: 5px;
	background-color: green;
	color: white;
}

form tbody td {
	vertical-align: middle !important;
}

[data-method="remove"] {
	cursor: help;
}

</style>

@endsection
