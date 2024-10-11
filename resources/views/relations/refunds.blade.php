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
				<li class="breadcrumb-item">포인트 환급</li>
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
						<form name="frmLogs" method="get" action="{{ Route('relations.refunds') }}">
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
@if ($row->point > 0 && $row->point >= $minimum)
												<button type="button" class="btn btn-sm btn-info" data-point="{{ (empty($row->point) ? '0.00' : $row->point) }}" data-parent="{{ $row->user_seq }}" data-depth="{{ $row->depth }}" data-toggle="modal" data-target="#refund">포인트 환급 (본인)</button>
@else
												<button type="button" class="btn btn-sm btn-danger" onclick="javascript: alert('환급가능한 포인트가 부족합니다.\n환급하려면 최소 <?php echo number_format($minimum, 2); ?> P가 필요합니다.');">환급불가</button>
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



	<!--	포인트지급 처리 : 시작						-->
	<div id="refund" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.refundset') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="parent_seq" value="" />
					<input type="hidden" name="parent_depth" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>적립 포인트 지급</b></h5></div>

					<div class="modal-body">
						<div class="form-row">
							<div class="col-12 mb-3">
								<label for="total-point">지급가능 포인트 (골드)</label>
								<input type="text" name="total_point" step="1" min="0" class="form-control is-valid text-right pr-5" id="total-point" value="" readonly required>
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
	let minimum = {{ $minimum }};

	/**
	 * 포인트 지급 모달 오픈시 이벤트.
	 *
	 */
	$('#refund')
		.on('show.bs.modal', function (e)
		{
			let cur = $(e.currentTarget);
			let rel = $(e.relatedTarget);

			let frm = cur.find('form');

			frm[0].reset();

			frm.find('[name="parent_seq"]').val(rel.data('parent'));
			frm.find('[name="parent_depth"]').val(0); //rel.data('depth'));
			frm.find('[name="total_point"]').val(parseFloat(rel.data('point')).addComma(2));
		});



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

		document.location.reload();
	});
});

</script>
@endsection
