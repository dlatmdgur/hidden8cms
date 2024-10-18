@extends('layouts.mainlayout')

@section('content')
	<script src="/js/apis.js"></script>


	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('relations.search') }}">추천인 정보</a></li>
				<li class="breadcrumb-item">실시간 플레이 유저</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<h2><b>실시간 플레이 유저</b> <span class="pl-3">{{ date('Y-m-d H:i:s') }}</span></h2>
							</div>
						</div>


						<!--		컨텐츠 영역 : 시작				-->
						<div class="row">
							<div class="col p-0" style="overflow-y: hidden; overflow-x: auto;">
								<table class="table table-bordered table-striped">
									<thead class="thead-dark text-center">
										<tr>
											<th class="col-1">텍사스홀덤</th>
											<th class="col-1">홀덤바둑이</th>
											<th class="col-1">블랙잭</th>
											<th class="col-1">바카라</th>
											<th class="col-1">슬롯</th>
										</tr>
									</thead>

									<tbody>
										<tr>
											<td class="text-right pr-3">{{ number_format($ccu->texasholdem)    }}</td>
											<td class="text-right pr-3">{{ number_format($ccu->badugi)   }}</td>
											<td class="text-right pr-3">{{ number_format($ccu->blackjack) }}</td>
											<td class="text-right pr-3">{{ number_format($ccu->baccarat)  }}</td>
											<td class="text-right pr-3">{{ number_format($ccu->slot)      }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!--		컨텐츠 영역 : 종료				-->

					</div>
				</div>
			</div>
		</div>
	</div>


<script>

$(function ()
{


});

</script>
@endsection
