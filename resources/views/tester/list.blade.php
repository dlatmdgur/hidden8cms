@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('tester.list') }}">테스트유저 관리</a></li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>테스트유저 관리</h2>
								<div class="pull-right pr-0">
									<button type="button" class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#add"><b>추가</b></button>
								</div>
								<div class="pull-right pr-0">
									<div class="input-group">
										<input name="keyword" id="user-keyword" value="" class="form-control" placeholder="유저ID 또는 닉네임 입력" />
										<div class="input-group-append">
											<button class="btn btn-success" id="user-check">등록여부 확인</button>
										</div>
									</div>
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
								<table class="table table-sm table-bordered table-hover table-striped mb-0">
									<thead class="thead-dark">
										<tr class="text-center text-nowrap">
											<th rowspan="2">유저 SEQ</th>
											<th rowspan="2">USER ID</th>
											<th rowspan="2">닉네임</th>
											<th colspan="1">서비스</th>
											<th rowspan="2">등록일</th>
											<th rowspan="2">-</th>
										</tr>

										<tr class="text-center text-nowrap">
											<th>HIDDEN8</th>
										</tr>
									</thead>

									<tbody id="data">
@if(count($testers) > 0)
	@foreach ($testers as $key => $row)
		@php ( $corps = explode('|', $row->corps) )
										<tr class="text-center text-nowrap align-middle">
											<td>{{ $row->user_seq }}</td>
											<td>{!! empty($row->platform_id) ? '<span class="text-danger">소셜 회원</span>' : $row->platform_id !!}</th>
											<td>{{ $row->nickname }}</td>

											<td>{!! in_array('hidden8', $corps) ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>' !!}</td>

											<td>{{ $row->created }}</td>

											<td>
												<div class="btn-group" data-seq="{{ $row->user_seq }}" data-nickname="{{ $row->nickname }}" data-corps="{{ $row->corps }}">
													<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-size="modal-xl" data-target="#modify">변경</button>
													<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>
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
								{{ $testers->onEachSide(10)->links('layouts.partials.page', ['pageinfo' => $testers, 'offset' => $offset]) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div id="add" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ route('tester.set') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header">
						<h5 class="modal-title">테스트 유저 등록</h5>
					</div>

					<div class="modal-body">
						<table class="table table-sm table-bordered">
							<colgroup>
								<col width="20%">
								<col width="*">
							</colgroup>
							<tbody>
								<tr>
									<th class="table-dark">유저 검색</th>
									<td class="p-2">
										<div class="input-group mb-0">
											<input type="text" name="keyword" value="" class="form-control" />
											<div class="input-group-append">
												<button class="btn btn-info" id="modal-search">검색</button>
											</div>
										</div>
									</td>
								</tr>

								<tr>
									<th class="table-dark">유저 선택</th>
									<td class="p-2">
										<div class="" role="group" id="search-player" style="width: 100%; overflow-x: hidden;"></div>
									</td>
								</tr>

								<tr>
									<th class="table-dark">HIDDEN 8</th>
									<td class="p-2">
										<div class="form-group row">
											<div class="col-sm-1 pt-1">
												<div class="custom-control custom-switch">
													<input type="checkbox" id="add-hidden8" name="corps[]" class="custom-control-input" value="hidden8">
													<label class="custom-control-label" for="add-hidden8"></label>
												</div>
											</div>
										</div>
									</td>
								</tr>

							</tbody>
						</table>

						<ul class="alert alert-warning text-dark" role="alert">
							<li class="ml-2"><span>알림 작성 기준</span> {{ date('Y-m-d H:i:s')}}</li>
							<hr />
							<li class="ml-2"><span>개요</span>"웹뷰슬롯 설정" 에서 설정한 옵션A, 옵션B 설정에 따라 동작한다.</li>
							<li class="ml-2"><span></span>본 설정은 옵션A(ON), 옵션B(OFF) 설정되어 있는 슬롯에 대한 동작에 관여한다.</li>
						</ul>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" aria-label="modify">추가(변경)</button>
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>



	<div id="modify" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ route('tester.set') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header">
						<h5 class="modal-title">테스트 유저 변경</h5>
					</div>

					<div class="modal-body">
						<table class="table table-sm table-bordered">
							<colgroup>
								<col width="20%">
								<col width="*">
							</colgroup>
							<tbody>
								<tr>
									<th class="table-dark">유저 SEQ</th>
									<td class="p-2"><label id="seq"></label></td>
								</tr>

								<tr>
									<th class="table-dark">닉네임</th>
									<td class="p-2"><label id="nickname"></label></td>
								</tr>

								<tr>
									<th class="table-dark">HIDDEN 8</th>
									<td class="p-2">
										<div class="form-group row">
											<div class="col-sm-1 pt-1">
												<div class="custom-control custom-switch">
													<input type="checkbox" id="corp-hidden8" name="corps[]" class="custom-control-input" value="hidden8">
													<label class="custom-control-label" for="corp-hidden8"></label>
												</div>
											</div>
										</div>
									</td>
								</tr>

							</tbody>
						</table>

						<ul class="alert alert-warning text-dark" role="alert">
							<li class="ml-2"><span>알림 작성 기준</span>{{ date('Y-m-d H:i:s')}}</li>
							<hr />
							<li class="ml-2"><span>개요</span>" 웹뷰슬롯 설정 "에서 설정한 옵션A, 옵션B 설정에 따라 동작한다.</li>
							<li class="ml-2"><span></span>본 설정은 옵션A(ON), 옵션B(OFF) 설정되어 있는 슬롯에 대한 동작에 관여한다.</li>
						</ul>
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
				<form name="frmDrop" method="post" action="{{ Route('tester.drop') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<div class="modal-header"><h5 class="modal-title">테스트유저 삭제</h5></div>
					<input type="hidden" name="user_seq" value="">

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
	const defCorps = ['hidden8'];



	//
	// 검색 입력 이벤트.
	//
//	$('input[name="keyword"]').on('change keyup', function (e)
//	{
//		if (e.keyCode == 13)
//			$('#modal-search').trigger('click');
//		return false;
//	});



	//
	// 검색 버튼 이벤트.
	//
	$('#modal-search').on('click', function (e)
	{
		let f = $(this).parents('form');
		let q = $(this).parents('td').find('input[name="keyword"]').val();

		// 초기화.
		let p = $('#search-player').html('');
		f.find('input[name="user_seq"]').val('');


		getUser(q, null, (d) =>
			{
				p.html('');
				for (var i in d.members)
					p.append($('<button type="button" class="btn btn-outline-secondary" data-seq="'+ d.members[i].user_seq +'">').text((d.members[i].nickname == '' ? 'NONAME' : d.members[i].nickname)+' ('+d.members[i].user_seq+')'));

				p.find('button').on('click', function ()
				{
					$(this).addClass('active').siblings().removeClass('active');
					f.find('input[name="user_seq"]').val($(this).data('seq'));
				});
			});

		return false;
	});


	//
	// 유저 체크.
	//
	$('#user-check').on('click', function ()
	{
		let q = $('#user-keyword').val();

		getUser(q, 'match', (d) =>
			{
				if (d.members.length <= 0)
					return alert('검색된 유저가 없습니다.');

				let u = d.members[0];

				let s = [];

				s.push('---------------------------------------------------------------------------');
				s.push('유저 닉네임 : '+ u.nickname);
				s.push('유저 아이디 : '+ u.account);
				s.push('유저 번호    : '+ u.user_seq);
				s.push('---------------------------------------------------------------------------');

				alert("\n검색된 유저가 있습니다.\n\n" + s.join('\n'));
			});
	});

	$('#user-keyword').on('keydown', function (e)
	{
		if (e.keyCode == 13)
			$('#user-check').trigger('click');
	});


	/**
	 * 유저 찾는 함수.
	 *
	 *
	 */
	function getUser(q, m, c)
	{
		if (q == undefined ||
			q == null ||
			q == '')
			return alert('검색할 대상을 입력하세요.');

		$.get('{{ route('tester.search') }}?q='+q+'&m='+m, 'json', function (d)
			{
				if (d.msg)
					alert(d.msg);

				if (d.result != 1)
					return false;

				c(d);
			});
	}




	//
	// 추가 버튼 이벤트.
	//
	$('#add').on('show.bs.modal', function (e)
	{
		let p = $(e.relatedTarget).parent();
		let f = $(e.currentTarget).find('form');

		let u = p.data('seq');


		// 필수
		f.find('input[name="user_seq"]').val(u);

		// 선택
		f.find('#seq').text(u);
		f.find('#nickname').text(p.data('nickname'));

		// 체크박스 처리.
		for (var corp of defCorps)
			f.find('#add-'+ corp).prop('checked', false);
	});



	//
	// 변경 버튼 이벤트.
	//
	$('#modify, #drop').on('show.bs.modal', function (e)
	{
		let p = $(e.relatedTarget).parent();
		let f = $(e.currentTarget).find('form');

		let u = p.data('seq');


		// 필수
		f.find('input[name="user_seq"]').val(u);

		// 선택
		f.find('#seq').text(u);
		f.find('#nickname').text(p.data('nickname'));

		// 체크박스 처리.
		for (var corp of defCorps)
			f.find('#corp-'+ corp).prop('checked', p.data('corps').toString().split('|').indexOf(corp) == -1 ? false : true);
	});



	//
	// 추가/변경/삭제 콜백 처리.
	//
	$('form[name="frmSet"], form[name=frmDrop]').on('callback', function (e, d)
	{
		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 1)
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
