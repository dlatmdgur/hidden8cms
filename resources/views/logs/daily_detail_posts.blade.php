<style>
	#detail_tb tr {
		white-space: nowrap;
		word-break: break-all;
	}

	#daily-detail .dt-buttons {
		margin-top:70px;
	}
</style>
<div class="card" style="overflow-x:auto;">
	<table class="table table-bordered info-table top-margin"  id="detail_tb">
		<thead>
			<tr>
				<th>No</th>
				<th>날짜</th>
				<th>닉네임</th>
				<th>회원번호</th>
				<th>처리구분</th>
				<th>처리항목</th>
				<th>처리금액</th>
				<th>처리사유1</th>
				<th>처리사유2</th>
				<th>처리자</th>
			</tr>
		</thead>
			<tbody>
			</tbody>
</table>
</div>


<script>
// moneyLogs
$(document).ready(function () {

	let detailTb = $('#detail_tb').DataTable({
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
					return '운영로그_가방지급회수(일별상세)_' + $("#search_start_date1").val().replace(/-/gi, '') +'-'+$("#search_end_date1").val().replace(/-/gi, '');
				},
				exportOptions: {
							format: {
								body: function(data, row, column, node) {
									return ([4].indexOf(column) > -1 ) ? data.replace(/,/g, '') : data;
								}
							}
						}
					}
		],

	});

	const data = @json($rs);
	const dataSize = data.length;

	detailTb.clear();

	$('#detail_tb tbody').empty();

	for (let i in data) {

		let tr = $(`<tr>
						<td> ${(parseInt(i)+1)} </td>
					 	<td> ${data[i].logDate} </td>
						<td> ${data[i].nickname} </td>
						<td> ${data[i].user_seq} </td>
						<td> ${actionNames[data[i].actionType]} </td>
						<td> ${presentNames[data[i].presentType]} </td>
						<td> ${numberFormat(data[i].changeAmount)} </td>
						<td> ${data[i].reason} </td>
						<td> ${data[i].extra} </td>
						<td> ${data[i].admin_name} </td>
				</tr>`);


		detailTb.row.add(tr);

	}



	detailTb.draw();



});

</script>