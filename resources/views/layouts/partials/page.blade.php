@php ( $cur_page	= $pageinfo->currentPage() )
@php ( $offset		= empty($offset) ? 20 : $offset )
@php ( $total_page	= $pageinfo->total() > 0 ? ceil($pageinfo->total() / $offset) : 1 )
@php ( $start_page	= ((ceil($cur_page / 10) - 1) * 10) + 1 )
@php ( $end_page	= ceil($cur_page / 10) * 10 )
@php ( $end_page	= $end_page >= $total_page ? $total_page : $end_page )
<nav style="width: 100%; display: flex; justify-content: center; align-items: center; margin: 20px auto 30px;">
	<ul class="pagination">
@if ($start_page > $offset)
		<li class="page-item"><a class="page-link" href="{{ $pageinfo->url(1) }}">처음</a></li>
@endif
@if ($start_page <= $cur_page && $start_page - 1 != 0)
		<li class="page-item"><a class="page-link" href="{{ $pageinfo->url($start_page - 1) }}">이전</a></li>
@endif

@for ($p = $start_page; $p <= $end_page; $p++)
@if ($p == $cur_page)
		<li class="page-item active"><a class="page-link disabled font-weight-bold" style="cursor: initial;">{{ $p }}</a></li>
@else
		<li class="page-item"><a class="page-link" href="{{ $pageinfo->url($p) }}">{{ $p }}</a></li>
@endif
@endfor

@if ($p+1 <= $total_page)
		<li class="page-item"><a class="page-link" href="{{ $pageinfo->url($end_page + 1) }}">다음</a></li>
@endif
@if ($end_page < $total_page)
		<li class="page-item"><a class="page-link" href="{{ $pageinfo->url($total_page) }}">끝</a></li>
@endif
	</ul>
</nav>
