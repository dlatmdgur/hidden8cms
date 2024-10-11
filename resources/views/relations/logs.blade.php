@extends('layouts.mainlayout')
@section('content')

<style>

</style>


	<script src="/js/apis.js"></script>
	<style>

	table .thin td {
		padding: 9px;
	}

	</style>

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">배팅 현황</li>
			</ol>

			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>검색 정보</h2>
							</div>
						</div>

						<!--		검색 영역 : 시작				-->
						<form name="frmLogs" method="get" action="{{ $_SERVER['REQUEST_URI'] }}">
						<div class="form-row">
							<div class="col-12 col-sm-6 col-md-3 col-xl-2 mb-2">
								<input type="date" name="s" value="{{ $s }}" max="{{ date('Y-m-d') }}" class="form-control" required >
							</div>

							<div class="col-12 col-sm-6 col-md-3 col-xl-2 mb-2">
								<input type="date" name="e" value="{{ $e }}" max="{{ date('Y-m-d') }}" class="form-control" required >
							</div>

							<div class="col-12 col-sm-6 col-md-3 col-xl-2 mb-2">
								<select name="t" class="form-control">
									<option value="u" {{ $t == 'u' ? 'SELECTED' : '' }}>아이디</option>
									<option value="n" {{ $t == 'n' ? 'SELECTED' : '' }}>닉네임</option>
								</select>
							</div>

							<div class="col-12 col-sm-6 col-md-3 mb-2">
								<div class="form-row">
									<div class="col-8">
										<input type="text" name="q" value="{{ $q }}" class="form-control" />
									</div>
									<div class="col-4">
										<button type="submit" class="btn btn-primary text-nowrap">검색</button>
									</div>
								</div>
							</div>
						</div>
						</form>
						<!--		검색 영역 : 종료				-->



						<!--		출력 영역 : 시작				-->
						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-bordered table-striped">
									<thead class="thead-dark text-center">
										<tr>
											<th>관계</th>
											<th>아이디 ( 닉네임 )</th>
											<th>하위유저 ( 전체유저 )</th>
											<th>보유 골드</th>
											<th>적립 포인트</th>
											<th>총 구입 (A)</th>
											<th>총 배팅 (B)</th>
											<th>총 상금 (C)</th>
											<th>손익 ( A＋B－C )</th>
											<th>손익률 (％)</th>
										</tr>
									</thead>

									<tbody class="thin">
