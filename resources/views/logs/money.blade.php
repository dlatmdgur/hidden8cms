@extends('layouts.mainlayout')

@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('logs.money') }}">운영로그</a></li>
				<li class="breadcrumb-item">칩 지급/회수 로그</li>
			</ol>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel">
						<div class="row x_title">
							<div class="col-lg-12 margin-tb">
								<div class="pull-left">
									<h2>칩 지급/회수 로그</h2>
								</div>
							</div>
						</div>


						@if ($message = Session::get('success'))
							<div class="alert alert-success">
								<p>{{ $message }}</p>
							</div>
						@endif

						<div class="x_panel">
							<div class="x_title">
								<h2>칩 지급/회수 로그<small>(일별)</small></h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table class="table-bordered info-table">
									<tr>
										<th width="15%">처리구분</th>
										<td><label class="col-form-label col-md-5 col-sm-5 label-align">
												<select class="form-control" id="log_type">
													<option value="all">전체</option>
													<option value="give">지급</option>
													<option value="revoke">회수</option>
												</select>
											</label></td>
										<th width="15%">항목선택</th>
										<td><label class="col-form-label col-md-5 col-sm-5 label-align">
												<select class="form-control" id="log_target">
													<option value="all">전체</option>
													<option value="chip">칩</option>
													<option value="gold">골드</option>
												</select>
											</label></td>
									</tr>
									<tr>
										<th>날짜 선택</th>
										<td colspan="3">
											<label class="col-form-label col-md-3 col-sm-3 label-align">
												<div class="input-group date" id="datepicker">
													<input type="text" class="form-control" id="search_start_date1" />
													<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
												</div>
											</label>
											<label class="col-form-label col-md-3 col-sm-3 label-align">
												<div class="input-group date" id="datepicker2">
													<input type="text" class="form-control" id="search_end_date1" />
													<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
												</div>
											</label>
											<label class="col-form-label col-md-1 col-sm-5 label-align">
												<button type="button" id="btn-search-log" class="btn btn-secondary">검색</button>
											</label>
											<label class="col-form-label col-md-5 col-sm-5 label-align">
												<button type="button" class="btn btn-primary period-selector" period="1" target="1">1일</button> &nbsp;
												<button type="button" class="btn btn-primary period-selector" period="3" target="1">3일</button> &nbsp;
												<button type="button" class="btn btn-primary period-selector" period="7" target="1">7일</button>
												<button type="button" class="btn btn-success period-selector" period="30" target="1">1개월</button> &nbsp;
												<button type="button" class="btn btn-success period-selector" period="90" target="1">3개월</button> &nbsp;
												<button type="button" class="btn btn-success period-selector" period="180" target="1">6개월</button>
												<button type="button" class="btn btn-warning period-selector" period="365" target="1">1년</button>
											</label>
										</td>
									</tr>
								</table>

								<table class="table table-bordered info-table top-margin"  id="log_table">
									<thead>
									<tr>
										<th>No</th>
										<th>날짜</th>
										<th>처리</th>
										<th>항목</th>
										<th>이벤트</th>
										<th>장애보상</th>
										<th>클레임</th>
										<th>오처리</th>
										<th>기타</th>
										<th>테스트</th>
										<th>총합</th>
										<th>-</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

						</div>


						<div class="x_panel">
							<div class="x_title">
								<h2>칩 지급/회수 로그<small>(개별)</small></h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table class="table-bordered info-table">
									<tr>
										<th>대상자</th>
										<td colspan="3">
											<label class="col-form-label col-md-2 col-sm-2 label-align">
												<select class="form-control" id="user_search_type">
													<option value="email">Email</option>
													<option value="userSeq">회원번호</option>
													<option value="nickname">닉네임</option>
												</select>
											</label>
											<label class="col-form-label col-md-2 col-sm-2 label-align ml-2" id="login_type_label">
												<select class="form-control" name="login_type">
													<option value="1">구글</option>
													<option value="3">플랫폼</option>
													<option value="2">유니티</option>
													<option value="0">게스트</option>
												</select>
											</label>
											<label class="col-form-label col-md-4 col-sm-4 label-align">
												<input type="text" class="form-control" id="user_search_keyword" />
												<input type="hidden" id="user_seq" />
												<input type="hidden" id="nickaname" />
											</label>
										</td>
									</tr>
									<tr>
										<th width="15%">처리구분</th>
										<td><label class="col-form-label col-md-5 col-sm-5 label-align">
												<select class="form-control" id="user_log_type">
													<option value="all">전체</option>
													<option value="give">지급</option>
													<option value="revoke">회수</option>
												</select>
											</label></td>
										<th width="15%">항목선택</th>
										<td><label class="col-form-label col-md-5 col-sm-5 label-align">
												<select class="form-control" id="user_log_target">
													<option value="all">전체</option>
													<option value="chip">칩</option>
													<option value="gold">골드</option>
												</select>
											</label></td>
									</tr>
									<tr>
										<th>날짜 선택</th>
										<td colspan="3">
											<label class="col-form-label col-md-3 col-sm-3 label-align">
												<div class="input-group date" id="datepicker3">
													<input type="text" class="form-control" id="search_start_date2" />
													<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
												</div>
											</label>
											<label class="col-form-label col-md-3 col-sm-3 label-align">
												<div class="input-group date" id="datepicker4">
													<input type="text" class="form-control" id="search_end_date2" />
													<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
												</div>
											</label>
											<label class="col-form-label col-md-1 col-sm-5 label-align">
												<button type="button" id="btn-search-user-log" class="btn btn-secondary">검색</button>
											</label>
											<label class="col-form-label col-md-5 col-sm-5 label-align">
												<button type="button" class="btn btn-primary period-selector" period="1" target="2">1일</button> &nbsp;
												<button type="button" class="btn btn-primary period-selector" period="3" target="2">3일</button> &nbsp;
												<button type="button" class="btn btn-primary period-selector" period="7" target="2">7일</button>
												<button type="button" class="btn btn-success period-selector" period="30" target="2">1개월</button> &nbsp;
												<button type="button" class="btn btn-success period-selector" period="90" target="2">3개월</button> &nbsp;
												<button type="button" class="btn btn-success period-selector" period="180" target="2">6개월</button>
												<button type="button" class="btn btn-warning period-selector" period="365" target="2">1년</button>
											</label>
										</td>
									</tr>
								</table>

								<table class="table table-bordered info-table top-margin"  id="log_table2">
									<thead>
									<tr>
										<th>No</th>
										<th>날짜</th>
										<th>처리항목</th>
										<th>처리금액</th>
										<th>회원정보</th>
										<th>처리구분</th>
										<th>처리사유1</th>
										<th>처리사유2</th>
										<th>처리자</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 로그 리스트: 시작 -->
	<!--	포인트 상세내역 : 시작						-->
	<div id="daily-detail" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md modal-xl">
			<div class="modal-content" style="overflow-y:auto; margin-top:50px">
				<!-- <form name="frmApply" method="post" onsubmit="return false;" method-transfer="async" class="m-0"> -->
					<input type="hidden" name="user_seq" value="" />

					<div class="modal-header"><h5 class="modal-title"><b>칩 지급/회수 로그 상세 : </b><strong class="date-space"></strong></h5></div>

					<div class="modal-body" style="">

					</div>

					<div class="modal-footer">
						<button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
					</div>
				<!-- </form> -->
			</div>
		</div>
	</div>

	<!-- Switchery -->
	<link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
	<!-- bootstrap-daterangepicker -->
	<link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<!-- bootstrap-datetimepicker -->
	<link href="/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
	<style>
		.dataTables_length { display: none; }
		.btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
		.btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
		.dataTables_filter > label { display: none; }
		.paging_full_numbers { width: auto; }
		.switchery { width:32px;height:20px }
		.switchery>small { width:20px;height:20px }

		.info-table { margin-bottom: 20px; }
		.info-table th { color: #212529; text-align: center; vertical-align: middle; }
		.info-table td { text-align: center; vertical-align: middle; }
		.info-table td.number { text-align: right; }
		.top-margin { margin-top: 80px; }
	</style>

	<script>
		const actionNames = $.parseJSON('{!! json_encode(Helper::adminActions()) !!}');
		const targetNames = $.parseJSON('{!! json_encode(Helper::adminTargets()) !!}');


		$('#daily-detail').on('show.bs.modal', function(e){

			let emb = $(e.relatedTarget);
			let emf = $(e.currentTarget);

			emf.find('.modal-header .date-space').text(emb.data('date'));

			emf.find('.modal-body').empty();

			$.get({
				url: "/log/moneyLogs/detail?"+'date='+emb.data('date')+'&type='+emb.data('type')+'&action_type='+emb.data('action-type'),
				dataType: 'json',
				success: function (d)
				{
					emf.find('.modal-body').append(d.html);
				}
			});

		});
		// moneyLogs
		$(document).ready(function () {
			let log_table = $('#log_table').DataTable({
				aaSorting: [],
				bSort: false,
				pageLength: 10,
				pagingType: 'full_numbers',
				language: {
					"emptyTable": "데이터가 없습니다."
				},
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						title: function() {
							return '운영로그_머니지급회수로그(일별)_' + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
						}
					}
				],
			});

			let log_table2 = $('#log_table2').DataTable({
				aaSorting: [],
				bSort: false,
				pageLength: 10,
				pagingType: 'full_numbers',
				language: {
					"emptyTable": "데이터가 없습니다."
				},
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						title: function() {
							return '운영로그_칩지급회수로그(개별)_' + $("#search_start_date2").val().replace(/-/gi, '') +'-'+$("#search_end_date2").val().replace(/-/gi, '');
						}
					}
				],
			});

			$('#datepicker').datetimepicker({
				format: 'YYYY-MM-DD'
			});
			$('#datepicker2').datetimepicker({
				format: 'YYYY-MM-DD'
			});
			$('#datepicker3').datetimepicker({
				format: 'YYYY-MM-DD'
			});
			$('#datepicker4').datetimepicker({
				format: 'YYYY-MM-DD'
			});

			// date search make period
			$('.period-selector').off('click').on('click', function() {
				let period = $(this).attr('period');
				let target = $(this).attr('target');
				fillSearchDate(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
			});

			// daily log
			$('#btn-search-log').off('click').on('click', function() {
				$('#search-errors').html('');

				$('#log_table tbody').empty();

				let searchActionType = $('#log_type option:selected').val();
				let searchTarget = $('#log_target option:selected').val();
				let startDate = $('#search_start_date1').val();
				let endDate = $('#search_end_date1').val();

				if (searchActionType.length === 0) {
					alert('처리구분을 선택하세요.');
					return false;
				}

				if (searchTarget.length === 0) {
					alert('항목을 선택하세요.');
					return false;
				}

				if (startDate.length === 0 || endDate.length === 0) {
					alert('검색 기간을 선택하세요');
					return false;
				}

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					type: 'POST',
					url: '/log/moneyLogs',
					data: {
						type: 'daily',
						keyword: 'daily',
						actionType: searchActionType,
						target: searchTarget,
						startDate: startDate,
						endDate: endDate,
						from: 'money'
					},
					dataType: 'json',
					success: function(data) {
						// console.log(data);
						if (data.error === true) {
							console.log('checked error');
							return;
						}

						$("#search-error-bag").hide();

						let adminLogs = data.adminLogs;
						log_table.clear();
						if (adminLogs.length > 0) {
							let no = 0;
							$(adminLogs).each(function(index, log) {

								// console.log('log', log);
								let tr = $('<tr>' +
									'<td>' + (++no) + '</td>' +
									'<td>' + log.logDate + '</td>' +
									'<td>' + actionNames[log.actionType] + '</td>' +
									'<td>' + targetNames[log.target] + '</td>' +
									'<td>' + numberToKorean(log.event) + '</td>' +
									'<td>' + numberToKorean(log.maintenance) + '</td>' +
									'<td>' + numberToKorean(log.claim) + '</td>' +
									'<td>' + numberToKorean(log.correction) + '</td>' +
									'<td>' + numberToKorean(log.admin) + '</td>' +
									'<td>' + numberToKorean(log.test) + '</td>' +
									'<td>' + numberToKorean(log.total) + '</td>' +
									'<td>' +'<button type="button" data-date="'+log.logDate+'" data-type="'+log.target+'" data-action-type="'+log.actionType+'" data-toggle="modal" data-target="#daily-detail" class="btn btn-secondary" >Detail</button>' +'</td>' +
									'</tr>');
								log_table.row.add(tr);
							});
						}
						log_table.draw();
					},
					error: function(data) {
						if (data.status === 419) {
							alert('세션이 만료되었습니다.');
							location.href = "/login";
						}
						let errors = $.parseJSON(data.responseText);
						// console.log(errors);
						$('#search-errors').html('');
						$.each(errors.messages, function(key, value) {
							$('#search-errors').append('<li>' + value + '</li>');
						});
						$("#search-error-bag").show();
					}
				});
			});


			// user log
			$('#btn-search-user-log').off('click').on('click', function() {
				$('#search-errors').html('');

				let type = $('#user_search_type option:selected').val();
				let keyword = $('#user_search_keyword').val();
				let searchActionType = $('#user_log_type option:selected').val();
				let searchTarget = $('#user_log_target option:selected').val();
				let startDate = $('#search_start_date2').val();
				let endDate = $('#search_end_date2').val();

				if (keyword.length === 0) {
					alert('키워드를 입력하세요.');
					return false;
				}

				if (searchActionType.length === 0) {
					alert('처리구분을 선택하세요.');
					return false;
				}

				if (searchTarget.length === 0) {
					alert('항목을 선택하세요.');
					return false;
				}

				if (startDate.length === 0 || endDate.length === 0) {
					alert('검색 기간을 선택하세요');
					return false;
				}

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					type: 'POST',
					url: '/log/moneyLogs',
					data: {
						type: type,
						keyword: keyword,
						platform: $('select[name=login_type]').val(),
						actionType: searchActionType,
						target: searchTarget,
						startDate: startDate,
						endDate: endDate,
						from: 'money'
					},
					dataType: 'json',
					success: function(data) {
						// console.log(data);
						if (data.error === true) {
							console.log('checked error');
							return;
						}

						$("#search-error-bag").hide();

						let adminLogs = data.adminLogs;
						log_table2.clear();
						$('#log_table2 tbody').empty();
						if (adminLogs.length > 0) {
							let no = 0;
							$(adminLogs).each(function(index, log) {
								console.log('log', log);

								let tr = $('<tr>' +
									'<td>' + (++no) + '</td>' +
									'<td>' + log.logDate + '</td>' +
									'<td>' + targetNames[log.target] + '</td>' +
									'<td>' + numberToKorean(log.changeAmount) + '</td>' +
									'<td>' + log.nickname + '</td>' +
									'<td>' + actionNames[log.actionType] + '</td>' +
									'<td>' + log.reason + '</td>' +
									'<td>' + log.extra + '</td>' +
									'<td>' + log.admin_name + '</td>' +
									'</tr>');
								log_table2.row.add(tr);
							});
						}
						log_table2.draw();
					},
					error: function(data) {
						if (data.status === 419) {
							alert('세션이 만료되었습니다.');
							location.href = "/login";
						}
						let errors = $.parseJSON(data.responseText);
						// console.log(errors);
						$('#search-errors').html('');
						$.each(errors.messages, function(key, value) {
							$('#search-errors').append('<li>' + value + '</li>');
						});
						$("#search-error-bag").show();
					}
				});
			});

			$('#user_search_type').change(function() {
			let searchType = $(this).val();
			if (searchType === "email") {
				$('#login_type_label').show();
			} else {
				$('#login_type_label').hide();
			}
			});
		});

	</script>

	<!-- Datatables -->
	<script src="/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script src="/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
	<script src="/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
	<script src="/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
	<script src="/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
	<script src="/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
	<script src="/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
	<script src="/vendors/jszip/dist/jszip.min.js"></script>
	<script src="/vendors/pdfmake/build/pdfmake.min.js"></script>
	<script src="/vendors/pdfmake/build/vfs_fonts.js"></script>

	<!-- Switchery -->
	<script src="/vendors/switchery/dist/switchery.min.js"></script>
	<!-- bootstrap-daterangepicker -->
	<script src="/vendors/moment/min/moment.min.js"></script>
	<script src="/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
	<!-- bootstrap-datetimepicker -->
	<script src="/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
@endsection
