

@php ( $request_uri = explode('?', $_SERVER['REQUEST_URI'])[0] )
								<nav class="nav nav-boxs">
									<a class="nav-link {{ $request_uri == '/rtp/general' ? 'active' : '' }}" href="{{ route('rtp.general') }}">GENERAL</a>
									<a class="nav-link {{ $request_uri == '/rtp/slot' ? 'active' : '' }}" href="{{ route('rtp.slot') }}">SLOT</a>
									<a class="nav-link {{ $request_uri == '/rtp/user' ? 'active' : '' }}" href="{{ route('rtp.user') }}">USER</a>
								</nav>
