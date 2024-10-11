@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotusers.search') }}">슬롯 유저정보</a></li>
				<li class="breadcrumb-item">유저 검색</li>
			</ol>

			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12">
								<div class="pull-left">
									<h2><b>유저 검색</b></h2>
								</div>
							</div>
							<div class="col-lg-12">
								<form name="frm" method="get" action="{{ route('slotusers.search') }}">
								<div class="input-group">
									<div class="input-group-prepend">
										<select name="type" class="form-control">
											<option value="userseq" {{ $type == 'userseq' ? 'SELECTED' : '' }}>유저번호</option>
											<option value="id" {{ $type == 'id' ? 'SELECTED' : '' }}>유저ID</option>
											<option value="nickname" {{ $type == 'nickname' ? 'SELECTED' : '' }}>닉네임</option>
										</select>
									</div>
									<input type="text" name="keyword" value="{{ $keyword }}" class="form-control" />

									<div class="input-group-append">
										<button type="submit" class="btn btn-secondary">검색</button>
									</div>
								</div>
								</form>
							</div>
						</div>

						<div class="row">
							<table class="table table-sm table-bordered table-striped">
								<thead class="thead-dark text-center">
									<tr>
										<th rowspan="2" width="65">U SEQ</th>
										<th rowspan="2">유저ID</th>
										<th rowspan="2">이름</th>
										<th colspan="2">닉네임</th>
										<th rowspan="2" width="65">레벨</th>
										<th rowspan="2">가입처</th>
										<th colspan="3">제화</th>
										<th rowspan="2" width="150">최근 슬롯 접속일</th>
										<th rowspan="2" width="90">-</th>
									</tr>
									<tr>
										<th width="100">SITE</th>
										<th width="100">게임</th>

										<th width="120">보석</th>
										<th width="120">칩</th>
										<th width="120">골드</th>
									</tr>
								</thead>
								<tbody>
@if (count($users) > 0)

	@foreach ($users AS $key => $row)
									<tr class="text-center">
										<td>{{ $row->user_seq }}</td>
										<td>{{ $row->userid }}</td>
										<td>{{ $row->name }}</td>
										<td>{{ $row->nickname }}</td>
										<td>{{ $row->game_nickname }}</td>
										<td class="text-right" data-type="level">{{ empty($row->level) ? 1 : $row->level }}</td>
										<td>{{ $row->join_site }}</td>
										<td class="text-right">{{ number_format($row->gem) }}</td>
										<td class="text-right">{{ number_format($row->chip) }}</td>
										<td class="text-right">{{ number_format($row->gold) }}</td>
										<td>{{ $row->game_logined }}</td>
										<td>
											<button type="detail" class="btn btn-sm btn-info" data-id="{{ $row->user_seq }}">상세보기</button>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>

$(function ()
{
	$('button[type="detail"]').on('click', function ()
	{
		document.location.href = "{{ route('slotusers.detail') }}?id=" + $(this).data('id');
	});
});

</script>

@endsection
