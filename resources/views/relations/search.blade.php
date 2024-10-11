@extends('layouts.mainlayout')

@section('content')
	<script src="/js/apis.js"></script>


	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">추천인 관리</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>추천인 조회</h2>
							</div>
						</div>

						<!--		검색 영역 : 시작				-->
						<form name="frmLogs" method="get" action="/relations/search">
						<div class="form-row">
							<div class="col-6 col-sm-3 col-md-2 mb-2">
								<select name="d" class="form-control">
@foreach ($depthname AS $key => $name)
									<option value="{{ $key }}" {{ $d == $key ? 'SELECTED' : '' }}>{{ $name }}</option>
@endforeach
								</select>
							</div>

							<div class="col-6 col-sm-3 col-md-2 mb-2">
								<select name="t" class="form-control">
									<option value="userid" {{ $t == 'u' ? 'SELECTED' : '' }}>아이디</option>
									<option value="nickname" {{ $t == 'n' ? 'SELECTED' : '' }}>닉네임</option>
								</select>
							</div>

							<div class="col mb-2">
								<div class="form-row">
									<div class="col-6">
										<input type="text" name="q" value="{{ $q }}" class="form-control" />
									</div>
									<div class="col">
										<button type="submit" class="btn btn-primary text-nowrap">검색</button>

									</div>
									<div class="col text-right">
										<button type="button" class="btn btn-primary text-nowrap" data-depth="0" data-toggle="modal" data-size="modal-xl" data-target="#childs">추천인 신규등록</button>
									</div>
								</div>
							</div>
						</div>
						</form>
						<!--		검색 영역 : 종료				-->



						<!--		컨텐츠 영역 : 시작				-->
						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-bordered table-striped">
									<thead class="thead-dark text-center">
										<tr>
											<th class="col-2">번호</th>
											<th class="col-2">관계</th>
											<th class="col-3">추천인</th>
											<th class="col-2">적립 포인트</th>
											<th class="col">등록일</th>
											<th class="col">-</th>
										</tr>
									</thead>

									<tbody>
@if (!empty($data) && count($data) > 0)
@foreach ($data AS $key => $row)
										<tr class="text-center align-middle text-nowrap">
											<td>{{ $key+1 }}</td>
											<td>{{ $row->depthname }}</td>
											<td><a href="/relations/memberdetail/{{ $row->user_seq }}" target="_blank" class="text-info">{{ $row->userid }}</a></td>
											<td class="text-right pr-4">{{ number_format($row->point, 2) }}</td>
											<td>{{ $row->created }}</td>
											<td>
												<button type="button" method-href="/relations/detail/{{ $row->idx }}" class="btn btn-sm btn-info">상세정보</button>
@if ($row->depth == 0)
												<button type="button" class="btn btn-sm btn-info" data-idx="{{ $row->idx }}" data-seq="{{ $row->user_seq }}" data-toggle="modal" data-size="modal-xl" data-target="#rates">기본요율</button>
@endif
											</td>
										</tr>
@endforeach
@else

										<tr>
											<td colspan="999" class="text-center">DATA NOT FOUND.</td>
										</tr>
@endif

									</tbody>
								</table>
							</div>
						</div>
						<!--		컨텐츠 영역 : 종료				-->




						<!--		페이징 영역 : 시작				-->
						<div class="row mt-3">
							<div class="col">
								<nav aria-label="Page navigation">
									<ul class="pagination justify-content-center select-none">
										<li class="page-item {{ $page == 1 ? 'disabled' : '' }}"><a href="?{{ $qs }}" class="page-link">처음</a></li>
										<li class="page-item {{ $end_page / 10 <= 1 ? 'disabled' : '' }}"><a href="?{{ $qs }}&page={{ $start_page-1 }}" class="page-link" data-click="188">이전</a></li>
@for ($p = $start_page; $p <= $end_page; $p++)
@if ($p == $page)
										<li class="page-item active"><span class="page-link">{{ $p }}</span></li>
@else
										<li class="page-item"><a class="page-link" href="?{{ $qs }}&page={{ $p }}" data-click="{{ $p == $page - 1 ? 37 : ($p == $page + 1 ? 39 : '') }}">{{ $p }}</a></li>
