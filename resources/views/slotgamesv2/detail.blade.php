@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('slotgames.slots') }}">슬롯 설정</a></li>
				<li class="breadcrumb-item">슬롯 데이터 설정</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb p-0">
								<h2><b>[ {{ $slot_id ? strtoupper($slot_id) : '' }} ] BET CUSTOMIZE</b></h2>
							</div>
						</div>


@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
@endif


						<div class="row">
							<div class="col p-0">
								<table class="table table-sm table-bordered table-striped m-0">
									<thead class="thead-dark text-center">
										<tr>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



<script src="/js/apis.js"></script>
<script>

$(function ()
{
	//
	// 배팅정보 데이터 정의.
	//
	let data = {!! json_encode($data) !!};

	//
	// 목록에 출력할 컬럼.
	//
	let cols = ['slot_id', 'level', 'bet', 'bet_rate'];


	//
	// 변경/삭제 버튼 눌렀을때 이벤트.
	//
	$('#modify, #drop').on('show.bs.modal', function (e)
	{
		var t = $(e.relatedTarget).data('i');
		var d = data[t];
		var f = $(e.currentTarget).find('form');

		var s = t == undefined;


		for (var i in cols)
		{
			switch (cols[i])
			{
				case 'slot_id':
					break;

				case 'bet_rate':
					f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]].join(','));
					break;

				default:
					f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]]);
					break;
			}
		}
	});

	//
	// 추가/변경 콜백 처리.
	//
	$('form[name="frmSet"]').on('callback', function (e, d)
	{
		if (d.msg != undefined && d.msg != '')
			alert(d.msg);

		if (d.result != 1)
			return false;

		document.location.reload();
	});


});

</script>

@endsection
