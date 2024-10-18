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
                                    <span id="increase" style="color:mediumblue; font-weight:bold;">{{ number_format($increase) }}</span>
                                </div>
                            </div>
						</div>


@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
@endif


						<form name="frmLogs" method="get" action="/slotlogsv2/spin">
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
                            <div class="col-12 col-lg-5 mb-1">
                                <div class="input-group">
                                    <div class="input-group-prepend ml-4 mt-2">
                                        <input class="form-check-input" type="checkbox" id="game_type" name="game_type" value="F" {{ (empty($game_type) || $game_type != "F" ? "" : "checked") }} />
                                        <label class="form-check-label h6" for="game_type">프리스핀만</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary ml-2 pl-4 pr-4">검색</button>
                                </div>
							</div>
						</div>
						</form>


						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-sm table-bordered table-hover table-striped text-md mb-0">
									<thead class="thead-dark text-center text-nowrap">

										<tr>
											<th rowspan="2">로그ID</th>
											<th rowspan="2">게임일자</th>
											<th rowspan="2">슬롯ID</th>
											<th rowspan="2">USER SEQ</th>

											<th colspan="2">소비금액</th>

											<th colspan="5">상금</th>

											<th rowspan="2">게임분류</th>
											<th rowspan="2">상금종류</th>
											<th rowspan="2" width="180">덱</th>

											<th colspan="3">제화 변화</th>

											<th colspan="2">버전</th>
											<th colspan="3">RTP</th>

											<th colspan="5" class="select-none" style="background-color: #555; color: orange;">개발자 전용</th>

										</tr>

										<tr>
											<th>배팅</th>
											<th>구입</th>

											<th>총합</th>
											<th>일반</th>
											<th>프리스핀</th>
											<th>보너스</th>
											<th>잭팟</th>

											<th>전</th>
											<th>후</th>
											<th>증/감</th>

											<th>서버</th>
											<th>클라</th>

											<th>전</th>
											<th>후</th>
											<th>버프</th>

											<th class="select-none" style="background-color: #555; color: #FF8000;">릴 레벨</th>
											<th class="select-none" style="background-color: #555; color: #FF8000;">릴 RTP</th>
											<th class="select-none" style="background-color: #555; color: #FF8000;">릴 번호</th>
											<th class="select-none" style="background-color: #555; color: orange;">RTP(전)</th>
											<th class="select-none" style="background-color: #555; color: orange;">RTP(후)</th>
										</tr>

									</thead>

									<tbody id="data" class="text-nowrap align-middle">
