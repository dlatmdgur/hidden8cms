<div class="card" style="overflow-y:auto">
	<table class="table table-sm table-striped table-bordered table-hover mb-0">
		<thead class="thead-dark text-center align-top">
			<tr>
				<th>NO</th>
				<th>Confirm Date</th>
				<th>Event Time</th>
				<th>Event Delay</th>
				<th>EVENT RTP</th>
				<th>STATUS</th>
				<th>CANCEL</th>
			</tr>
		</thead>

		<tbody id="list" class="text-center text-nowrap align-middle">
			@forelse($rs as $row)
			<tr class="text-center">
				<td>{{ $loop->count - $loop->index }}</td>
				<td>{{ $row->created }}</td>
				<td>{{ $row->start_time }}</td>
				<td class="pl-3 pr-3 text-center">{{ $row->delay_min }}&nbsp;min</td>
				<td class="pl-3 pr-3 text-center">{{ $row->reward_rate }}%</td>
				<td class="pl-3 pr-3 text-center fw-bold text-{{ ($row->status  === 'Completed') ? 'success' : (($row->status  === 'Pending') ? 'dark' : 'danger')}}">{{ $row->status }}</td>
				<td>
					@if ($row->status === 'Pending')
						<button type="button" data-method="append" class="btn btn-sm btn-danger m-0" data-cancel="{{ $row->idx }}" data-start="{{ $row->start_time }}">CANCEL</button>
					@else
						-
					@endif
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="999" class="text-center p-3"><b><i>{{ '내역이 없습니다.' }}</i></b></td>
			</tr>
			@endforelse
		</tbody>
	</table>
	{{ $rs->onEachSide(10)->links('layouts.partials.page', ['pageinfo' => $rs, 'offset' => $offset]) }}
	</div>

<script>
$(function(){

	let md = $('#event-log');

	$('[data-cancel]').on('click', function(e){

		e.preventDefault();

		let startTime = new Date($(this).data('start'));
		let now = new Date();

		if (startTime.getTime() <= now.getTime())
		{
			alert('이미 완료된 잭팟입니다.');
			return false;
		}


		let cancel = confirm('정말 취소하시겠습니까?');


		if (cancel) {

			$.ajaxSetup({
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			});
			$.ajax({
				type: 'DELETE',
				data: {start_time: $(this).data('start')},
				url: ['/event/jackpot', md.find('input[name="user_seq"]').val(), $(this).data('cancel')].join('/'),
				dataType: 'json',
				success: function(d) {

					alert(d.message);

					if ( d.result != 0)
						return false;

					window.location.reload();
				},

			});

			return true;
		}

		return false;

	});

	md.find('.pagination .page-link').on('click', function(e)
	{

		e.preventDefault();

		if (typeof($(this).attr('href')) === 'undefined') {

			return false;
		}

		$.get({
				url:$(this).attr('href').replace(/^https?:\/\/[^\/]+/, ''),
				dataType: 'json',
				success: function (d)
				{
					md.find('.modal-body').empty();
					md.find('.modal-body').append(d.html);
				}
		});
	});

});
</script>
