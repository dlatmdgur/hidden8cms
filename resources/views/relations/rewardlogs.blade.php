@extends('layouts.mainlayout')

@section('content')
	<script src="/js/apis.js"></script>

<style>

.userbox {
	border: 1px solid #aaa;
	padding: 5px 10px;
}

.input-avg input[type="number"] {
	font-size: 15px;
	margin: 2px 20px 0 3px;
	margin-right: 10px;
}

.input-avg {
	position: relative;
}

.input-avg::after {
	content:	'%';
	position:	absolute;
	top:		5px;
	right:		5px;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}


.modal-rewards {
	max-width: 1120px;
}

@media (max-width: 1140px) {

	.modal-rewards {
		max-width: 97%;
	}

}

</style>

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">포인트 지급/환급 내역</li>
			</ol>

			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>포인트 지급내역</h2>
							</div>
						</div>

						<!--		검색 영역 : 시작				-->
						<form name="frmLogs" method="get" action="/relations/rewardlogs">
						<div class="form-row">
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
											<th>번호</th>
											<th>발송 유저</th>
											<th>수신 유저</th>
											<th>포인트</th>
											<th>상태</th>
											<th>등록일</th>
											<th>-</th>
										</tr>
									</thead>

									<tbody>
@if (!empty($data) && count($data) > 0)
@foreach ($data AS $key => $row)

										<tr class="text-center align-middle text-nowrap">
											<td>{{ $row->idx }}</td>
											<td><a href="/relations/memberdetail/{{ $row->sender_seq }}" target="_blank" class="text-info">{{ $row->sender_nickname }} ( {{ $row->sender_id }} )</td>
											<td><a href="/relations/memberdetail/{{ $row->user_seq }}" target="_blank" class="text-info">{{ $row->user_nickname }} ( {{ $row->user_id }} )</td>
											<td class="text-right pr-3 pl-5">{{ number_format($row->point) }}</td>
											<td>
@if ($row->status == 1)
												<button type="button" class="btn btn-sm btn-outline-success" data-reason="{{ nl2br($row->reason) }}" data-toggle="modal" data-target="#reason"><b>발송</b> 사유</button>
@else
												<button type="button" class="btn btn-sm btn-outline-danger" data-reason="{{ nl2br($row->reason_cancel) }}" data-toggle="modal" data-target="#reason"><b>취소</b> 사유</button>
@endif
											</td>

											<td>{{ $row->created }}</td>
											<td>
@if ($row->read != 1)
												<button type="button" class="btn btn-sm btn-danger" data-idx="{{ $row->idx }}" data-point="{{ $row->point }}" data-toggle="modal" data-target="#cancel">취소</button>
@else
												<small class="font-weight-bold text-danger" style="font-size: 0.8rem;">-</small>
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



	<!--	사유 모달 정의 : 시작						-->
	<div id="reason" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header"><h5 class="modal-title"><b>사유 보기</b></h5></div>

				<div class="modal-body" style="padding: 20px 15px 30px; min-height: 150px; font-size: 1rem;"></div>

				<div class="modal-footer">
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">닫기</button>
				</div>
			</div>
		</div>
	</div>
	<!--	사유 모달 정의 : 끝							-->



	<!--	사유 모달 정의 : 시작						-->
	<div id="cancel" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="frmCancel" method="post" action="{{ Route('relations.rewardcancel') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<div class="modal-header"><h5 class="modal-title"><b>포인트 지급발송 취소</b></h5></div>

					<div class="modal-body was-validated">
						<div class="form-row">
							<div class="col-12 mb-3">
								<label for="idx">회수할 지급번호</label>
								<input type="number" name="idx" class="form-control is-valid text-right pr-5" id="idx" value="" required readonly>
								<div class="invalid-feedback">지급번호가 올바르지 않습니다.</div>
							</div>

							<div class="col-12 mb-3">
								<label for="point">회수할 포인트</label>
								<input type="number" name="point" class="form-control is-valid text-right pr-5" id="point" value="" required readonly>
								<div class="invalid-feedback">회수할 포인트가 올바르지 않습니다.</div>
							</div>

							<div class="col-12 mb-3">
								<label for="reason">취소 사유 <small class="text-danger">* 필수</small></label>
								<textarea name="reason" class="form-control is-valid" id="reason" style="height: 150px; resize: none;" required></textarea>
								<div class="invalid-feedback">취소사유를 입력하세요.</div>
							</div>

							<div class="col-12 mb-3">
								<ul class="alert alert-danger text-light" role="alert">
									<p class="m-0 mb-2"><b>아래의 이유로 취소불가 할 수 있습니다.</b></p>
									<li class="ml-3">발송된 우편이 삭제된 경우 ( 기간만료 )</li>
									<li class="ml-3">대상 유저가 이미 보상을 받은 경우</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-danger" aria-label="modify">취소</button>
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">닫기</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--	사유 모달 정의 : 끝							-->


<script>

$(function ()
{
	$('#reason').on(
		'show.bs.modal',
		function (e)
		{
			$(this).find('.modal-body').html($(e.relatedTarget).data('reason'));
		});


	$('#cancel').on(
		'show.bs.modal',
		function (e)
		{
			let b = $(e.relatedTarget);
			let frm = $(e.currentTarget).find('form');

			frm[0].reset();

			for (var f of ['idx', 'point'])
				frm.find('input[name="'+ f +'"]').val(b.data(f));
		});


	$('form[name="frmCancel"]').on('callback', function (e, d)
	{
		if (d.msg)
			alert(d.msg);

		if (d.result != 0)
			return true;


		document.location.reload();
	});



});

</script>
@endsection
