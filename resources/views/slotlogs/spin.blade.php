@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">슬롯 로그</a></li>
				<li class="breadcrumb-item">스핀 로그</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-9 margin-tb">
								<div class="pull-left">
									<h2>스핀 로그</h2>
								</div>
							</div>
                            <div class="col-lg-3 mt-2">
                                <div class="pull-right">
                                    증감머니 :
                                    <span id="increase" style="color:mediumblue; font-weight:bold;">{{ number_format($increase, 3) }}</span>
                                </div>
                            </div>
						</div>


@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
@endif


						<form name="frmLogs" method="get" action="/slotlogs/spin">
						<div class="row">
							<div class="col-12 col-lg-5 mb-1">
								<input type="number" name="uid" value="{{ $uid }}" step="1" class="form-control" />
							</div>
							<div class="col-12 col-lg-5 mb-1">
								<select name="slot_id" class="form-control">
									<option value="">-- 슬롯 전체 --</option>
@if (count($slots) > 0)
	@foreach ($slots AS $row)

									<option value="{{ $row->slot_id }}" {{ $slot_id == $row->slot_id ? 'SELECTED' : '' }}>{{ $row->slot_name }}</option>
	@endforeach
@endif
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-lg-5 mb-1">
								<input type="date" name="date" value="{{ empty($date) ? date('Y-m-d') : $date }}" class="form-control" />
							</div>
							<div class="col-12 col-lg-5">
								<div class="row">
									<div class="col-12 col-sm-6 mb-1"><input type="time" name="stime" value="{{ empty($stime) ? '00:00' : $stime }}" class="form-control" /></div>
									<div class="col-12 col-sm-6 mb-1"><input type="time" name="etime" value="{{ empty($etime) ? '23:59' : $etime }}" class="form-control" /></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-lg-5 mb-1">
								<select name="limit" class="form-control">
									<option value="20" {{ $limit == 20 ? 'SELECTED' : '' }}>20 개씩</option>
									<option value="40" {{ $limit == 40 ? 'SELECTED' : '' }}>40 개씩</option>
									<option value="80" {{ $limit == 80 ? 'SELECTED' : '' }}>80 개씩</option>
									<option value="100" {{ $limit == 100 ? 'SELECTED' : '' }}>100 개씩</option>
								</select>
							</div>
							<div class="col-12 col-lg-3 mb-1">
								<button type="submit" class="btn btn-primary pl-4 pr-4">검색</button>
							</div>
						</div>
						</form>


						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<form name="frmSlot" method="post" action="/refer/slotlist" onsubmit="return false;" method-transfer="async" class="m-0">
								<table class="table table-sm table-bordered table-hover table-striped mb-0">
									<thead class="thead-dark text-center align-bottom">
										<tr>
											<th rowspan="2">로그ID</th>
											<th rowspan="2">게임일자</th>
											<th rowspan="2">슬롯ID</th>
											<th rowspan="2">UID</th>
											<th rowspan="2">레벨</th>
											<th rowspan="2">배팅금액</th>
											<th colspan="2">스핀상금</th>
											<th rowspan="2">잭팟상금</th>
											<th rowspan="2">프리윈</th>
											<th rowspan="2">보너스윈</th>
											<th rowspan="2">잭팟종류</th>
											<th rowspan="2">프리스핀여부</th>
											<th rowspan="2">족보</th>
											<th rowspan="2">스핀전 코인</th>
											<th rowspan="2">스핀후 코인</th>
											<th rowspan="2">클라이언트버전</th>
											<th rowspan="2">유저RTP (버프%)<br />사용한 버전</th>
										</tr>
										<tr>
											<th>합</td>
											<th>노말</td>
										</tr>
									</thead>

									<tbody id="data">
@if (count($data) > 0)
	@foreach ($data AS $index => $row)

										<tr class="text-center align-middle"">
											<td class="text-center">{{ $row['id'] }}</td>
											<td class="text-center">{{ $row['time'] }}</td>
											<td class="text-center">{{ $row['slot_id'] }}</td>
											<td class="text-center">{{ $row['uid'] }}</td>
											<td class="text-center">{{ number_format($row['level']) }}</td>
											<td class="text-right pr-2">{{ number_format($row['bet'] / $divInt, 3) }}</td>
											<td class="text-right pr-2">{{ number_format($row['spin_win'] / $divInt, 3) }}</td>
											<td class="text-right pr-2">{{ number_format($row['normal_win'] / $divInt, 3) }}</td>
											<td class="text-right pr-2">{{ number_format($row['jackpot_win'] / $divInt, 3) }}</td>
											<td class="text-right pr-2">{{ number_format($row['free_win'] / $divInt, 3) }}</td>
											<td class="text-right pr-2">{{ number_format($row['bonus_win'] / $divInt, 3) }}</td>
											<td class="text-center">{{ number_format($row['win_type']) }}</td>
											<td class="text-center">{{ $row['in_free_symbol'] }}</td>
											<td class="text-left pl-2 pr-2">{{ $row['deck'] }}</td>
											<td class="text-right pr-2">{{ number_format($row['cli_coins'], 3) }}</span></td>
											<td class="text-right pr-2">{{ number_format($row['coins'], 3) }}</span></td>
											<td class="text-center">{{ $row['client_ver'] }}</td>
											<td class="text-right pr-2">
		@if (!empty($row['spin_result']->rtp))
												{{ number_format(max(0, $row['spin_result']->rtp[0] + $row['spin_result']->rtp[1]), 2) }} ( <span data-type="avg">{{ $row['spin_result']->rtp[1] }}</span> )
			@if (!empty($row['spin_result']->rtp[2]))

												<span class="ml-1" data-type="ver">{{ $row['spin_result']->rtp[2] }}</span>
			@endif
		@else
												-
		@endif

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

						<div class="row mt-3">
							<div class="col">
								<nav aria-label="Page navigation">
									<ul class="pagination justify-content-center">
										<li class="page-item {{ $page == 1 ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page=1&limit={{ $limit }}" class="page-link">처음</a></li>
										<li class="page-item {{ $end_page / 10 <= 1 ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $start_page-1 }}&limit={{ $limit }}" class="page-link">이전</a></li>

@for ($p = $start_page; $p <= $end_page; $p++)
										<li class="page-item{{ $p == $page ? ' active' : '' }}"><a class="page-link" href="?uid={{ $uid }}&slot_id={{ $slot_id }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $p }}&limit={{ $limit }}">{{ $p }}</a></li>
@endfor
										<li class="page-item {{ $end_page / 10 == $total_page / 10 ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $end_page+1 }}&limit={{ $limit }}" class="page-link">다음</a></li>
										<li class="page-item {{ $page == $total_page ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $total_page }}&limit={{ $limit }}" class="page-link">끝</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<script>


</script>
@endsection
