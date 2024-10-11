<div class="card" style="overflow-y:auto;">
			<table class="table table-sm table-bordered table-striped">
				<thead class="thead-dark text-center">
					<tr>
						<th>NO</th>
						<th>{{ $into_force ? 'CONFIRM ' : '' }}DATE</th>
						<th>JACKPOT TIER</th>
						@if ($into_force)
						<th>STATUS</th>
						@else
						<th>MEMBER ID</th>
						<th>USER ID</th>
						<th>RTP</th>
						@endif
						<th>JACKPOT PRICE</th>
						@if ($into_force)
						<th>JACKPOT WIN DATE</th>
						@endif
					</tr>
				</thead>
				<tbody>
	@forelse ($logs AS $num => $row)
					<tr class="text-center">
						<td>{{ $loop->iteration}}</td>
						<td>{{ $row->confirm_date }}</td>
						<td>{{ $row->tier }}</td>
						@if ($into_force)
						<th class="text-{{ ($row->status) ? 'dark' : 'success' }}" >{{ ($row->status) ? 'Pending' : 'Completed' }}</th>
						@else
						<td>{{ $row->id }}</td>
						<td>{{ $row->user_seq }}</td>
						<td>{{ number_format($row->rtp, 2) }} %</td>
						@endif
						<td class="text-right pl-3 pr-3">{{ number_format($row->prize) }}</td>
						@if ($into_force)
						<th>{{ ($row->confirm_date  === $row->win_date) ? '' : $row->win_date}}</th>
						@endif
						{{-- <td>
							<button type="button" class="btn btn-secondary" data-game-history>VIEW DETAIL</button>
						</td> --}}
					</tr>
	@empty
					<tr>
						<td colspan="999" align="center">DATA NOT FOUND.</td>
					</tr>
	@endforelse
				</tbody>
			</table>
		</div>
		<div class="col-12 ">
			{{ $logs->onEachSide(10)->links('layouts.partials.page', ['pageinfo' => $logs, 'offset' => $offset]) }}
		</div>
	</div>
</div>

<script>
	$(function(){

		$(document).on('click','[data-game-history]', function(e){
			e.preventDefault();
		});

		$('.page-link').on('click', function(e) {
			e.preventDefault();
			$('.modal-content').find('form > input[name="page"]').val($(this).text());

			fm.submit();
		});



	});
</script>