@endif
@endfor
										<li class="page-item {{ $end_page / 10 == $total_page / 10 ? 'disabled' : '' }}"><a href="?{{ $qs }}&page={{ $end_page+1 > $total_page ? $total_page : $end_page+1 }}" class="page-link" data-click="190">다음</a></li>
										<li class="page-item {{ $page == $total_page ? 'disabled' : '' }}"><a href="?{{ $qs }}&page={{ $total_page }}" class="page-link">끝</a></li>
									</ul>
								</nav>
							</div>
						</div>
						<!--		페이징 영역 : 종료				-->

					</div>
				</div>
			</div>
		</div>
	</div>



	<!--	하위유저 추가/변경 : 시작						-->
	<div id="childs" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.set') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="idx" value="" />

					<div class="modal-header"><h5 class="modal-title">추천인 신규등록</h5></div>

					<div class="modal-body">
						<table class="table table-bordered">
							<colgroup>
								<col class="col-4" />
								<col class="col-8" />
							</colgroup>
							<tbody class="thead-dark">
								<tr>
									<th>등록할 타입</th>
									<td>
										<select name="depth" class="form-control">
											<option value="">▒ 타입 선택 ▒</option>
@foreach ($depthname AS $key => $name)
@if ($key > 0)
											<option value="{{ $key }}">{{ $name }}</option>
@endif
@endforeach
@if(auth()->user()->can('permission') || auth()->user()->can('outer0'))
											<option value="6">유저</option>
@endif
										</select>
									</td>
								</tr>

								<tr data-target="parents" style="display:none;">
									<th>상위 소속</th>
									<td>
										<select name="parent_seq" class="form-control">
										</select>
									</td>
								</tr>

								<tr>
									<th rowspan="2">추천인</th>
									<td>
										<div class="input-group">
											<input type="text" name="search" value="" class="form-control" />
											<button type="button" data-button="usearch" class="btn btn-info">검색</button>
										</div>
									</td>
								</tr>

								<tr>
									<td id="relation_id"></td>
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
	<!--	하위유저 추가/변경 : 종료						-->



	<!--	요율 추가/변경 : 시작							-->
	<div id="rates" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.setrates') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header"><h5 class="modal-title">기본요율 등록</h5></div>

					<div class="modal-body" style="overflow: auto;">
						<table class="table table-bordered table-striped">
							<thead data-target="title-rate" class="thead-dark text-center">
							</thead>
							<tbody data-target="reg-rate">
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
	<!--	요율 추가/변경 : 종료							-->


<script>

$(function ()
{
	$('#childs').on('show.bs.modal', function (e)
	{
		let f = $(e.currentTarget).find('form');

		f.find('select[name="depth"]>option').eq(0).prop('selected', true);
	});


	$('#rates').on('show.bs.modal', function (e)
	{
		let x = $(e.relatedTarget).data('seq');
		let f = $(e.currentTarget).find('form');
		let hm = f.find('[data-target="title-rate"]').html('');
		let em = f.find('[data-target="reg-rate"]').html('');

		$.get(
			'/relations/rates/'+x,
			function (d)
			{
				if (d.msg)
					alert(d.msg);

				if (d.result != 0)
					return true;


				// 타이틀 출력.
				var tr = $('<tr>');
				tr.append($('<th>').text('게임타입'));
				for (var n of d.depthname)
				{
					tr.append($('<th>').text(n));
				}

				hm.append(tr);


				f.find('input[name="user_seq"]').val(d.master_seq);

				for (var i in d.rates)
				{
					var tr = $('<tr>');

					tr.append($('<td>').addClass('text-nowrap').text(d.code[i]));

					for (var j in d.rates[i])
						tr.append($('<td>').html('<span class="input-avg"><input type="number" name="rates['+ d.rates[i][j].code +']['+ d.rates[i][j].depth +']" value="'+ d.rates[i][j].rate1 +'" step="0.01" class="form-control form-control-sm text-center" style="min-width: 65px;" /></span>'));

					em.append(tr);
				}
			});
	});


	$(document).on('change', 'select[name="depth"]', function ()
	{
		let v = $(this).val();
		let f = $(this).parents('form').find('select[name="parent_seq"]').html('');

		if (v == 0)
			return $('[data-target="parents"]').hide(0);

		$('[data-target="parents"]').show(0);

		$.get(
			'/relations/parents/'+(v - 1),
			function (d)
			{
				if (d.msg)
					alert(d.msg);

				if (d.result != 0)
					return true;

				for (var i in d.data)
				{
					f.append('<option value="'+ d.data[i].user_seq +'">'+ d.data[i].userid +' ( '+ d.data[i].nickname +' )</option>');
				}
			});
	});


	$(document).on('click', '[data-button]', function ()
	{
		let f = $(this).parents('form');

		$.get(
			'/relations/'+$(this).data('button')+'/'+ f.find('input[name="search"]').val(),
			function (d)
			{
				if (d.msg)
					alert(d.msg);

				if (d.result != 0)
					return true;

				let em = f.find('#relation_id').html('');
				for (var i in d.data)
					em.append('<p><input type="hidden" name="seq[]" value="'+ d.data[i].user_seq +'" />'+ d.data[i].userid +' ( '+ d.data[i].nickname +' )</p>');
			});
	});


	$('form[name="frmSet"]').on('callback', function (e, d)
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