@if (count($data) > 0)
	@foreach ($data AS $index => $row)

										<tr class="text-center">
											<td class="text-right pl-3 pr-2">{{ number_format($row->idx) }}</td>
											<td class="pl2 pr-2">{{ $row->created }}</td>
											<td class="pl2 pr-2">{{ empty($slotname[$row->slot_id]) ? $row->slot_id : $slotname[$row->slot_id] }} <small class="text-muted">{{ $row->slot_id }}</small></td>
                                            <td class="text-right pl-3 pr-2"><a href="/slotusers/detail?id={{ $row->uid }}" target="_blank">{{ $row->uid }}</a></td>
		@if (!empty($row->results->iFee))

											<td class="text-right pl-3 pr-2" style="color: #CCC;">{{ $row->bet > 0 ? number_format($row->bet) : '-' }}</td>
											<td class="text-right pl-3 pr-2">{{ number_format($row->results->iFee) }}</td>
		@else

											<td class="text-right pl-3 pr-2">{{ $row->bet > 0 ? number_format($row->bet) : '-' }}</td>
											<td class="text-right pl-3 pr-2">-</td>
		@endif

											<td class="text-right pl-3 pr-2">{{ $row->payout > 0 ? number_format($row->payout) : '-' }}</td>
											<td class="text-right pl-3 pr-2">{{ ($row->payout - $row->free_payout - $row->bonus_payout) > 0 ? number_format($row->payout - $row->free_payout - $row->bonus_payout) : '-' }}</td>
											<td class="text-right pl-3 pr-2">{{ $row->free_payout > 0 ? number_format($row->free_payout) : '-' }}</td>
											<td class="text-right pl-3 pr-2">{{ $row->bonus_payout > 0 ? number_format($row->bonus_payout) : '-' }}</td>
											<td class="text-right pl-3 pr-2">{{ $row->jackpot_payout > 0 ? number_format($row->jackpot_payout) : '-' }}</td>

											<td><span class="badge badge-{{ ($row->game_type == 'F' ? 'danger' : ($row->game_type == 'B' ? 'warning' : 'success')) }}">{{ $row->game_type }}</span></td>
		@if ($row->results->aWinType[0] >= 32)

											<td><span class="badge badge-danger">S</span></td>
		@elseif ($row->results->aWinType[0] >= 16)

											<td><span class="badge badge-warning">M</span></td>
		@elseif ($row->results->aWinType[0] >= 8)

											<td><span class="badge badge-primary">B</span></td>
		@else

											<td>-</td>
		@endif

		@if (in_array($row->game_type, ['N','F']))
			@if (!empty($row->results->aDecks))
											<td>{{ json_encode($row->results->aDecks[0]) }}</td>
			@else
											<td>-</td>
			@endif
		@else

											<td>-</td>
		@endif

											<td class="text-right pl-3 pr-2">{{ number_format($row->bef_coins + $row->bef_bonus) }}</td>
											<td class="text-right pl-3 pr-2">{{ number_format($row->aft_coins + $row->aft_bonus) }}</td>
											@php $chgGoodsAmt = (($row->aft_coins + $row->aft_bonus) - ($row->bef_coins + $row->bef_bonus)) @endphp
											<td class="text-right pl-3 pr-2 text-{{ $chgGoodsAmt > 0 ? 'success' : 'danger'}}">{{  ($chgGoodsAmt > 0 ? '+' : '').number_format($chgGoodsAmt)  }}</td>

											<td data-type="ver" class="pl-2 pr-2">{{ $row->server_ver }}</td>
											<td data-type="ver" class="pl-2 pr-2">{{ $row->client_ver }}</td>

											<td class="text-right pl-3 pr-2" data-type="avg">{{ $row->bef_rtp }}</td>
											<td class="text-right pl-3 pr-2" data-type="avg">{{ $row->aft_rtp }}</td>
											<td class="text-right pl-3 pr-2" data-type="avg">{{ $row->buff_rtp }}</td>
                                            @if ($row->pick == null)
                                                <td colspan="3">릴 데이터 알 수 없음</td>
                                            @elseif ($row->pick[0][0] == -1 && $row->pick[0][1] == -1)
                                                <td colspan="3">임의지급</td>
                                            @else
                                                <td data-type="level">{{ @implode(' ~ ', $row->pick[0]) }}</td>
                                                <td data-type="rtp">{{ @implode(' ~ ', $row->pick[1]) }}</td>
                                                <td data-type="no">{{ @$row->pick[2] }}</td>
                                            @endif
											<td class="text-right pl-3 pr-2" data-type="avg">{{ $row->buff_rtp < 0 ? ($row->bef_rtp - ($row->buff_rtp)) : ($row->bef_rtp - $row->buff_rtp) }}</td>
											<td class="text-right pl-3 pr-2" data-type="avg">{{ $row->buff_rtp < 0 ? ($row->aft_rtp - ($row->buff_rtp)) : ($row->aft_rtp - $row->buff_rtp) }}</td>
										</tr>
	@endforeach
@else

										<tr>
											<td colspan="999" class="text-center p-3"><b><i>DATA NOT FOUND.</i></b></td>
										</tr>
@endif

									</tbody>
								</table>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col">
								<nav aria-label="Page navigation">
									<ul class="pagination justify-content-center select-none">
                                        <li class="page-item {{ $page == 1 ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&game_type={{ $game_type }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page=1&limit={{ $limit }}" class="page-link">처음</a></li>
                                        <li class="page-item {{ $end_page / 10 <= 1 ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&game_type={{ $game_type }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $start_page-1 }}&limit={{ $limit }}" class="page-link" data-click="188">이전</a></li>

@for ($p = $start_page; $p <= $end_page; $p++)
	@if ($p == $page)

										<li class="page-item active"><span class="page-link">{{ $p }}</span></li>
	@else

                                                <li class="page-item"><a class="page-link" href="?uid={{ $uid }}&slot_id={{ $slot_id }}&game_type={{ $game_type }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $p }}&limit={{ $limit }}" data-click="{{ $p == $page - 1 ? 37 : ($p == $page + 1 ? 39 : '') }}">{{ $p }}</a></li>
	@endif
@endfor
                                        <li class="page-item {{ $end_page / 10 == $total_page / 10 ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&game_type={{ $game_type }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $end_page+1 > $total_page ? $total_page : $end_page+1 }}&limit={{ $limit }}" class="page-link" data-click="190">다음</a></li>
                                        <li class="page-item {{ $page == $total_page ? 'disabled' : '' }}"><a href="?uid={{ $uid }}&slot_id={{ $slot_id }}&game_type={{ $game_type }}&date={{ $date }}&stime={{ $stime }}&etime={{ $etime }}&page={{ $total_page }}&limit={{ $limit }}" class="page-link">끝</a></li>
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

$(function ()
{
	$('body').on('keyup', function (e)
	{
		if ($('a[data-click="'+e.keyCode+'"]').length > 0)
			document.location.href = $('a[data-click="'+e.keyCode+'"]').attr('href');

		e.preventDefault();
	});
});

</script>
@endsection
