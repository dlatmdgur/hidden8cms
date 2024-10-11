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

</style>

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotlogs.daily') }}">사용자조회</a></li>
				<li class="breadcrumb-item">포인트 적립내역</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2>추천인 포인트 적립내역</h2>
							</div>
						</div>

						<!--		검색 영역 : 시작				-->
						<form name="frmLogs" method="get" action="/relations/points">
						<div class="form-row">
							<div class="col-12 col-sm-6 col-md-3 mb-2">
								<input type="date" name="s" value="{{ $s }}" class="form-control form-control" />
							</div>
							<div class="col-12 col-sm-6 col-md-3 mb-2">
								<input type="date" name="e" value="{{ $e }}" class="form-control form-control" />
							</div>
							<div class="col-12 col-sm-3 col-md-2 mb-2">
								<select name="d" class="form-control">
@foreach ($depthname AS $key => $name)
									<option value="{{ $key }}" {{ $d == $key ? 'SELECTED' : '' }}>{{ $name }}</option>
@endforeach
								</select>
							</div>

							<div class="col-12 col-sm-9 col-md-4 mb-2">
								<div class="input-group">
									<select name="t" class="form-control col-sm-4">
										<option value="u" {{ $t == 'u' ? 'SELECTED' : '' }}>아이디</option>
										<option value="n" {{ $t == 'n' ? 'SELECTED' : '' }}>닉네임</option>
									</select>
									<input type="text" name="q" value="{{ $q }}" class="form-control" />
									<div class="input-group-append">
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
											<th>날짜</th>
											<th>번호</th>
											<th>관계</th>
											<th>추천인</th>
											<th>배팅비</th>
											<th>적립포인트</th>
											<th>상세내역</th>
										</tr>
									</thead>

									<tbody class="text-center align-middle">
@if (!empty($points) && count($points) > 0)
@foreach ($points AS $key => $row)
										<tr>
											<td>{{ implode('-', [substr($row->datecode, 0, 4), substr($row->datecode, 4, 2), substr($row->datecode, 6, 2)]) }}</td>
											<td>{{ $row->idx }}</td>
											<td>{{ $depthname[$row->depth] }}</td>
											<td><a href="/relations/memberdetail/{{ $row->user_seq }}" target="_blank" class="text-info">{{ $row->nickname }} ( {{ $row->user_seq }} )</a></td>
											<td class="text-right pr-4">{{ number_format($row->total_spend + $row->total_purchase, 2) }}</td>
											<td class="text-right pr-4">{{ number_format($row->total_point, 2) }}</td>
											<td>
												<button type="button" class="btn btn-info btn-sm" data-user="{{ $row->user_seq }}" data-nickname="{{ $row->nickname }}" data-date="{{ $row->datecode }}" data-toggle="modal" data-target="#detail">상세내역</button>
											</td>
										</tr>
@endforeach
@else
										<tr>
											<td colspan="999" class="text-center" height="100">DATA NOT FOUND.</td>
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



	<!--	포인트 상세내역 : 시작						-->
	<div id="detail" class="modal fade" tabindex="-1">
		<div class="modal-dialog" style="min-width: 95%;">
			<div class="modal-content">
				<form name="frmGet" method="get" action="" onsubmit="return false">

				</form>
				<div class="modal-header"><h5 class="modal-title"><span id="nickname"></span> - 포인트 적립 내역</h5></div>
				<div class="modal-body p-0" style="overflow-x: auto; overflow-y: hidden; max-height: 95%;">
					<table class="table table-bordered table-striped m-0">
						<thead class="thead-dark text-center text-nowrap">
							<tr>
								<th>번호</th>
								<th>날짜</th>
								<th>유저</th>
								<th>게임명</th>
								<th>배팅비</th>
								<th>적립포인트</th>
							</tr>
						</thead>
						<tbody id="body" class="text-center"></tbody>
					</table>
				</div>
				<div class="modal-body m-1">
					<div class="row">
						<div class="col">
							<nav aria-label="Page navigation">
								<ul class="pagination justify-content-center select-none"></ul>
							</nav>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
				</div>
			</div>
		</div>
	</div>
	<!--	포인트 상세내역 : 종료						-->



<script>

$(function ()
{
	let u, t;

	$('#detail').on('show.bs.modal', function (e)
	{
		u = $(e.relatedTarget).data('user');
		t = $(e.relatedTarget).data('date');
		$(e.currentTarget).find('#nickname').text($(e.relatedTarget).data('nickname'));


		$(this).trigger('loaded', {p:1});
	});


	$('#detail').on('loaded', function (e, r)
	{
		let p = r.p;
		let em = $(this).find('#body');
		let pm = $(this).find('.pagination');

		$.get(['/relations', 'pointdetail', u, t, p, 20].join('/'), function (d)
		{
			if (d.msg)
				alert(d.msg);

			if (d.result != 0)
				return;


			let rs		= [];
			let ibet	= 0;
			let ipoint	= 0;

			for (var f of d.data)
			{
				let tr = $('<tr>');

				tr.append($('<td>').text(f.idx));
				tr.append($('<td>').text(f.date));
				tr.append($('<td>').html('<a href="/relations/memberdetail/'+f.user_seq+'" target="_blank" class="text-info">'+ f.nickname+'( '+f.user_seq+' )</a>'));
				tr.append($('<td>').text(f.codename));
				tr.append($('<td>').addClass('text-right pr-3').text(parseFloat(f.total_spend).addComma(2)));
				tr.append($('<td>').addClass('text-right pr-3').text(parseFloat(f.point).addComma(2)));

				rs.push(tr);

				ibet += parseFloat(f.total_spend);
				ipoint += parseFloat(f.point);
			}


			if (ibet > 0 || ipoint > 0)
				rs.push($('<tr class="bg-warning">').append($('<td colspan="4">').text('-'), $('<td class="text-right pr-3">').text(parseFloat(ibet).addComma(2)), $('<td class="text-right pr-3">').text(parseFloat(ipoint).addComma(2))));


			if (rs.length > 0)
				em.html('').append(rs);
			else
				em.html('<tr><td colspan="999">데이터를 찾을 수 없습니다.</td></tr>');


			let ps = [];
			ps.push('<li class="page-item '+(d.page == 1 ? ' disabled' : '')+'"><a href="1" class="page-link">처음</a></li>');
			ps.push('<li class="page-item '+ (d.end_page / 10 <= 1 ? 'disabled' : '') +'"><a href="'+ d.qs + (d.start_page - 1) +'" class="page-link" data-click="188">이전</a></li>');

			for (var i = d.start_page; i <= d.end_page; i++)
			{
				if (i == d.page)
					ps.push('<li class="page-item active"><span class="page-link">'+i+'</span></li>');
				else
					ps.push('<li class="page-item"><a class="page-link" href="'+i+'">'+i+'</a></li>');
			}

			ps.push('<li class="page-item '+ ((d.end_page / 10) == (d.total_page / 10) ? 'disabled' : '') +'"><a href="'+ d.qs + (d.end_page+1 > d.total_page ? d.total_page : d.end_page + 1) +'" class="page-link" data-click="190">다음</a></li>');
			ps.push('<li class="page-item '+(d.page == d.total_page ? ' disabled' : '')+'"><a href="'+d.total_page+'" class="page-link">끝</a></li>');

			pm.html('').append(ps);
		});
	});

	$(document).on('click', '#detail .page-item > a[href]', function ()
	{
		$(this).trigger('loaded', {p:$(this).attr('href')});
		return false;
	});

});

</script>
@endsection
