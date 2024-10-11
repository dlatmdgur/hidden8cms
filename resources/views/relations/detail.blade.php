@extends('layouts.mainlayout')
@section('content')

<style>

</style>


	<script src="/js/apis.js"></script>


	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">추천인 관리 ( 상세 )</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="x_panel">
						<!--		기본 영역 : 시작				-->
						<div class="row mb-3">
							<div class="col-12">
								<div class="row x_title">
									<div class="col-lg-12 margin-tb">
										<h2><b>기본 정보</b></h2>
									</div>
								</div>
							</div>

							<div class="col-12 pt-0 pb-0">
								<table class="table table-bordered table-striped">
									<colgroup>
										<col class="col-2">
										<col class="col">
									</colgroup>
									<tbody class="align-middle">
										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">타입</td>
											<td class="pl-3">{{ $master->depthname }}</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">닉네임 ( 아이디 )</td>
											<td class="pl-3">{{ $master->nickname.' ( '.$master->userid.' )' }}</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">상위 유저</td>
											<td data-method="dash"><span>{!! implode('</span><span>', explode('/', $parents)) !!}</span></td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">현재 보유 포인트</td>
											<td class="pl-3">{{ number_format($master->point, 2) }}</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">계정 생성 시각</td>
											<td class="pl-3">{{ $master->created }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!--		기본 영역 : 종료				-->



						<!--		운영 영역 : 시작				-->
						<div class="row mb-3">
							<div class="col-12">
								<div class="row x_title">
									<div class="col-lg-12 margin-tb">
										<h2><b>운영 정보</b></h2>
									</div>
								</div>
							</div>

							<div class="col-12 pt-0 pb-0">
								<table class="table table-bordered table-striped">
									<colgroup>
										<col class="col-2">
										<col>
										<col>
									</colgroup>
									<tbody>
										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">추천인 총 수</td>
											<td class="text-nowrap pl-3 pr-5"><b>전체</b><br />{{ array_sum($total_user) }} 명 ( 추천: {{ $total_user[0] }} 명, 일반: {{ $total_user[1] }} 명 )</td>
											<td>
												<div class="row">
@foreach ($summary AS $key => $row)

													<div class="col-12 col-md-6 col-xl-4 mb-1">
														<button type="button" class="btn btn-sm btn-outline-info w-100 text-left" data-parent="{{ $row->parent_seq }}" data-depth="{{ $row->depth }}" data-toggle="modal" data-target="#childs"><b>{{ $row->depthname }}</b><br />{{ $row->member }} 명</button>
													</div>
@endforeach

												</div>
											</td>
										</tr>

										<tr>
											<td class="bg-dark text-light pl-3 font-weight-bold">추천인<br />총 보유포인트</td>
											<td class="text-nowrap pl-3 pr-5"><b>전체</b><br />{{ number_format($total_point, 2) }} p</td>
											<td>
												<div class="row">
@foreach ($summary AS $key => $row)

													<div class="col-12 col-md-6 col-xl-4 mb-1">
														<button type="button" class="btn btn-sm btn-outline-info w-100 text-left" data-parent="{{ $row->parent_seq }}" data-depth="{{ $row->depth }}" data-toggle="modal" data-target="#childs"><b>{{ $row->depthname }}</b><br />{{ number_format($row->point, 2) }} p</button>
													</div>
@endforeach

												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!--		운영 영역 : 종료				-->



						<!--		요율 영역 : 시작				-->
@if (!empty($rates))
						<div class="row mb-3">
							<div class="col-12">
								<div class="row x_title">
									<div class="col-lg-12 margin-tb">
										<h2><b>요율 정보</b></h2>
									</div>
								</div>
							</div>

							<div class="col-12 pt-0 pb-0">
								<table class="table table-bordered table-striped">
									<thead class="thead-dark text-center">
										<tr>
											<th width="10%">항목</th>
@foreach ($depthname AS $num => $name)
											<th width="10%">{{ $name }}</th>
@endforeach
										</tr>
									</thead>
									<tbody>
@foreach ($rates AS $code => $row)
										<tr>
											<td><b>{{ $codename[$code] }}</b></td>
	@foreach ($depthname AS $num => $name)
											<td class="text-right pr-3" data-type="avg">{{ number_format(array_sum([$row[$num]->rate1, $row[$num]->rate2, $row[$num]->rate3]), 2) }}</td>
	@endforeach
										</tr>
@endforeach
									</tbody>
								</table>
							</div>
						</div>
@endif
						<!--		요율 영역 : 종료				-->



						<!--		추천인 영역 : 시작				-->
						<div class="row mb-3">
							<div class="col-12">
								<div class="row x_title">
									<div class="col-lg-12 margin-tb">
										<h2><b>추천인 목록</b></h2>
									</div>
								</div>
							</div>


							<div class="col-12">
								{!! $html !!}
							</div>
						</div>
						<!--		추천인 영역 : 종료				-->



						<!--		버튼 영역 : 시작				-->
						<hr />
						<div class="row mb-3">
							<div class="col-12">
							</div>

							<div class="col p-0">

							</div>
						</div>
						<!--		버튼 영역 : 종료				-->
					</div>
				</div>
			</div>
		</div>
	</div>



	<!--	요율 추가/변경 : 시작							-->
	<div id="rates" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.setrates') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>기본요율 등록</b></h5></div>

					<div class="modal-body" style="overflow: auto;">
						<table class="table table-bordered table-striped">
							<thead data-target="title-rate" class="thead-dark text-center"></thead>
							<tbody data-target="reg-rate"></tbody>
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



	<!--	하위유저 상세내역 : 시작						-->
	<div id="childs" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header"><h5 class="modal-title"></span><b>유저목록</b></h5></div>
				<div class="modal-body p-0" style="overflow-x: auto; overflow-y: hidden; max-height: 95%;">
					<table class="table table-sm table-bordered table-striped m-0">
						<thead class="thead-dark text-center text-nowrap">
							<tr>
								<th width="5%">번호</th>
								<th width="10%">관계</th>
								<th width="20%">추천인</th>
								<th width="20%">연락처</th>
								<th width="20%">적립포인트</th>
								<th width="15%">등록일</th>
								<th width="10%">-</th>
							</tr>
						</thead>
						<tbody class="text-center"></tbody>
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
					<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">닫기</button>
				</div>
			</div>
		</div>
	</div>
	<!--	하위유저 상세내역 : 종료						-->



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
								<tr><th>보유 골드</th><td id="gold"></td></tr>
								<tr><th>보유 포인트</th><td id="point"></td></tr>
								<tr><th>관계 제거</th><td id="drop"></td></tr>
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

$(function ()
{

	$(document).on('callback', 'form[name="frmSet"]', function (e, d)
	{
		if (d.msg != undefined
			&& d.msg != '')
			alert(d.msg);

		if (d.result != 0)
			return false;

		document.location.reload();
	});


	$('#info').on('show.bs.modal', function (e)
	{
		let f = $(e.currentTarget).find('form');

		$.get(
			'/relations/memberdetail/'+ $(e.relatedTarget).data('seq'),
			function (d)
			{
				if (d.msg)
					alert(d.msg);

				if (d.result > 0)
					f.find('button[type="cancel"]').trigger('click');

				f.find('input[name="user_seq"]').val(d.user_seq);
				f.find('#user_seq').text(d.user_seq);

				f.find('#userid').text(d.member.userid);
				f.find('#nickname').text(d.member.nickname);
				f.find('#gold').text(parseFloat(d.member.gold).addComma(0));
				f.find('#point').text(f.member == undefined ? '0.00' : parseFloat(d.member.point).addComma(2));


				f.find('#drop').html('').append($('<span class="mr-5">').text('하위 유저수 : '+ parseFloat(d.childs).addComma(0)));

				if (d.childs <= 0)
				{
					f.find('#drop').append(em = $('<button type="button" class="btn btn-sm btn-danger">').text('제거'));
					em.on('click', function ()
					{
						if (confirm('정말 삭제하시겠습니까?'))
							f.trigger('submit');
					});
				}
			});
	});


	$('#rates').on('show.bs.modal', function (e)
	{
		let f = $(e.currentTarget).find('form');
		let hm = f.find('[data-target="title-rate"]').html('');
		let em = f.find('[data-target="reg-rate"]').html('');

		$.get(
			'/relations/rates/'+ $(e.relatedTarget).data('user-seq'),
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
					tr.append($('<th>').text(n));

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



	$('#childs').on('loaded', function (e, d)
	{
		let em = $(this).find('tbody');
		let pm = $(this).find('.pagination');

		$.get(
			['/relations', 'depthdetail', d.u, d.d, d.p, d.l].join('/'),
			function (d)
			{
				if (d.msg)
					alert(d.msg);

				if (d.result != 0)
				{
					em.html('<tr><td colspan="999">검색된 데이터가 업습니다.</td></tr>');
					return true;
				}


				let trs = [];
				for (var r of d.data)
				{
					let tr = $('<tr>');

					tr.append($('<td>').text(r.idx));
					tr.append($('<td>').text(r.depthname));
					tr.append($('<td>').text(r.nickname + ' ( '+ r.platform_id +' )'));
					tr.append($('<td>').html(r.phone + ' <span style="font-size: 12px;" class="badge badge-'+ (r.phone_auth == 'Y' || r.phone_auth2 == 1 ? 'success">인증 O' : 'danger">인증 X') +'</span>'));
					tr.append($('<td class="text-right pr-3">').text(parseFloat(r.point).addComma(2)));
					tr.append($('<td>').text(r.created));
					tr.append($('<td>').append($('<button class="btn btn-sm btn-info" href="/relations/memberdetail/'+ r.user_seq +'">').text('상세정보')));

					trs.push(tr);
				}

				em.html('').append(trs);


				let ps = [];
				ps.push('<li class="page-item '+ (d.page == 1 ? ' disabled' : '')+'"><a href="'+ d.qs + '1" class="page-link">처음</a></li>');
				ps.push('<li class="page-item '+ (d.end_page / 10 <= 1 ? 'disabled' : '') +'"><a href="'+ d.qs + (d.start_page - 1) +'" class="page-link" data-click="188">이전</a></li>');

				for (var i = d.start_page; i <= d.end_page; i++)
				{
					if (i == d.page)
						ps.push('<li class="page-item active"><span class="page-link">'+i+'</span></li>');
					else
						ps.push('<li class="page-item"><a class="page-link" href="'+ d.qs + i +'">'+i+'</a></li>');
				}

				ps.push('<li class="page-item '+ ((d.end_page / 10) == (d.total_page / 10) ? 'disabled' : '') +'"><a href="'+ d.qs + (d.end_page+1 > d.total_page ? d.total_page : d.end_page + 1) +'" class="page-link" data-click="190">다음</a></li>');
				ps.push('<li class="page-item '+ (d.page == d.total_page ? ' disabled' : '') +'"><a href="'+ d.qs + d.total_page +'" class="page-link">끝</a></li>');

				pm.html('').append(ps);
			});
	});


	$('#childs').on('show.bs.modal', function (e)
	{
		let u = $(e.relatedTarget).data('parent');
		let d = $(e.relatedTarget).data('depth');

		$(this).trigger('loaded', {p:1, l:20, u:u, d:d});
	});


	$(document).on('click', 'button[href]', function ()
	{
		window.open($(this).attr('href'));
	});

	$(document).on('click', '#childs .page-item > a[href]', function ()
	{
		let e = $(this).attr('href').split(':');
		$(this).trigger('loaded', {u:e[0], d:e[1], p:e[3], l:e[2]});
		return false;
	});

});

</script>
@endsection
