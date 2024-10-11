<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\SlotGameController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(
	[
		'middleware'	=>	['ipCheck', 'auth']
	],
	function ()
	{
		Route::resource('users', 'UserController');


		Route::get('/', function (){ return redirect('/member/info'); });

		Route::get('/home', function (){ return redirect('/member/info'); });

		Route::get('/dashboard', function (){ return redirect('/member/info'); })->name('dashboard');


		//Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
		Route::get('/blank', 'BlankController@index')->name('blank');

		Route::get('/user/profile', 'UserController@profile')->name('profile');

		Route::post('/user/reset/{id}', 'UserController@reset')->name('reset');



		##### maintenance
		Route::get('/maintenance/tables', 'MaintenanceController@tables')->name('maintenance.tables');

		##### members
		Route::get('/member/list', 'MemberController@list')->name('members.list');
		Route::get('/member/info', 'MemberController@info')->name('members.info');
		Route::post('/member/search', 'MemberController@search')->name('members.search');
		Route::post('/member/forceClose', 'MemberController@forceClose')->name('members.forceClose');
		Route::get('/member/edit', 'MemberController@edit')->name('members.edit');
		Route::post('/member/checkNickname', 'MemberController@checkNickname')->name('members.checkNickname');
		Route::post('/member/update', 'MemberController@update')->name('members.update');
		Route::get('/member/oneday', [MemberController::class, 'oneday'])->name('members.oneday');
		Route::post('/member/oneday', [MemberController::class, 'getOneday']);
		Route::post('/member/delDi', [MemberController::class, 'delDi']);
		Route::get('/member/referrer', [MemberController::class, 'referrer'])->name('members.referrer');
		Route::post('/member/referrer', [MemberController::class, 'getReferrer']);
		Route::get('/member/friend', [MemberController::class, 'friend'])->name('members.friend');

		Route::get('/member/certification/{user_seq}/{type}', 'MemberController@certification')->name('members.certification');

		##### games
		Route::get('/game/info', 'GameController@info')->name('games.info');
		Route::post('/game/search', 'GameController@search')->name('games.search');
		Route::post('/game/detail', 'GameController@detail')->name('games.detail');
		Route::post('/game/detailSearch', 'GameController@detailSearch')->name('games.detailSearch');
		Route::post('/game/detailExcel', 'GameController@detailExcel')->name('games.detailExcel');
		Route::post('/game/detail2', 'GameController@detail2')->name('games.detail2');
		Route::post('/game/detail3', 'GameController@detail3')->name('games.detail3');
		Route::post('/game/packet', 'GameController@packet')->name('games.packet');
		Route::post('/game/betting', 'GameController@betting')->name('games.betting');

		// Route::get('/game/seven', 'GameController@seven')->name('games.seven');
		// Route::get('/game/holdem', 'GameController@holdem')->name('games.holdem');
		// Route::get('/game/badugi', 'GameController@badugi')->name('games.badugi');
		// Route::get('/game/hilow', 'GameController@hilow')->name('games.hilow');
		// Route::get('/game/baccarat', 'GameController@baccarat')->name('games.baccarat');
		// Route::get('/game/all', 'GameController@all')->name('games.all');

		Route::get('/game/payment', 'GameController@payment')->name('games.payment');
		Route::post('/game/billing', 'GameController@billing')->name('games.billing');

		// common
		Route::post('/game/userInfo', 'GameController@userInfo')->name('games.userInfo');

		Route::get('/game/gem', 'GameController@gem')->name('games.gem');
		Route::post('/game/gemLogs', 'GameController@gemLogs')->name('games.gemLogs');

		Route::get('/game/vault', 'GameController@vault')->name('games.vault');
		Route::post('/game/vaultLogs', 'GameController@vaultLogs')->name('games.vaultLogs');

		Route::get('/game/posts', 'GameController@posts')->name('games.posts');
		Route::post('/game/presents', 'GameController@presents')->name('games.presents');

		Route::get('/game/goods', 'GameController@goods')->name('games.goods');
		Route::post('/game/inventory', 'GameController@inventory')->name('games.inventory');
		Route::post('/game/buyLogs', 'GameController@buyLogs')->name('games.buyLogs');

		Route::get('/game/limits', 'GameController@limits')->name('games.limits');
		Route::post('/game/resetChangeCnt', [GameController::class, 'resetChangeCnt']);

		//Route::get('/game/gold', 'GameController@gold')->name('games.golds');
		//Route::get('/game/goldLogs', 'GameController@goldLogs')->name('games.goldLogs');

		Route::get('/game/rakeBack', 'GameController@rakeBack')->name('games.rakeBack');
		Route::post('/game/rakeBackLogs', 'GameController@rakeBackLogs')->name('games.rakeBackLogs');

		//Route::get('/game/exchange', 'GameController@exchange')->name('games.exchange');
		//Route::get('/game/exchangeLogs', 'GameController@exchangeLogs')->name('games.exchangeLogs');

		Route::post('/game/friends', 'GameController@friends')->name('games.friends');
		Route::post('/game/friendsLogs', 'GameController@friendsLogs')->name('games.friendsLogs');

		Route::get('/game/tournament', [GameController::class, 'tournament'])->name('games.tournament');
		Route::post('/game/tournament/rank', [GameController::class, 'getTournamentRank'])->name('games.tournament.rank');



		##### operation
		Route::get('/operation/chipGold', 'OperationController@chipGold')->name('operations.chipGold');
		Route::post('/operation/editChipGold', 'OperationController@editChipGold')->name('operations.editChipGold');

		Route::get('/operation/gem', 'OperationController@gem')->name('operations.gem');
		Route::post('/operation/editGem', 'OperationController@editGem')->name('operations.editGem');

		Route::get('/operation/posts', 'OperationController@posts')->name('operations.posts');
		Route::post('/operation/editPresent', 'OperationController@editPresent')->name('operations.editPresent');

		Route::get('/operation/send', 'OperationController@send')->name('operations.send');
		Route::post('/operation/excelUpload', 'OperationController@excelUpload')->name('operations.excelUpload');
		Route::post('/operation/sendMassive', 'OperationController@sendMassive')->name('operations.sendMassive');

		Route::get('/operation/effect', 'OperationController@effect')->name('operations.effect');
		Route::post('/operation/editEffect', 'OperationController@editEffect')->name('operations.editEffect');

		Route::get('/operation/ticketSeed', [OperationController::class, 'ticketSeed'])->name('operations.ticketSeed');
		Route::post('/operation/ticketSeed/delete', [OperationController::class, 'ticketSeedDelete']);



		##### logs
		Route::get('/log/money', 'LogController@money')->name('logs.money');
		Route::post('/log/moneyLogs', 'LogController@moneyLogs')->name('logs.moneyLogs');
		Route::get('/log/moneyLogs/detail', 'LogController@detailMoneyLogs')->name('logs.detailMoneyLogs');

		Route::get('/log/posts', 'LogController@posts')->name('logs.posts');
		Route::post('/log/postsLogs', 'LogController@postsLogs')->name('logs.postsLogs');
		Route::get('/log/postsLogs/detail', 'LogController@detailPostsLogs')->name('logs.detailPostsLogs');

		Route::get('/log/gem', 'LogController@gem')->name('logs.gem');
		Route::post('/log/gemLogs', 'LogController@gemLogs')->name('logs.gemLogs');

		Route::get('/log/effect', 'LogController@effect')->name('logs.effect');
		Route::post('/log/effectLogs', 'LogController@effectLogs')->name('logs.effectLogs');

		Route::get('/log/send', 'LogController@send')->name('logs.send');
		Route::post('/log/sendLogs', 'LogController@sendLogs')->name('logs.sendLogs');

		Route::get('/log/ticketSeed', [LogController::class, 'ticketSeed'])->name('logs.ticketSeed');



		##### management
		Route::post('/management/upload', 'ManagementController@upload')->name('managements.upload');

		Route::get('/management/notice', 'ManagementController@notice')->name('managements.notice');
		Route::post('/management/listNotice', 'ManagementController@listNotice')->name('managements.listNotice');
		Route::post('/management/getNotice', 'ManagementController@getNotice')->name('managements.getNotice');
		Route::post('/management/updateNotice', 'ManagementController@updateNotice')->name('managements.updateNotice');
		Route::get('/management/previewNotice', 'ManagementController@previewNotice')->name('managements.previewNotice');
		Route::get('/management/publishNotice', 'ManagementController@publishNotice')->name('managements.publishNotice');

		Route::get('/management/faq', 'ManagementController@faq')->name('managements.faq');
		Route::post('/management/listFaq', 'ManagementController@listFaq')->name('managements.listFaq');
		Route::post('/management/getFaq', 'ManagementController@getFaq')->name('managements.getFaq');
		Route::post('/management/updateFaq', 'ManagementController@updateFaq')->name('managements.updateFaq');
		Route::get('/management/previewFaq', 'ManagementController@previewFaq')->name('managements.previewFaq');
		Route::get('/management/publishFaq', 'ManagementController@publishFaq')->name('managements.publishFaq');

		Route::get('/management/rolling', [ManagementController::class, 'rolling'])->name('managements.rolling');
		Route::post('/management/listRolling', [ManagementController::class, 'listRolling'])->name('managements.listRolling');
		Route::post('/management/setRolling', [ManagementController::class, 'setRolling'])->name('managements.setRolling');
		Route::post('/management/dropRolling', [ManagementController::class, 'dropRolling'])->name('managements.dropRolling');

		Route::get('/management/version', [ManagementController::class, 'version'])->name('managements.version');
		Route::post('/management/listVersion', [ManagementController::class, 'listVersion'])->name('managements.listVersion');
		Route::post('/management/setVersion', [ManagementController::class, 'setVersion'])->name('managements.setVersion');
		Route::post('/management/dropVersion', [ManagementController::class, 'dropVersion'])->name('managements.dropVersion');
		Route::get('/management/whitelist', [ManagementController::class, 'whitelist'])->name('managements.whitelist');
		Route::post('/management/listWhitelist', [ManagementController::class, 'listWhitelist'])->name('managements.listWhitelist');
		Route::post('/management/setWhitelist', [ManagementController::class, 'setWhitelist'])->name('managements.setWhitelist');
		Route::post('/management/dropWhitelist', [ManagementController::class, 'dropWhitelist'])->name('managements.dropWhitelist');

		Route::get('/management/tournament', [ManagementController::class, 'tournament'])->name('managements.tournament');
		Route::get('/management/tournament/detail/{tid}', [ManagementController::class, 'tournamentDetail'])->name('managements.tournament.detail');
		Route::post('/management/tournament/excel', [ManagementController::class, 'tournamentExcel']);
		Route::post('/management/tournament/reg', [ManagementController::class, 'tournamentReg']);
		Route::post('/management/tournament/member/reg', [ManagementController::class, 'tournamentRegMember']);



		##### maintenance
		Route::get('/maintenance/ranking', 'MaintenanceController@ranking')->name('maintenance.ranking');
		Route::post('/maintenance/rankSchedule', 'MaintenanceController@rankSchedule')->name('maintenance.rankSchedule');
		Route::post('/maintenance/editSchedule', 'MaintenanceController@editSchedule')->name('maintenance.editSchedule');

		Route::get('/maintenance/tables', 'MaintenanceController@tables')->name('maintenance.tables');



		##### monitor
		Route::get('/monitor/abuse', 'MonitoringController@abuse')->name('monitor.abuse');
		Route::get('/monitor/ipUsers', 'MonitoringController@ipUsers')->name('monitor.ipUsers');
		Route::post('/monitor/searchIp', 'MonitoringController@searchIp')->name('monitor.searchIp');
		Route::post('/monitor/searchIpUsers', 'MonitoringController@searchIpUsers')->name('monitor.searchIpUsers');

		Route::get('/monitor/ban', 'MonitoringController@ban')->name('monitor.ban');
		Route::post('/monitor/editMassive', 'MonitoringController@editMassive')->name('monitor.editMassive');
		Route::post('/monitor/excelUpload', 'MonitoringController@excelUpload')->name('monitor.excelUpload');

		Route::get('/monitor/groupUsers', 'MonitoringController@groupUsers')->name('monitor.groupUsers');
		Route::post('/monitor/groupLogs', 'MonitoringController@groupLogs')->name('monitor.groupLogs');



		##### Customer for Slot
		Route::post('/management/slot/upload', 'SlotManagementController@upload')->name('managements.slot.upload');

		Route::get('/management/slot/notice', 'SlotManagementController@notice')->name('managements.slot.notice');
		Route::post('/management/slot/listNotice', 'SlotManagementController@listNotice')->name('managements.slot.listNotice');
		Route::post('/management/slot/getNotice', 'SlotManagementController@getNotice')->name('managements.slot.getNotice');
		Route::post('/management/slot/updateNotice', 'SlotManagementController@updateNotice')->name('managements.slot.updateNotice');
		Route::get('/management/slot/previewNotice', 'SlotManagementController@previewNotice')->name('managements.slot.previewNotice');
		Route::get('/management/slot/publishNotice', 'SlotManagementController@publishNotice')->name('managements.slot.publishNotice');

		Route::get('/management/slot/faq', 'SlotManagementController@faq')->name('managements.slot.faq');
		Route::post('/management/slot/listFaq', 'SlotManagementController@listFaq')->name('managements.slot.listFaq');
		Route::post('/management/slot/getFaq', 'SlotManagementController@getFaq')->name('managements.slot.getFaq');
		Route::post('/management/slot/updateFaq', 'SlotManagementController@updateFaq')->name('managements.slot.updateFaq');
		Route::get('/management/slot/previewFaq', 'SlotManagementController@previewFaq')->name('managements.slot.previewFaq');
		Route::get('/management/slot/publishFaq', 'SlotManagementController@publishFaq')->name('managements.slot.publishFaq');



		##### statistics
		Route::get('/statistics/ccu', 'StatisticsController@ccu')->name('statistics.ccu');
		Route::get('/statistics/ccuLive', 'StatisticsController@ccuLive')->name('statistics.ccuLive');
		Route::get('/statistics/sales', 'StatisticsController@sales')->name('statistics.sales');
		Route::get('/statistics/items', 'StatisticsController@items')->name('statistics.items');
		Route::get('/statistics/users', 'StatisticsController@users')->name('statistics.users');
		Route::get('/statistics/goodsGame', 'StatisticsController@goodsGame')->name('statistics.goodsGame');
		Route::get('/statistics/goodsUser', 'StatisticsController@goodsUser')->name('statistics.goodsUser');
		Route::get('/statistics/exchange', 'StatisticsController@exchange')->name('statistics.exchange');
		Route::get('/statistics/exchangeUser', 'StatisticsController@exchangeUser')->name('statistics.exchangeUser');
		Route::post('/statistics/getExchangeLogs', 'StatisticsController@getExchangeLogs')->name('statistics.getExchangeLogs');
		Route::post('/statistics/excelExchangeLogs', 'StatisticsController@excelExchangeLogs')->name('statistics.excelExchangeLogs');
		Route::get('/statistics/exchangeLimitExcept', 'StatisticsController@exchangeLimitExcept')->name('statistics.exchangeLimitExcept');
		Route::post('/statistics/searchPassUser', 'StatisticsController@searchPassUser')->name('statistics.searchPassUser');
		Route::post('/statistics/addPassUser', 'StatisticsController@addPassUser')->name('statistics.addPassUser');

		Route::post('/statistics/getCcuLogs', 'StatisticsController@getCcuLogs')->name('statistics.getCcuLogs');
		Route::post('/statistics/getDailyLogs', 'StatisticsController@getDailyLogs')->name('statistics.getDailyLogs');





		//////////////////////////////////////////////////////////////////////////////////////////////
		//																							//
		//	슬롯 설정 영역 v1.0																		//
		//																							//
		//////////////////////////////////////////////////////////////////////////////////////////////

		// 슬롯 유저 검색
		Route::get('/slotusers/search', 'SlotUserController@search')->name('slotusers.search');
		Route::get('/slotusers/detail', 'SlotUserController@detail')->name('slotusers.detail');
		Route::post('/slotusers/reset_rtp', 'SlotUserController@reset_rtp')->name('slotusers.reset_rtp');
		Route::post('/slotusers/set_buff', 'SlotUserController@set_buff')->name('slotusers.set_buff');
		Route::post('/slotusers/drop_buff', 'SlotUserController@drop_buff')->name('slotusers.drop_buff');

		// 슬롯 데이터 조작
		Route::get('/slotgames/reset', 'SlotGameController@reset')->name('slotgames.reset');
		// 슬롯 조회
		Route::get('/slotgames/slots', 'SlotGameController@slots')->name('slotgames.slots');
		// 슬롯 등록
		Route::post('/slotgames/slot_set', 'SlotGameController@slot_set')->name('slotgames.slot_set');
		// 슬롯 삭제
		Route::post('/slotgames/slot_drop', 'SlotGameController@slot_drop')->name('slotgames.slot_drop');
		// 슬롯 확률 변경
		Route::post('/slotgames/slot_probability', 'SlotGameController@slot_probability')->name('slotgames.slot_probability');
		// 슬롯 UID 변경
		Route::post('/slotgames/uidset', 'SlotGameController@uidset')->name('slotgames.uidset');
		// 웹뷰슬롯 관리
		Route::get('/slotgames/wv_slot', [SlotGameController::class, 'wv_slot'])->name('slotgames.wv_slot');
		Route::post('/slotgames/wv_slot_set', [SlotGameController::class, 'wv_slot_set'])->name('slotgames.wv_slot_set');
		Route::post('/slotgames/wv_slot_drop', [SlotGameController::class, 'wv_slot_drop'])->name('slotgames.wv_slot_drop');
		// 테스트 유저 관리
		Route::get('/tester', 'TesterController@list')->name('tester.list');
		Route::get('/tester/search', 'TesterController@search')->name('tester.search');
		Route::post('/tester/set', 'TesterController@set')->name('tester.set');
		Route::post('/tester/drop', 'TesterController@drop')->name('tester.drop');

		// 게임데이터 조회
		Route::get('/slotgames/gamedata/{slot_id}', 'SlotGameController@gamedata')->name('slotgames.gamedata');
		Route::get('/slotgames/gamedata/{slot_id}/{ver}', 'SlotGameController@gamedata')->name('slotgames.gamedata');
		// 게임데이터 등록
		Route::post('/slotgames/gamedata_set', 'SlotGameController@gamedata_set')->name('slotgames.gamedata_set');
		// 게임데이터 삭제
		Route::post('/slotgames/gamedata_drop', 'SlotGameController@gamedata_drop')->name('slotgames.gamedata_drop');

		// 배팅정보 조회
		Route::get('/slotgames/betinfo/{slot_id}', 'SlotGameController@betinfo')->name('slotgames.betinfo');
		// 배팅정보 등록
		Route::post('/slotgames/betinfo_set', 'SlotGameController@betinfo_set')->name('slotgames.betinfo_set');
		// 배팅정보 삭제
		Route::post('/slotgames/betinfo_drop', 'SlotGameController@betinfo_drop')->name('slotgames.betinfo_drop');

		// 이벤트 잭팟
		Route::get('/event/jackpot', 'EventJackpotController@search')->name('event.jackpot.search');
		Route::post('/event/jackpot/apply', 'EventJackpotController@apply')->name('event.jackpot.apply');
		Route::post('/event/jackpot/cancel', 'EventJackpotController@cancel')->name('event.jackpot.cancel');
		Route::get('/event/jackpot/{useq}', 'EventJackpotController@jackpotsByUser')->name('event.jackpot.byuser');
		Route::delete('/event/jackpot/{useq}/{idx}', 'EventJackpotController@cancelJackpot')->name('event.jackpot.logs');

		// 글로벌 잭팟
		Route::get('/global/jackpot/panel', 'GlobalJackpotController@panel')->name('global.jackpot.panel');
		Route::get('/global/jackpot/switch/{idx}/{status}', 'GlobalJackpotController@switch')->name('global.jackpot.switch');
		Route::post('/global/jackpot/switch/slot', 'GlobalJackpotController@slotSwitch')->name('global.jackpot.slotSwitch');

		Route::get('/global/jackpot/force', 'GlobalJackpotController@force')->name('global.jackpot.force');
		Route::post('/global/jackpot/apply', 'GlobalJackpotController@apply')->name('global.jackpot.apply');

		Route::get('/global/jackpot/forcelog', 'GlobalJackpotController@forcelog')->name('global.jackpot.forcelog');

		Route::get('/rtp/general', 'RtpController@general')->name('rtp.general');
		Route::post('/rtp/general/set', 'RtpController@general_set')->name('rtp.general.set');

		Route::get('/rtp/slot', 'RtpController@slot')->name('rtp.slot');
		Route::post('/rtp/slot/set', 'RtpController@slot_set')->name('rtp.slot.set');

		Route::get('/rtp/user', 'RtpController@user')->name('rtp.user');
		Route::post('/rtp/user/set', 'RtpController@user_set')->name('rtp.user.set');

		// 잭팟 사용 유저
		Route::get('/event/users', 'EventJackpotController@users')->name('event.jackpot.users');





		//////////////////////////////////////////////////////////////////////////////////////////////
		//																							//
		//	신규 슬롯 영역 v2.0																		//
		//																							//
		//////////////////////////////////////////////////////////////////////////////////////////////

		// 슬롯 RTP 조작
		Route::get('/slotgamesv2/rtps', 'SlotGameV2Controller@rtps')->name('slotgamesv2.rtps');
		Route::post('/slotgamesv2/set_rtp', 'SlotGameV2Controller@set_rtp')->name('slotgamesv2.set_rtp');
		Route::post('/slotgamesv2/drop_rtp', 'SlotGameV2Controller@drop_rtp')->name('slotgamesv2.drop_rtp');
		Route::post('/slotgamesv2/reset_rtp', 'SlotGameV2Controller@reset_rtp')->name('slotgamesv2.reset_rtp');

		// 보정잭팟 설정
		Route::get('/slotgamesv2/assigns', 'SlotGameV2Controller@assigns')->name('slotgamesv2.assigns');
		Route::post('/slotgamesv2/set_assigns', 'SlotGameV2Controller@set_assigns')->name('slotgamesv2.set_assigns');
		Route::post('/slotgamesv2/drop_assigns', 'SlotGameV2Controller@drop_assigns')->name('slotgamesv2.drop_assigns');
		Route::post('/slotgamesv2/reset_assigns', 'SlotGameV2Controller@reset_assigns')->name('slotgamesv2.reset_assigns');

		// 기간잭팟 설정
		Route::get('/slotgamesv2/jackpots', 'SlotGameV2Controller@jackpots')->name('slotgamesv2.jackpots');
		Route::post('/slotgamesv2/set_jackpot', 'SlotGameV2Controller@set_jackpot')->name('slotgamesv2.set_jackpot');
		Route::post('/slotgamesv2/drop_jackpot', 'SlotGameV2Controller@drop_jackpot')->name('slotgamesv2.drop_jackpot');

		// 슬롯 데이터 조작
		Route::get('/slotgamesv2/slots', 'SlotGameV2Controller@slots')->name('slotgamesv2.slots');
		Route::post('/slotgamesv2/set_slot', 'SlotGameV2Controller@set_slot')->name('slotgamesv2.set_slot');

		Route::post('/slotgamesv2/reset', 'SlotGameV2Controller@reset')->name('slotgamesv2.reset');

		Route::get('/slotgamesv2/detail/{slot_id}', 'SlotGameV2Controller@detail')->name('slotgamesv2.detail');
		Route::post('/slotgamesv2/set_custom', 'SlotGameV2Controller@set_custom')->name('slotgamesv2.set_custom');
		Route::post('/slotgamesv2/drop_custom', 'SlotGameV2Controller@drop_custom')->name('slotgamesv2.drop_custom');

		Route::get('/slotgamesv2/bettings/{slot_id}', 'SlotGameV2Controller@bettings')->name('slotgamesv2.bettings');
		Route::post('/slotgamesv2/set_betting', 'SlotGameV2Controller@set_betting')->name('slotgamesv2.set_betting');
		Route::post('/slotgamesv2/drop_betting', 'SlotGameV2Controller@drop_betting')->name('slotgamesv2.drop_betting');


		// 스핀 로그
		Route::get('/slotlogsv2/spin', 'SlotLogV2Controller@spin')->name('slotlogsv2.spin');
		Route::post('/slotlogsv2/spin', 'SlotLogV2Controller@spin')->name('slotlogsv2.spin');





		//////////////////////////////////////////////////////////////////////////////////////////////
		//																							//
		//	슬롯 로그 영역																			//
		//																							//
		//////////////////////////////////////////////////////////////////////////////////////////////

		// 슬롯 일자별 통계
		Route::get('/slotlogs/daily', 'SlotLogController@daily')->name('slotlogs.daily');

		// 슬롯 일자별 통계 (노멀)
		Route::get('/slotlogs/normalize', 'SlotLogController@normalize')->name('slotlogs.normalize');

		// 스핀 로그
		Route::get('/slotlogs/spin', 'SlotLogController@spin')->name('slotlogs.spin');
		Route::post('/slotlogs/spin', 'SlotLogController@spin')->name('slotlogs.spin');





		//////////////////////////////////////////////////////////////////////////////////////////////
		//																							//
		//	관계 포인트 영역																		//
		//																							//
		//////////////////////////////////////////////////////////////////////////////////////////////

		// 관계 목록
		Route::get('/relations/search', 'RelationController@search')->name('relations.search');
		Route::post('/relations/search', 'RelationController@search')->name('relations.search');

		Route::get('/relations/usearch/{search}', 'RelationController@usearch')->name('relations.usearch');
		Route::get('/relations/parents/{depth}', 'RelationController@parents')->name('relations.parents');
		Route::get('/relations/childs/{parent_seq}/{depth}', 'RelationController@childs')->name('relations.childs');
		Route::get('/relations/childs/{parent_seq}/{depth}/{keyword}', 'RelationController@childs')->name('relations.childs');

		Route::post('/relations/set', 'RelationController@set')->name('relations.set');
		Route::post('/relations/drop', 'RelationController@drop')->name('relations.drop');

		// 상세 목록
		Route::get('/relations/detail/{idx}', 'RelationController@detail')->name('relations.detail');
		Route::get('/relations/rates/{idx}', 'RelationController@rates')->name('relations.rates');
		Route::post('/relations/setrates', 'RelationController@setrates')->name('relations.setrates');
		Route::post('/relations/setuserrate', 'RelationController@setuserrate')->name('relations.setuserrate');

		Route::get('/relations/points' , 'RelationController@points')->name('relations.points');
		Route::get('/relations/pointdetail/{user_seq}/{date}/{page}/{limit}' , 'RelationController@pointdetail')->name('relations.pointdetail');

		Route::get('/relations/depthdetail/{user_seq}/{depth}/{page}/{limit}' , 'RelationController@depthdetail')->name('relations.depthdetail');

		Route::get('/relations/memberdetail/{user_seq}' , 'RelationController@memberdetail')->name('relations.memberdetail');
		Route::post('/relations/memberset' , 'RelationController@memberset')->name('relations.memberset');

		Route::get('/relations/rewards', 'RelationController@rewards')->name('relations.rewards');
		Route::post('/relations/rewardset', 'RelationController@rewardset')->name('relations.rewardset');
		Route::post('/relations/rewardcancel', 'RelationController@rewardcancel')->name('relations.rewardcancel');
		Route::get('/relations/rewardlogs', 'RelationController@rewardlogs')->name('relations.rewardlogs');

		Route::get('/relations/refunds', 'RelationController@refunds')->name('relations.refunds');
		Route::post('/relations/refundset', 'RelationController@refundset')->name('relations.refundset');

		Route::get('/relations/userstat', 'RelationController@userstat')->name('relations.userstat');

		// 리포트
		Route::get('/relations/logs/{parent}', 'RelationController@logs')->name('relations.logs');
		Route::get('/relations/logs', 'RelationController@logs')->name('relations.logs');

	});
