@extends('layouts.popuplayout')

@section('content')
<style>

.table .caption-top {
	caption-side: top;
}

.table .badge {
	font-size: 12px;
	font-weight: bold;
}

</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-12 pt-1 pb-1">

			<table class="table table-sm table-bordered table-striped">
				<caption class="caption-top"><h3><b>EVENT PAYMENT LIST</b></h3></caption>
				<thead class="thead-dark text-center">
					<tr>
						<th>NO</th>
						<th>CONFIRM DATE</th>
						<th>JACKPOT TIER</th>
						<th>STATUS</th>
						<th>JACKPOT PRICE</th>
						<th>JACKPOT WIN DATE</th>
						<th>DETAIL</th>
					</tr>
				</thead>
				<tbody>
@if (!empty($logs) && count($logs) > 0)
	@foreach ($logs AS $num => $row)
					<tr class="text-center">
						<td>{{ $row->idx }}</td>
						<td>{{ $row->created }}</td>
						<td>{{ $row->tier }}</td>
						<td>
		@if ($row->status == 1)
							<span class="badge badge-warning">미사용</span>
		@else
							<span class="badge badge-success">사용</span>
		@endif
						</td>
						<td class="text-right pl-3 pr-3">{{ number_format($row->prize) }}</td>
						<td>{{ $row->updated }}</td>
						<td>-</td>
					</tr>
	@endforeach
@else
					<tr>
						<td colspan="999" align="center">DATA NOT FOUND.</td>
					</tr>
@endif
				</tbody>
			</table>
		</div>
		<div class="col-12 ">
			{{ $logs->onEachSide(10)->links('layouts.partials.page', ['pageinfo' => $logs, 'offset' => $offset]) }}
		</div>
	</div>
</div>

@endsection
