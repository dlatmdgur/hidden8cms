<div class="col-md-3 left_col select-none">
	<div class="left_col scroll-view">
		<div class="navbar nav_title" style="border: 0;">
			<a href="{{ route('dashboard') }}" class="site_title"><i class="fa fa-dashboard"></i> <span>{{ (count(explode(config('hosting.app_host'), $_SERVER['HTTP_HOST'])) > 1 ? config('hosting.header_name') : config('hosting.another_name')) }}</span></a>
		</div>

		<div class="clearfix"></div>

		<!-- menu profile quick info -->
		<!--
		<div class="profile clearfix">
			<div class="profile_pic">
				<img src="images/img.jpg" alt="..." class="img-circle profile_img">
			</div>
			<div class="profile_info">
				<span>Welcome,</span>
				<h2>John Doe</h2>
			</div>
		</div>
		-->
		<!-- /menu profile quick info -->

		<br />

		<!-- sidebar menu -->
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

			@if(auth()->user()->can('outer0') || auth()->user()->can('outer1') || auth()->user()->can('outer2') || auth()->user()->can('outer3') || auth()->user()->can('outer4') || auth()->user()->can('outer5'))
			<div class="menu_section">
				<ul class="nav side-menu">
					<li><a><i class="fa fa-male"></i> 추천인 정보<span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('relations.search') }}">추천인 관리</a></li>
							<li><a href="{{ route('relations.points') }}">포인트 적립내역</a></li>
							<li><a href="{{ route('relations.rewards') }}">포인트 지급</a></li>
							<li><a href="{{ route('relations.refunds') }}">포인트 환급</a></li>
							<li><a href="{{ route('relations.rewardlogs') }}">포인트 지급/환급 내역</a></li>
							<li style="border-top: 1px solid #606060;"><a href="{{ route('relations.userstat') }}">실시간 플레이 유저</a></li>
							<li><a href="{{ route('relations.logs') }}">배팅 현황</a></li>
				@if(auth()->user()->can('outer0'))
							<li style="border-top: 1px solid #606060;"><a href="{{ route('tester.list') }}">테스트유저 관리 설정</a></li>
				@endif
						</ul>
					</li>
				</ul>
			</div>
			@endif

			@if(auth()->user()->can('member') || auth()->user()->can('game'))
			<div class="menu_section">
				<div class="menu_title">게임 정보</div>
				<ul class="nav side-menu">
					@can('member')
					<li><a><i class="fa fa-male"></i> 사용자조회 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('members.list') }}">접속회원 리스트</a></li>
							<li><a href="{{ route('members.info') }}">회원정보</a></li>
							<li><a href="{{ route('members.edit') }}">회원정보 변경</a></li>
							<li><a href="{{ route('members.oneday') }}">하루 가입 유저</a></li>
							<li><a href="{{ route('members.referrer') }}">추천인별 가입자</a></li>
							<li><a href="{{ route('members.friend') }}">친구 리스트</a></li>
						</ul>
					</li>
					@endcan

					@can('game')
					<li><a><i class="fa fa-database"></i> 게임조회 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('games.info') }}">게임정보 조회</a></li>
							<li><a href="{{ route('games.payment') }}">결제내역 조회</a></li>
							<li><a href="{{ route('games.gem') }}">보석정보 조회</a></li>
							<li><a href="javascript:alert('준비중입니다.')">골드내역 조회</a></li>
{{--							<li><a href="{{ route('games.gold') }}">골드내역 조회</a></li>--}}
							<li><a href="{{ route('games.vault') }}">금고</a></li>
							<li><a href="{{ route('games.posts') }}">가방</a></li>
							<li><a href="{{ route('games.rakeBack') }}">저금통</a></li>
							<li><a href="{{ route('games.goods') }}">상품정보 조회</a></li>
							<li><a href="{{ route('games.limits') }}">손실한도 조회</a></li>
							<li><a href="{{ route('games.tournament') }}">토너먼트 조회</a></li>
						</ul>
					</li>

					@elsecan('outer0')
					<li><a><i class="fa fa-male"></i> 사용자조회 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('members.info') }}">회원정보</a></li>
						</ul>
					</li>
					<li><a><i class="fa fa-database"></i> 게임조회 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('games.info') }}">게임정보 조회</a></li>
							<li><a href="{{ route('games.tournament') }}">토너먼트 조회</a></li>
						</ul>
					</li>
					@endcan
				</ul>
			</div>
			@endif

			@if(auth()->user()->can('operation') || auth()->user()->can('log') || auth()->user()->can('management') || auth()->user()->can('outer0'))
			<div class="menu_section">
				<div class="menu_title"> 운영 정보 </div>
				<ul class="nav side-menu">

					@can('operation')
					<li><a><i class="fa fa-gift"></i> 운영정보 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('operations.chipGold') }}">칩/골드 지급/회수</a></li>
							<li><a href="{{ route('operations.posts') }}">가방 지급/회수</a></li>
							<li><a href="{{ route('operations.gem') }}">보석 지급/회수</a></li>
							<li><a href="{{ route('operations.effect') }}">효력수정</a></li>
							<li><a href="{{ route('operations.send') }}">대량발송 (가방)</a></li>
							<li><a href="{{ route('operations.ticketSeed') }}">티켓이벤트 수정</a></li>
						</ul>
					</li>
					@elsecan('outer0')
					<li><a><i class="fa fa-gift"></i> 운영정보 & 로그 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('operations.posts') }}">가방 지급/회수</a></li>
							<li><a href="{{ route('logs.posts') }}">가방 지급/회수 로그</a></li>
						</ul>
					</li>
					@endcan

					@can('log')
					<li><a><i class="fa fa-history"></i> 운영로그 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('logs.money') }}">칩/골드 지급/회수 로그</a></li>
							<li><a href="{{ route('logs.posts') }}">가방 지급/회수 로그</a></li>
							<li><a href="{{ route('logs.gem') }}">보석 지급/회수 로그</a></li>
							<li><a href="{{ route('logs.effect') }}">효력 수정 로그</a></li>
							<li><a href="{{ route('logs.send') }}">대량발송 로그</a></li>
							<li><a href="{{ route('logs.ticketSeed') }}">티켓이벤트 로그</a></li>
						</ul>
					</li>
					@endcan

					@can('management')
					<li><a><i class="fa fa-comment"></i> 운영관리 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('managements.notice') }}">포커 공지</a></li>
							<li><a href="{{ route('managements.faq') }}">포커 FAQ</a></li>
							<li><a href="{{ route('managements.rolling') }}">롤링공지</a></li>
						</ul>
					</li>
					@endcan

					@can('management')
						<li><a><i class="fa fa-cogs"></i> 서버설정 <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li><a href="{{ route('managements.version') }}">버전 설정</a></li>
								<li><a href="{{ route('managements.whitelist') }}">점검 화이트리스트 설정</a></li>
								<li><a href="{{ route('managements.tournament') }}">토너먼트 설정</a></li>
							</ul>
						</li>
					@endcan
				</ul>
			</div>
			@endif

			@if(auth()->user()->can('maintenance') ||  auth()->user()->can('permission'))
			<div class="menu_section">
				<div class="menu_title"> 운영툴 관리 </div>
				<ul class="nav side-menu">
					@can('maintenance')
						<li><a><i class="fa fa-upload"></i>업데이트 <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li><a href="{{ route('maintenance.ranking') }}">랭킹전 관리</a></li>
{{--								<li><a href="{{ route('maintenance.tables') }}">테이블 업로드</a></li>--}}
								<li><a href="javascript:alert('준비 중입니다.');">테이블 업로드</a></li>
							</ul>
						</li>
					@endcan

					@can('permission')
						<li><a><i class="fa fa-book"></i>권한관리 <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li><a href="{{ route('users.index') }}">사용자 등록/정보</a></li>
							</ul>
						</li>
					@endcan
					<!--
					<li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> 별도 메뉴용 <span class="label label-success pull-right">Coming Soon</span></a></li>
					-->
				</ul>
			</div>
			@endif

			@if(auth()->user()->can('monitor'))
			<div class="menu_section">
				<div class="menu_title"> 모니터링 </div>
				<ul class="nav side-menu">

					@can('monitor')
					<li><a><i class="fa fa-eye"></i>모니터링 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('monitor.ban') }}">일괄 변경</a></li>
							<li><a href="{{ route('monitor.ipUsers') }}">대량 ID</a></li>
							<!--<li><a href="{{ route('monitor.abuse') }}">사용자 검색</a></li>-->
							<li><a href="{{ route('monitor.groupUsers') }}">그룹 유저</a></li>
						</ul>
					</li>
					<!--<li><a href="{{ route('statistics.goodsUser') }}"><i class="fa fa-eye-slash"></i>회원별 골드 이용현황<span class="fa fa-chevron-down"></span></a>
					</li>-->
					@endcan
				</ul>
			</div>
			@endif

			<div class="menu_section">
				<div class="menu_title"> 리포트 </div>
				<ul class="nav side-menu">
					<li><a><i class="fa fa-file-o"></i>리포트 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('report.player') }}">플레이어별</a></li>

							<li><a href="{{ route('report.summary') }}">요약</a></li>

							<li><a href="{{ route('report.game') }}">게임별</a></li>

							<li><a href="{{ route('report.month') }}">월별</a></li>
						</ul>
					</li>
				</ul>
			</div>

			@if(auth()->user()->can('statistics'))
			<div class="menu_section">
				<div class="menu_title"> 통계 </div>
				<ul class="nav side-menu">
					<li><a><i class="fa fa-bar-chart"></i> 동접 현황 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('statistics.ccuLive') }}">CCU (실시간)</a></li>
							<li><a href="{{ route('statistics.ccu') }}">CCU</a></li>
						</ul>
					</li>
					<li><a href="{{ route('statistics.users') }}"><i class="fa fa-line-chart"></i>회원 이용 통계 <span class="fa fa-chevron-down"></span></a></li>
					<li><a href="{{ route('statistics.sales') }}"><i class="fa fa-dollar"></i>매출 및 구매현황 <span class="fa fa-chevron-down"></span></a></li>
					<li><a href="{{ route('statistics.items') }}"><i class="fa fa-shopping-bag"></i>아이템구매 및 소비현황 <span class="fa fa-chevron-down"></span></a></li>
					<li><a href="{{ route('statistics.goodsGame') }}"><i class="fa fa-table"></i>재화 현황 <span class="fa fa-chevron-down"></span></a></li>
					<!--<li><a href="{{ route('statistics.exchange') }}"><i class="fa fa-bank"></i>교환소 이용현황 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('statistics.exchange') }}">교환소 이용현황</a></li>
							<li><a href="{{ route('statistics.exchangeUser') }}">유저 교환소 이용현황</a></li>
						</ul>
					</li>-->
				</ul>
			</div>
			@endif

			@if(auth()->user()->can('maintenance'))
			<div class="menu_section">
				<div class="menu_title"> 슬롯 정보</div>

				@can('maintenance')
				<ul class="nav side-menu">
					<li><a><i class="fa fa-bar-chart"></i> 슬롯 유저정보 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('slotusers.search') }}">유저 검색</a></li>
						</ul>
					</li>
				</ul>

				<ul class="nav side-menu">
					<li><a><i class="fa fa-bar-chart"></i> 게임 설정 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('slotgamesv2.rtps') }}">슬롯 RTP구간 설정</a></li>
							<li><a href="{{ route('slotgamesv2.slots') }}">슬롯 설정 v2.0</a></li>
							<li><a href="{{ route('slotgamesv2.assigns') }}">보정잭팟 설정</a></li>
							<li><a href="{{ route('slotgamesv2.jackpots') }}">기간잭팟 설정</a></li>
							<li><a href="{{ route('slotgames.wv_slot') }}">웹뷰슬롯 설정</a></li>

							<li style="border-top: 1px solid #606060;"><a href="{{ route('tester.list') }}">테스트유저 관리 설정</a></li>
						@if (!in_array(auth()->user()->email, ['test@mega.com']))
							<li style="border-top: 1px solid #606060;"><a href="{{ route('event.jackpot.search') }}">이벤트 잭팟 설정</a></li>
							<li><a href="{{ route('global.jackpot.panel') }}">글로벌 잭팟 설정</a></li>
							<li><a href="{{ route('rtp.general') }}">RTP 설정</a></li>
						@endif
						</ul>
					</li>
				</ul>

				<ul class="nav side-menu">
					<li><a><i class="fa fa-bar-chart"></i> 슬롯 로그 <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('slotlogs.daily') }}">슬롯 일자별 통계</a></li>
							<li><a href="{{ route('slotlogsv2.spin') }}">스핀 로그 v2.0</a></li>
						</ul>
					</li>
				</ul>
				@elsecan('outer0')
				<ul class="nav side-menu">
					<li><a><i class="fa fa-bar-chart"></i> 슬롯 정보<span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="{{ route('slotusers.search') }}">유저 검색</a></li>
							<li><a href="{{ route('slotlogs.daily') }}">슬롯 일자별 통계</a></li>
							<li><a href="{{ route('slotlogsv2.spin') }}">스핀 로그 v2.0</a></li>
						</ul>
					</li>
				</ul>
				@endcan
			</div>
			@endif

		</div>
		<!-- /sidebar menu -->

		<!-- /menu footer buttons -->
		<!--<div class="sidebar-footer hidden-small">
			<a data-toggle="tooltip" data-placement="top" title="Settings">
				<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="FullScreen">
				<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="Lock">
				<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
				<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
			</a>
		</div>-->
		<!-- /menu footer buttons -->
	</div>
</div>
