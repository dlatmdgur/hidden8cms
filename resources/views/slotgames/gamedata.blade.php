@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">슬롯 설정</a></li>
				<li class="breadcrumb-item">게임데이터 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<div class="pull-left">
									<h2>게임데이터 설정</h2>
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
								<table class="table table-sm table-bordered table-hover mb-0">
									<thead class="thead-dark">
										<tr class="text-center">
											<th>슬롯 ID</th>
											<th>버전</th>
											<th>마지막 변경시간</th>
											<th>Env</th>
											<th>메모</th>
											<th>활성화1</th>
											<th>활성화2</th>
											<th width="105">-</th>
										</tr>
									</thead>

									<tbody id="data">
@if (count($data) > 0)
	@foreach ($data AS $index => $row)
										<tr class="text-center {{ $row->active ? 'table-primary' : ($row->alt ? 'table-info' : '') }}">
											<td class="text-left pl-3">{{ $row->slot_id }}</td>
											<td>{{ $row->ver }}</td>
											<td>{{ $row->time_stamp }}</td>
											<td>{{ $row->env }}</td>
											<td class="text-left pl-3">{{ $row->note }}</td>
											<td><span class="badge badge-info">{{ $row->active ? '사용' : '' }}</span></td>
											<td><span class="badge badge-info">{{ $row->alt ? '사용' : '' }}</span></td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm btn-primary" data-i="{{ $index }}" data-toggle="modal" data-size="modal-xl" data-target="#modify">변경</button>
 													<button type="button" class="btn btn-sm btn-secondary" data-i="{{ $index }}" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>
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
				<form name="frmSet" method="post" action="/slotgames/gamedata_set" onsubmit="return false;" method-transfer="async" class="m-0">
				<textarea name="game_data" class="form-control form-control-sm col-12 hide" style="display:none;" data-check=true data-msg-false="게임 데이터가 올바르지 않습니다." readonly ></textarea>
				<div class="modal-header"><h5 class="modal-title">게임 데이터 추가(변경)</h5></div>
				<div class="modal-body">
					<table class="table table-sm">
						<colgroup>
							<col width="20%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th class="table-dark">슬롯 ID</th>
								<td><input type="text" name="slot_id" value="{{ $slot_id }}" class="form-control form-control-sm col-6" readonly data-check=true data-msg-false="슬롯ID는 필수 사항입니다." /></td>
							</tr>
							<tr>
								<th class="table-dark">버전</th>
								<td><input type="text" name="ver" value="" class="form-control form-control-sm col-6" data-check=true data-msg-false="버전은 필수 사항입니다." /></td>
							</tr>
							<tr>
								<th class="table-dark">Env</th>
								<td><input type="text" name="env" value="" class="form-control form-control-sm col-6" data-check=true data-msg-false="ENV는 필수 사항입니다." /></td>
							</tr>
							<tr>
								<th class="table-dark">메인 활성화</th>
								<td>
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" name="active" value="1" />
										<label class="custom-control-label" for="active"></label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">서브 활성화</th>
								<td>
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="alt" name="alt" value="1" />
										<label class="custom-control-label" for="alt"></label>
									</div>
								</td>
							</tr>
							<tr>
								<th class="table-dark">메모</th>
								<td><input type="text" name="note" value="" class="form-control form-control-sm col-12" /></td>
							</tr>
							<tr>
								<th class="table-dark">게임 데이터</th>
								<td id="jsoneditor" style="height: 550px;"></td>
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
				<form name="frmSet" method="post" action="/slotgames/gamedata_drop" onsubmit="return false;" method-transfer="async" class="m-0">
				<div class="modal-header"><h5 class="modal-title">슬롯 삭제</h5></div>

				<div class="modal-body">
					<input type="hidden" name="slot_id" value="{{ $slot_id }}" />
					<input type="hidden" name="ver" value="" />
					<input type="hidden" name="env" value="" />
					<p>삭제하면 복구 불가합니다.</p>
					<p>정말 삭제하시겠습니까?</p>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" aria-label="modify">삭제</button>
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	<div id="deploy" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="/cmd/set" onsubmit="return false;" method-transfer="async" class="m-0">
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


<link rel="stylesheet" href="/css/jsoneditor.min.css" type="text/css" />
<script src="/js/jsoneditor.min.js"></script>
<script src="/js/apis.js"></script>
<script>

$(function ()
{
	//
	// 게임데이터 데이터 정의.
	//
	let data = {!! $data !!};

	//
	// 표현할 컬럼 정의.
	//
	let cols = ['slot_id', 'ver', 'time_stamp', 'env', 'note', 'active', 'alt'];


	//
	// JSON Editor 처리.
	//
	const options = {
		mode: 'code',
		onChange: function ()
		{
			try {
				let j = JSON.parse(editor.getText());
				$('[name="game_data"]').val(editor.getText());
			}
			catch (e) {
				$('[name="game_data"]').val('');
			}

		},
	};
	const editor = new JSONEditor($('#jsoneditor')[0], options);


	//
	// 추가/변경 버튼 이벤트.
	//
	$('#modify, #drop').on('show.bs.modal', function (e)
	{
		var t = $(e.relatedTarget).data('i');
		var d = data[t];
		var f = $(e.currentTarget).find('form');

		if (d === undefined)
		{
			$(this).parents('.modal').modal('hide');
			return false;
		}

		var s = t == undefined;

		for (var i in cols)
		{
			var elem = f.find('[name='+cols[i]+']');

			if (cols[i] != 'slot_id')
				elem.val(s ? '' : d[cols[i]]);

			switch (elem.attr('type'))
			{
				case 'checkbox':
					elem.val(1).prop('checked', d != undefined && d[cols[i]] == 1 ? true : false);
					break;
			}
		}

		f.find('[name="game_data"]').val(s ? '' : d.game_data);
		editor.setText(s ? '' : d.game_data);
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