@if (!empty($data) && count($data))
	@php ( $total_childs	= 0 )
	@php ( $total_totals	= 0 )
	@php ( $total_gold		= 0 )
	@php ( $total_point		= 0 )
	@php ( $total_purchase	= 0 )
	@php ( $total_spend		= 0 )
	@php ( $total_payout	= 0 )
	@php ( $total_gains		= 0 )
	@foreach ($data AS $key => $row)
		@php ( $row->childs			= empty($row->childs) ? 0 : $row->childs )
		@php ( $row->totals			= empty($row->totals) ? 0 : $row->totals )
		@php ( $row->sum_point		= empty($row->sum_point) ? 0 : $row->sum_point )
		@php ( $row->sum_purchase	= empty($row->sum_purchase) ? 0 : $row->sum_purchase )
		@php ( $row->sum_spend		= empty($row->sum_spend) ? 0 : $row->sum_spend )
		@php ( $row->sum_payout		= empty($row->sum_payout) ? 0 : $row->sum_payout )
		@php ( $row->sum_gains		= empty($row->sum_gains) ? 0 : $row->sum_gains )
		@php ( $row->gains_avg      = $row->sum_payout <= 0 || ($row->sum_purchase + $row->sum_spend) <= 0 ? 0 : @(($row->sum_payout * 100) / ($row->sum_purchase + $row->sum_spend)) )

		@php ( $total_childs		= $total_childs + $row->childs )
		@php ( $total_totals		= $total_totals + $row->totals )
		@php ( $total_gold			= $total_gold + $row->gold )
		@php ( $total_point			= $total_point + $row->sum_point )
		@php ( $total_purchase		= $total_purchase + $row->sum_purchase )
		@php ( $total_spend			= $total_spend + $row->sum_spend )
		@php ( $total_payout		= $total_payout + $row->sum_payout )
		@php ( $total_gains			= $total_gains + $row->sum_gains )
										<tr class="text-right">
		@if ($key == 0)
											<td class="text-center" rowspan="{{ count($data) }}">{{ empty($depthname[$depth]) ? '유저' : $depthname[$depth] }}</td>
		@endif
		@if (empty($depthname[$depth]))
											<td class="text-left pl-3">{{ $row->userid }} ( {{ $row->nickname }} )</td>
		@else
											<td class="text-left pl-3"><a href="{{ route('relations.logs') }}/{{ $row->user_seq }}?s={{ $s }}&e={{ $e }}">{{ $row->userid }} ( {{ $row->nickname }} )</a></td>
		@endif
											<td class="pr-3">{{ number_format($row->childs) }} ( {{ number_format($row->totals) }} )</td>
											<td class="pr-3">{{ number_format($row->gold) }}</td>
											<td class="pr-3">{{ number_format($row->sum_point, 2) }}</td>
											<td class="pr-3">{{ number_format($row->sum_purchase, 2) }}</td>
											<td class="pr-3">{{ number_format($row->sum_spend, 2) }}</td>
											<td class="pr-3">{{ number_format($row->sum_payout, 2) }}</td>
											<td class="pr-3 text-{{ $row->sum_gains >= 0 ? 'primary' : 'danger' }}">{{ number_format($row->sum_gains, 2) }}</td>
											<td class="pr-3 text-{{ $row->sum_gains >= 0 ? 'primary' : 'danger' }}" data-type="avg">{{ is_nan($row->gains_avg) || in_array(intval($row->gains_avg), [0, 100]) ? '0.00' : number_format($row->gains_avg > 100 ? ($row->gains_avg - 100) : -(100 - $row->gains_avg), 2) }}</td>
										</tr>
	@endforeach

	@php ( $gains_avg		= $total_payout <= 0 || ($total_purchase + $total_spend) <= 0 ? 0 : @(($total_payout * 100) / ($total_purchase + $total_spend)) )
										<tr class="text-right font-weight-bold bg-warning">
											<td colspan="2" class="text-center">합계</td>
											<td class="pr-3">{{ number_format($total_childs) }} ( {{ number_format($total_totals) }} )</td>
											<td class="pr-3">{{ number_format($total_gold) }}</td>
											<td class="pr-3">{{ number_format($total_point, 2) }}</td>
											<td class="pr-3">{{ number_format($total_purchase, 2) }}</td>
											<td class="pr-3">{{ number_format($total_spend, 2) }}</td>
											<td class="pr-3">{{ number_format($total_payout, 2) }}</td>
											<td class="pr-3 text-{{ $total_gains >= 0 ? 'primary' : 'danger' }}">{{ number_format($total_gains, 2) }}</td>
											<td class="pr-3 text-{{ $total_gains >= 0 ? 'primary' : 'danger' }}" data-type="avg">{{ is_nan($gains_avg) || in_array($gains_avg, [0, 100]) ? '0.00' : number_format($gains_avg > 100 ? ($gains_avg - 100) : -(100 - $gains_avg), 2) }}</td>
										</tr>
@else
										<tr>
											<td colspan="999" class="text-center pt-5 pb-5">DATA NOT FOUND</td>
										</tr>
@endif
									</tbody>
								</table>
							</div>
						</div>
						<!--		출력 영역 : 종료				-->



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



	<!--	유저 삭제 : 시작								-->
	<div id="info" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.drop') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>유저 기본 정보</b></h5></div>

					<div class="modal-body" style="overflow: auto;">
						<table class="table table-borderless table-striped">
							<colgroup>
								<col class="col-4" />
								<col class="col-8" />
							</colgroup>
							<tbody>
								<tr><th>아이디</th><td id="userid"></td></tr>
								<tr><th>닉네임</th><td id="nickname"></td></tr>
								<tr><th>유저번호</th><td id="user_seq"></td></tr>
								<tr><th>포인트</th><td id="point"></td></tr>
								<tr><th>관계제거</th><td id="drop"></td></tr>
							</tbody>
						</table>
					</div>

					<div class="modal-footer">
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">닫기</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--	유저 삭제 : 종료								-->



<script>



</script>


@endsection
