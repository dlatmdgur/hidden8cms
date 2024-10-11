@extends('layouts.mainlayout')

@section('content')
	<script src="/js/apis.js"></script>

<style>

.userbox {
	border:				1px solid #aaa;
	padding:			5px 10px;
}

.input-avg input[type="number"] {
	font-size:			15px;
	margin:				2px 20px 0 3px;
	margin-right:		10px;
}

.input-avg {
	position:			relative;
}

.input-avg::after {
	content:			'%';
	position:			absolute;
	top:				5px;
	right:				5px;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance:	none;
  margin:				0;
}

/* Firefox */
input[type=number] {
  -moz-appearance:		textfield;
}


.modal-rewards {
	max-width:			1120px;
}

@media (max-width: 1140px) {

	.modal-rewards {
		max-width:		97%;
	}

}

</style>

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">포인트 지급</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>지급가능 유저목록</h2>
							</div>
						</div>

						<!--		검색 영역 : 시작				-->
						<form name="frmLogs" method="get" action="/relations/rewards">
						<div class="form-row">
							<div class="col-6 col-sm-3 col-md-2 mb-2">
								<select name="d" class="form-control">
@foreach ($depthname AS $key => $name)
@if (0 <= $key && $key <= 2)
									<option value="{{ $key }}" {{ $d == $key ? 'SELECTED' : '' }}>{{ $name }}</option>
@endif
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
												<button type="button" class="btn btn-sm btn-info" data-point="{{ $row->point }}" data-parent="{{ $row->user_seq }}" data-depth="{{ $row->depth }}" data-toggle="modal" data-target="#rewards">포인트 지급 (타인)</button>
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



	<!--	포인트지급 처리 : 시작						-->
	<div id="rewards" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-rewards">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.rewardset') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="parent_seq" value="" />
					<input type="hidden" name="parent_depth" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>적립 포인트 지급</b></h5></div>

					<div class="modal-body">
						<div class="row">
							<div class="col-12 col-md-12 mb-3">
								<div class="card">
									<div class="card-header pt-1 pb-0 pl-1 pr-0">
										<div class="input-group col-12 col-md-8 m-0">

											<div class="input-group-prepend">
												<select name="depth" class="form-control">
@foreach ($depthname AS $key => $name)
@if ($d < $key)
													<option value="{{ $key }}" {{ $d == $key ? 'SELECTED' : '' }}>{{ $name }}</option>
@endif
@endforeach
												</select>
											</div>

											<input type="text" name="keyword" class="form-control" value="qa" placeholder="검색할 유저를 입력하세요."  aria-describedby="btn-search">
											<div class="input-group-append">
												<button class="btn btn-info" type="button" id="btn-search">검색</button>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4 mb-3">
								<div class="card" style="height: 460px;">
									<div class="card-header"><h6 class="m-0"><b>검색된 유저</b></h6></div>
									<div class="card-body p-0">
										<select name="search" data-select-move="picks[]" class="form-control" style="height: 100%;" multiple></select>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4 mb-3">
								<div class="card" style="height: 460px;">
									<div class="card-header"><h6 class="m-0"><b>선택된 유저</b></h6></div>
									<div class="card-body p-0">
										<select name="picks[]" data-select-move="search" class="form-control" style="height: 100%;" multiple required></select>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4 mb-3">
								<div class="card" style="height: 460px;">
									<div class="card-header"><h6 class="m-0"><b>지급 조건</b></h6></div>
									<div class="card-body was-validated">
										<div class="form-row">
											<div class="col-12 mb-3">
												<label for="total-point">지급가능 포인트 (골드)</label>
												<input type="number" name="total_point" step="1" min="0" class="form-control is-valid text-right pr-5" id="total-point" value="" readonly required>
											</div>

											<div class="col-12 mb-3">
												<label for="point">각각 지급할 포인트 (골드) <small class="text-danger">* 필수</small></label>
												<input type="number" name="point" step="1" min="0" max-length="20" class="form-control is-valid text-right pr-5" id="point" value="" required>
												<div class="invalid-feedback">지급할 포인트가 유효하지 않습니다.</div>
											</div>

											<div class="col-12 mb-3">
												<label for="reason">지급 사유 <small class="text-danger">* 필수</small></label>
												<textarea name="reason" class="form-control is-valid" id="reason" style="height: 150px; resize: none;" required></textarea>
												<div class="invalid-feedback">지급사유를 입력하세요.</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" aria-label="modify">지급</button>
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--	포인트지급 처리 : 끝						-->

<script>

$(function ()
{
	/**
	 * 검색 처리.
	 *
	 */
	$('#btn-search').on('click', function (e)
	{
		let frm = $(this).parents('form');

		$.get(
			['/relations', 'childs', ...['parent_seq', 'depth', 'keyword'].map((m) => { return frm.find('[name="'+ m +'"]').val(); })].join('/'),
			function (d)
			{
				let s = frm.find('[name="search"]').html('');

				if (d == undefined)
					return alert('잘못된 요청입니다.');

				if (d.msg)
					alert(d.msg);

				if (d.result != 0)
					return true;


				for (var i of d.data)
					s.append($('<option>').val(i.user_seq).text(i.nickname+' ('+i.userid+')'));
			});
	});



	/**
	 * SELECT 끼리 데이터 스위치.
	 *
	 */
	$('select[data-select-move]').on('dblclick', function ()
	{
		let frm = $(this).parents('form');

		if ($(this).data('select-move') == '')
			$($(this)[0][$(this)[0].selectedIndex]).remove();
		else
		{
			if (frm.find('[name="'+ $(this).data('select-move') +'"] > option[value="'+$($(this)[0][$(this)[0].selectedIndex]).val()+'"]').length > 0)
			{
				alert('이미 등록된 유저입니다.');
				return true;
			}

			frm.find('[name="'+ $(this).data('select-move') +'"]').append($(this)[0][$(this)[0].selectedIndex]);
		}
	});



	/**
	 * 키워드 검색이벤트 정의.
	 *
	 */
	$('input[name="keyword"]').on('keypress', function (e)
	{
		if (e.keyCode == 13)
		{
			$('#btn-search').trigger('click');
			return false;
		}
	});



	/**
	 * 포인트 지급 모달 오픈시 이벤트.
	 *
	 */
	$('#rewards')
		.on('show.bs.modal', function (e){
			let frm = $(e.currentTarget).find('form');

			frm.find('select[data-select-move], input').each(function (){ $(this).val('').html(''); });
			frm.find('input[name="parent_seq"]').val($(e.relatedTarget).data('parent'));
			frm.find('input[name="parent_depth"]').val($(e.relatedTarget).data('depth'));
			frm.find('input[name="total_point"]').val($(e.relatedTarget).data('point'));
			$('#btn-search').trigger('click');
		})
		.find('form').on('submit', function (){ /* 모든 선택유저를 active 처리 한다. */ $(this).find('select[name="picks[]"] > option').each(function (e, d, n){ $(d).prop('selected', true); }); return true; });



	/**
	 * 포인트 지급 후, 콜백 처리.
	 *
	 */
	$('form[name="frmSet"]').on('callback', function (e, d)
	{
		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 0)
			return false;

		$(this)[0].reset();
		$(this).find('input[name="keyword"]').val('');
		$(this).find('input[name="total_point"]').val(d.total_point);
		$(this).find('select[data-select-move]').each(function (){ $(this).html(''); });
//		document.location.reload();
	});
});

</script>
@endsection
