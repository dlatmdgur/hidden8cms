@extends('layouts.mainlayout')

@section('content')
	<script src="/js/apis.js"></script>

<style>

</style>

	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">추천인 상세정보</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<!--		기본 영역 : 시작				-->
						<div class="row mb-3">
							<div class="col-12">
								<div class="row x_title">
									<div class="col-lg-12 margin-tb">
										<h2><b>유저 정보</b></h2>
									</div>
								</div>
							</div>

							<div class="col-12 pt-0 pb-0">
								<table class="table table-bordered table-striped">
									<colgroup>
										<col class="col-2">
										<col class="col-9">
										<col class="col-1">
									</colgroup>
									<tbody class="align-middle">
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>유저 번호</b></td>
											<td class=" pl-3 pr-5">{{ $member->user_seq }}</td>
											<td class="text-center">-</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>유저 ID</b></td>
											<td class=" pl-3 pr-5">{{ $member->userid }}</td>
											<td class="text-center">-</td>
										</tr>
									</tbody>

									<tbody class="align-middle">
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>유저 닉네임</b></td>
											<td class=" pl-3 pr-5">{{ $member->nickname }}</td>
											<td class="text-center">
												-
<!-- 												<button type="button" class="btn btn-sm btn-info" data-key="nickname" data-user="{{ $member->user_seq }}" data-value="{{ $member->nickname }}" data-toggle="modal" data-target="#modify">변경</button> -->
											</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>성명 / 성별</b></td>
											<td class=" pl-3 pr-5">{{ $member->name }} / {{ $member->sex }}</td>
											<td class="text-center">
												-
<!-- 												<button type="button" class="btn btn-sm btn-info" data-key="name" data-user="{{ $member->user_seq }}" data-value="{{ $member->name }}/{{ $member->sex }}" data-toggle="modal" data-target="#modify">변경</button> -->
											</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>실명인증 여부</b></td>
											<td class=" pl-3 pr-5"><span style="font-size: 0.8rem;" class="badge badge-{{ !empty($member->CI) ? 'success' : 'danger' }}">{{ empty($member->CI) ? '미인증' : '인증완료' }}</span></td>
											<td class="text-center">
										@if(auth()->user()->can('outer0'))
												<button type="button" class="btn btn-sm btn-info" data-key="cert" data-user="{{ $member->user_seq }}" data-value="{{ empty($member->CI) ? 'N' : 'Y' }}" data-toggle="modal" data-target="#modify">정보 변경</button>
										@else
												-
										@endif
											</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>연락처 / 인증여부</b></td>
											<td class=" pl-3 pr-5">{{ $member->phone }}  /  <span style="font-size: 0.8rem;" class="badge badge-{{ $member->phone_auth == 'Y' ? 'success' : 'danger' }}">{{ $member->phone_auth == 'Y' ? '인증완료' : '미인증' }}</span></td>
											<td class="text-center">
										@if(auth()->user()->can('outer0'))
												<button type="button" class="btn btn-sm btn-info" data-key="phone" data-user="{{ $member->user_seq }}" data-value="{{ $member->phone }}/{{ $member->phone_auth }}" data-toggle="modal" data-target="#modify">정보 변경</button>
										@else
												-
										@endif
											</td>
										</tr>
									</tbody>

									<tbody class="align-middle">
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>보유 골드</b></td>
											<td class=" pl-3 pr-5">{{ number_format($member->gold) }}</td>
											<td class="text-center">-</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>보유 포인트</b></td>
											<td class=" pl-3 pr-5">{{ number_format($member->point, 2) }}</td>
											<td class="text-center">-</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>계정 생성일</b></td>
											<td class=" pl-3 pr-5">{{ $member->created_at }}</td>
											<td class="text-center">-</td>
										</tr>
										<tr>
											<td class="bg-dark text-light pl-3 pr-5"><b>하위 유저 수</b></td>
											<td class=" pl-3 pr-5">{{ $childs }}</td>
											<td class="text-center">-</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!--		기본 영역 : 종료				-->

					</div>
				</div>
			</div>
		</div>
	</div>



	<!--	유저정보 추가/변경 : 시작						-->
	<div id="modify" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form name="frmSet" method="post" action="{{ Route('relations.memberset') }}" onsubmit="return false;" method-transfer="async" class="m-0">
					<input type="hidden" name="method" value="" />
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>유저 정보 변경</b></h5></div>

					<div class="modal-body">
						<div class="form-group mb-3">
							<label for="inp">Email address</label>
							<input type="email" class="form-control" id="inp" aria-describedby="emailHelp">
						</div>
						<div class="form-group mb-3">
							<label for="inp">Email address</label>
							<input type="email" class="form-control" id="inp" aria-describedby="emailHelp">
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" aria-label="modify">변경</button>
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--	유저정보 추가/변경 : 종료						-->




<script>

$(function ()
{

	$('#modify').on('show.bs.modal', function (e)
	{
		let em = $(this).find('.modal-body').html('');
		let k = $(e.relatedTarget).data('key');
		let v = $(e.relatedTarget).data('value');
		let u = $(e.relatedTarget).data('user');

		let f = null;

		$(this).find('input[name="method"]').val(k);
		$(this).find('input[name="user_seq"]').val(u);

		switch (k)
		{
			case 'nickname':
				em.append($('<div class="form-group mb-3">').append('<label for="inp">닉네임</label><input type="text" class="form-control id="inp" name="nickname" required />'));
				em.find('input[name="nickname"]').val(v);
				break;

			case 'name':
				em.append($('<div class="form-group mb-3">').append('<label for="inp1">이름</label><input type="text" class="form-control id="inp1" name="name" required />'));
				em.append($('<div class="form-group mb-3">').append('<label for="inp2">성별</label><select id="inp2" name="sex" class="form-control"><option value="">성별 선택</option><option value="M">남성</option><option value="F">여성</option></select>'));

				v = v.split('/');
				em.find('input[name="name"]').val(v[0]);
				em.find('select[name="sex"] > option[value="'+v[1]+'"]').prop('selected', true);
				break;

			case 'cert':
				em.append($('<div class="form-group mb-3">').append('<label for="inp">실명인증</label><select name="cert" id="inp" class="form-control" required><option value="">선택</option><option value="Y">인증</option><option value="N">미인증</option></select>'));
				break;

			case 'phone':
				em.append($('<div class="form-group mb-3">').append('<label for="inp1">연락처</label><input type="text" class="form-control id="inp1" name="phone" required />'));
				em.append($('<div class="form-group mb-3">').append('<label for="inp2">휴대폰 인증</label><select name="phone_auth" class="form-control" required><option value="">선택</option><option value="Y">인증</option><option value="N">미인증</option></select>'));
				v = v.split('/');
				em.find('input[name="phone"]').val(v[0]);
				em.find('select[name="phone_auth"] > option[value="'+v[1]+'"]').prop('selected', true);
				break;
		}
	});


	$('form[name="frmSet"]').on('callback', function (e, d)
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
