<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\Account\Certification;
use App\Model\Account\TodayLoseChip;
use App\Model\CMS\AdminLog;
use App\Model\CMS\Tournament;
use App\Model\Game\BillingInfo;
use App\Model\Game\FreeChargeInfoAdmob;
use App\Model\Game\Gem;
use App\Model\Game\Inventory;
use App\Model\Game\Present;
use App\Model\Game\UserInfo;
use App\Model\Log\BaccaratResultLog;
use App\Model\Log\BlackjackResultLog;
use App\Model\Log\BuyLog;
use App\Model\Log\ExchangeLog;
use App\Model\Log\GameResultLog;
use App\Model\Log\HighlowResultLog;
use App\Model\Log\LoginLog;
use App\Model\Log\PacketLog;
use App\Model\Log\RakeBackLog;
use App\Model\Log\SafeLog;
use App\Model\Tables\Item;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class GameController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the game info.
	 *
	 * @return Renderable
	 */
	public function info()
	{
		return view('games.info');
	}

	/**
	 * Show Users User Info
	 *
	 * @return JsonResponse
	 */
	public function userInfo(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$loginLog = null;
		$adminLog = null;
		$presents = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'from' => 'required'
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');
		$from = $request->input('from');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
		}

		$limitInfo = null;

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			// find login log
			$loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();
			// last admin log
			$adminLog = AdminLog::where('user_seq', $accountInfo->user_seq)->orderby('id', 'DESC')->first();
			// limit info
			$limitInfo = TodayLoseChip::getTodayLoseChip($accountInfo->user_seq)->first();
			if($limitInfo) {
				$limitInfo->isChange = false;
				if(Helper::isTesterDi($limitInfo->di)) $limitInfo->isChange = true;
				unset($limitInfo->di);
			}

			if ($from == "posts") {
				$presents = Present::getUnreadPresents($accountInfo->user_seq);
			}
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'limitInfo' => $limitInfo,
			'loginLog' => $loginLog,
			'adminLog' => $adminLog,
			'presents' => $presents,
			'messages' => $messages
		], $status);
	}

	/**
	 * Search the members info for Ajax Call.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function search(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$billing_limit = null;

		$blackjackGameInfo = null;
		$baccaratGameInfo = null;
		$badugiGameInfo = null;
		//$highLowGameInfo = null;
		//$sevenPokerGameInfo = null;
		$texasHoldemGameInfo = null;
		$badugiHoldemGameInfo 	= null;
		//$taisaiGameInfo = null;
		//$omahaHoldemGameInfo = null;

		$dailyBlackjackInfo = null;
		$dailyBaccaratInfo = null;
		$dailyBadugiInfo = null;
		//$dailyHighLowInfo = null;
		//$dailySevenPokerInfo = null;
		$dailyTexasHoldemInfo = null;
		$dailyBadugiHoldemInfo = null;
		//$dailyTaisaiInfo = null;
		//$dailyOmahaHoldemInfo = null;

		$loginLog = null;
		$adminLog = null;
		$freeChargeAdmob = null;
		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
		}

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();

			// find billing limit by user_seq
			$billing_limit = BillingInfo::getMonthlyBillingAmount($accountInfo->user_seq);

			// find login log
			$loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();

			// find free video log
			$freeChargeAdmob = FreeChargeInfoAdmob::where('user_seq', $accountInfo->user_seq)
				->where('update_date', '>=', Carbon::today())->first();

			// find all game info
			$blackjackGameInfo = BlackjackResultLog::getGameLog(2, $accountInfo->user_seq);
			$baccaratGameInfo = BaccaratResultLog::getGameLog(3, $accountInfo->user_seq);
			$badugiGameInfo = GameResultLog::getGameLog(4, $accountInfo->user_seq);
			//$highLowGameInfo = HighlowResultLog::getGameLog(5, $accountInfo->user_seq);
			//$sevenPokerGameInfo = GameResultLog::getGameLog(6, $accountInfo->user_seq);

			//2024-09-25 루키자유방은 홀덤에서 제외하고 계산해달라고 하심
			$texasHoldemGameInfo = GameResultLog::getGameLog(7, $accountInfo->user_seq, ['10000']);
			//$taisaiGameInfo = GameResultLog::getGameLog(8, $accountInfo->user_seq);
			//$omahaHoldemGameInfo = GameResultLog::getGameLog(9, $accountInfo->user_seq);
			$badugiHoldemGameInfo = GameResultLog::getGameLog(11, $accountInfo->user_seq);

			// daily
			$dailyBlackjackInfo = BlackjackResultLog::getDailyLog(2, $accountInfo->user_seq);
			$dailyBaccaratInfo = BaccaratResultLog::getDailyLog(3, $accountInfo->user_seq);
			$dailyBadugiInfo = GameResultLog::getDailyLog(4, $accountInfo->user_seq);
			//$dailyHighLowInfo = HighlowResultLog::getDailyLog(5, $accountInfo->user_seq);
			//$dailySevenPokerInfo = GameResultLog::getDailyLog(6, $accountInfo->user_seq);
			$dailyTexasHoldemInfo = GameResultLog::getDailyLog(7, $accountInfo->user_seq, null, ['10000']);
			//$dailyTaisaiInfo = GameResultLog::getDailyLog(8, $accountInfo->user_seq);
			//$dailyOmahaHoldemInfo = GameResultLog::getDailyLog(9, $accountInfo->user_seq);
			$dailyBadugiHoldemInfo = GameResultLog::getDailyLog(11, $accountInfo->user_seq);

		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'billing_limit' => $billing_limit,

			'blackjackGameInfo' => $blackjackGameInfo,
			'baccaratGameInfo' => $baccaratGameInfo,
			'badugiGameInfo' => $badugiGameInfo,
			//'highLowGameInfo' => $highLowGameInfo,
			//'sevenPokerGameInfo' => $sevenPokerGameInfo,
			'texasHoldemGameInfo' => $texasHoldemGameInfo,
			//'taisaiGameInfo' => $taisaiGameInfo,
			//'omahaHoldemGameInfo' => $omahaHoldemGameInfo,
			'badugiHoldemGameInfo' => $badugiHoldemGameInfo,

			'dailyBlackjackInfo' => $dailyBlackjackInfo,
			'dailyBaccaratInfo' => $dailyBaccaratInfo,
			'dailyBadugiInfo' => $dailyBadugiInfo,
			//'dailyHighLowInfo' => $dailyHighLowInfo,
			//'dailySevenPokerInfo' => $dailySevenPokerInfo,
			'dailyTexasHoldemInfo' => $dailyTexasHoldemInfo,
			//'dailyTaisaiInfo' => $dailyTaisaiInfo,
			//'dailyOmahaHoldemInfo' => $dailyOmahaHoldemInfo,
			'dailyBadugiHoldemInfo' => $dailyBadugiHoldemInfo,

			'loginLog' => $loginLog,
			'freeChargeAdmob' => $freeChargeAdmob,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the game info.
	 *
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function detail(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'gameTypes' => 'required',
			'betType' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

	//        if ($validator->fails()) {
	//            return response()->json([
	//                'error'    => true,
	//                'messages' => $validator->errors(),
	//            ], 422);
	//        }

		$userSeq = $request->input('userSeq');
		$gameTypes = $request->input('gameTypes');
		$betType = $request->input('betType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');

		$gameTypeArr = json_decode($gameTypes, true);

		$availableChans = array();

		//게임이 단일로 선택되어있다면
		if (sizeof($gameTypeArr) === 1)
		{
			$extractChan =  Helper::gameChannels()[$gameTypeArr[0]];

			//배팅 타입이 전체가 아니라면?
			if ($betType !== 'all')
			{
				foreach ($extractChan as $chan => $info)
				{
					if ($info['type'] === $betType)
						continue;

					unset($extractChan[$chan]);
				}
			}
			foreach ($extractChan as $chan => $info)
				$availableChans[substr($chan,3)] = array('bet' => $info['bet'], 'name' => $info['name']) ;
		}
		return view('games.detail', compact('userSeq', 'gameTypes', 'betType', 'startDate', 'endDate', 'availableChans'));
	}

	/**
	 * Show the game info.
	 *
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function detail2(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'gameTypes' => 'required',
			'betType' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

	//        if ($validator->fails()) {
	//            return response()->json([
	//                'error'    => true,
	//                'messages' => $validator->errors(),
	//            ], 422);
	//        }

		$userSeq = $request->input('userSeq');
		$gameTypes = $request->input('gameTypes');
		$betType = $request->input('betType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');

		return view('games.detail2', compact('userSeq', 'gameTypes', 'betType', 'startDate', 'endDate'));
	}

	/**
	 * Show the game info.
	 *
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function detail3(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'gameTypes' => 'required',
			'betType' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

	//        if ($validator->fails()) {
	//            return response()->json([
	//                'error'    => true,
	//                'messages' => $validator->errors(),
	//            ], 422);
	//        }

		$userSeq = $request->input('userSeq');
		$gameTypes = $request->input('gameTypes');
		$betType = $request->input('betType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');

		return view('games.detail3', compact('userSeq', 'gameTypes', 'betType', 'startDate', 'endDate'));
	}

	/**
	 * Show detail game info. : popup
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function detailSearch(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$targetUser = null;
		$status = 200;
		$messages = ['데이터를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'gameTypes' => 'required',
			'betType' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$userSeq = $request->input('userSeq');
		$gameTypes = $request->input('gameTypes');
		$betType = $request->input('betType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		$selChan = (empty($request->input('selChannel'))) ? null : '100'.$request->input('selChannel');


		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		$channelInfo = null;
		$blackJackLogs = null;
		$baccaratLogs = null;
		$badugiLogs = null;
		$highLowLogs = null;
		$sevenPokerLogs = null;
		$texasHoldemLogs = null;

		$targetUser = AccountInfo::where('user_seq', $userSeq)->first();
		$gameTypes = json_decode($gameTypes, true);

	//        if (in_array(2, $gameTypes)){
	//            // blackJack
	//        }

		// allinOne
		$allGameLogs = [];

		// blackjack
		if (in_array('2', $gameTypes)) {
			$channels = Helper::getChannels(2, $betType, $selChan);
			if (count($channels) > 0) {
				$baccaratLogs = BlackjackResultLog::getResultlLog($userSeq, 2, $channels, $startDate, $endDate);

				foreach ($baccaratLogs as &$log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 2;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['data'] = $log;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// baccarat
		if (in_array('3', $gameTypes)) {
			$channels = Helper::getChannels(3, $betType, $selChan);
			if (count($channels) > 0) {
				$baccaratLogs = BaccaratResultLog::getResultlLog($userSeq, 3, $channels, $startDate, $endDate);

				foreach ($baccaratLogs as $log) {
					$gameLogs = [];

					$gameLogs['gameType'] = 3;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['data'] = $log;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// // badugi
		// if (in_array('4', $gameTypes)) {
		//     $channels = Helper::getChannels(4, $betType);
		//     if (count($channels) > 0) {
		//         $badugiLogs = GameResultLog::getResultlLog($userSeq, 4, $channels, $startDate, $endDate);

		//         foreach ($badugiLogs as $log) {
		//             $gameLogs = [];
		//             $gameLogs['gameType'] = 4;
		//             $gameLogs['roomId'] = $log->unique_num;
		//             $gameLogs['logDate'] = $log->log_date;
		//             $gameLogs['data'] = $log;
		//             array_push($allGameLogs, $gameLogs);
		//         }
		//     }
		// }

		// // highlow
		// if (in_array('5', $gameTypes)) {
		//     $channels = Helper::getChannels(5, $betType);
		//     if (count($channels) > 0) {
		//         $highLowLogs = HighLowResultLog::getResultlLog($userSeq, 5, $channels, $startDate, $endDate);

		//         foreach ($highLowLogs as $log) {
		//             $gameLogs = [];
		//             $gameLogs['gameType'] = 5;
		//             $gameLogs['roomId'] = $log->unique_num;
		//             $gameLogs['logDate'] = $log->log_date;
		//             $gameLogs['data'] = $log;
		//             array_push($allGameLogs, $gameLogs);
		//         }
		//     }
		// }

		// // 7poker
		// if (in_array('6', $gameTypes)) {
		//     $channels = Helper::getChannels(6, $betType);
		//     if (count($channels) > 0) {
		//         $sevenPokerLogs = GameResultLog::getResultlLog($userSeq, 6, $channels, $startDate, $endDate);

		//         foreach ($sevenPokerLogs as $log) {
		//             $gameLogs = [];
		//             $gameLogs['gameType'] = 6;
		//             $gameLogs['roomId'] = $log->unique_num;
		//             $gameLogs['logDate'] = $log->log_date;
		//             $gameLogs['data'] = $log;
		//             array_push($allGameLogs, $gameLogs);
		//         }
		//     }
		// }

		// texasholdem
		if (in_array('7', $gameTypes)) {
			$channels = Helper::getChannels(7, $betType, $selChan);
			if (count($channels) > 0) {
				$texasHoldemLogs = GameResultLog::getResultlLog($userSeq, 7, $channels, $startDate, $endDate);

				foreach ($texasHoldemLogs as &$log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 7;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['logDate'] = $log->log_date;
					$log->game_detail = "<button type='button' class='btn btn-sm btn-danger m-0' data-type='{$log->game_type}' data-toggle='modal' data-target='#game_detail' data-game-detail='{$log->unique_num}'>상세보기</button>";
					$gameLogs['data'] = $log;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// // Taisai
		// if (in_array('8', $gameTypes)) {
		//     $channels = Helper::getChannels(8, $betType);
		//     if (count($channels) > 0) {
		//         $taiSaiLogs = GameResultLog::getResultlLog($userSeq, 8, $channels, $startDate, $endDate);

		//         foreach ($taiSaiLogs as $log) {
		//             $gameLogs = [];
		//             $gameLogs['gameType'] = 8;
		//             $gameLogs['roomId'] = $log->unique_num;
		//             $gameLogs['logDate'] = $log->log_date;
		//             $gameLogs['data'] = $log;
		//             array_push($allGameLogs, $gameLogs);
		//         }
		//     }
		// }

		// // Omahaholdem
		// if (in_array('9', $gameTypes)) {
		//     $channels = Helper::getChannels(9, $betType);
		//     if (count($channels) > 0) {
		//         $texasHoldemLogs = GameResultLog::getResultlLog($userSeq, 9, $channels, $startDate, $endDate);

		//         foreach ($texasHoldemLogs as $log) {
		//             $gameLogs = [];
		//             $gameLogs['gameType'] = 9;
		//             $gameLogs['roomId'] = $log->unique_num;
		//             $gameLogs['logDate'] = $log->log_date;
		//             $gameLogs['data'] = $log;
		//             array_push($allGameLogs, $gameLogs);
		//         }
		//     }
		// }

		// BadugiHoldem
		if (in_array('11', $gameTypes)) {
			$channels = Helper::getChannels(11, $betType, $selChan);
			if (count($channels) > 0) {
				$badugiHoldemLogs = GameResultLog::getResultlLog($userSeq, 11, $channels, $startDate, $endDate);

				foreach ($badugiHoldemLogs as &$log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 11;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['logDate'] = $log->log_date;
					$log->game_detail = "<button type='button' class='btn btn-sm btn-danger m-0' data-type='{$log->game_type}' data-idx='{$log->unique_num}'>상세보기</button>";
					$gameLogs['data'] = $log;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// sort by logDate
		if (!empty($allGameLogs)) {
			$error = false;
			$status = 200;
			$messages = null;

			foreach ((array)$allGameLogs as $key => $value) {
				$sort[$key] = $value['logDate'];
			}
			array_multisort($sort, SORT_ASC, $allGameLogs);
		}

		return response()->json([
			'error' => $error,
			'targetUser' => $targetUser,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'allGameLogs' => $allGameLogs,
			'messages' => $messages
		], $status);

	}

	/**
	 * Show detail game info. : popup
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function detailExcel(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$targetUser = null;
		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'gameTypes' => 'required',
			'betType' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$userSeq = $request->input('userSeq');
		$gameTypes = $request->input('gameTypes');
		$betType = $request->input('betType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');

		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		$channelInfo = null;
		$blackJackLogs = null;
		$baccaratLogs = null;
		$badugiLogs = null;
		$highLowLogs = null;
		$sevenPokerLogs = null;
		$texasHoldemLogs = null;
		$taisaiLogs = null;
		$omahaHoldemLogs = null;

		$targetUser = AccountInfo::where('user_seq', $userSeq)->first();
		$gameTypes = json_decode($gameTypes, true);

		// allinOne
		$allGameLogs = [];

		// blackJack
		if (in_array(2, $gameTypes)){
			$channels = Helper::getChannels(2, $betType);
			if (count($channels) > 0) {
				$blackJackLogs = BlackjackResultLog::getResultlLog($userSeq, 2, $channels, $startDate, $endDate);

				foreach ($blackJackLogs as $log) {
					$game_result = 'Lose';
					if (intval($log->change_money) > 0) {
						$game_result = 'Win';
					} else if (intval($log->change_money) == 0) {
						$game_result = 'Push';
					}

					$gameLogs = [];
					$gameLogs['gameType'] = 2;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $game_result;
					$gameLogs['beforeMoney'] = $log->before_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->after_money;
					$gameLogs['totalBet'] = $log->total_bet;
					$gameLogs['pairBet'] = $log->pair_bet;
					$gameLogs['insuranceBet'] = $log->insurance_bet;
					$gameLogs['dealerGame'] = $log->dealer_game;
					$gameLogs['dealerCharge'] = 0;
					$gameLogs['game0'] = $log->game0;
					$gameLogs['game1'] = $log->game1;
					$gameLogs['game2'] = $log->game2;
					$gameLogs['game3'] = $log->game3;
					$gameLogs['leave'] = '0';
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// baccarat
		if (in_array('3', $gameTypes)) {
			$channels = Helper::getChannels(3, $betType);
			if (count($channels) > 0) {
				$baccaratLogs = BaccaratResultLog::getResultlLog($userSeq, 3, $channels, $startDate, $endDate);

				foreach ($baccaratLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 3;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->before_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->after_money;
					$gameLogs['betPlayer'] = $log->bet_player;
					$gameLogs['betBanker'] = $log->bet_banker;
					$gameLogs['betPPair'] = $log->bet_ppair;
					$gameLogs['betTie'] = $log->bet_tie;
					$gameLogs['betBPair'] = $log->bet_bpair;
					$gameLogs['dealerCharge'] = 0;
					$gameLogs['cardPlayer'] = $log->card_player;
					$gameLogs['cardBanker'] = $log->card_banker;
					$gameLogs['leave'] = $log->leave;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// // badugi
		// if (in_array('4', $gameTypes)) {
		//     $channels = Helper::getChannels(4, $betType);
		//     if (count($channels) > 0) {
		//         $badugiLogs = GameResultLog::getResultlLog($userSeq, 4, $channels, $startDate, $endDate);

		//         foreach ($badugiLogs as $log) {
		//             $gameLogs = [];
		//             $gameLogs['gameType'] = 4;
		//             $gameLogs['roomId'] = $log->unique_num;
		//             $gameLogs['channel'] = $log->channel;
		//             $gameLogs['logDate'] = $log->log_date;
		//             $gameLogs['userSeq'] = $log->user_seq;
		//             $gameLogs['nickname'] = $log->nickname;
		//             $gameLogs['gameResult'] = $log->game_result;
		//             $gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
		//             $gameLogs['changeMoney'] = $log->change_money;
		//             $gameLogs['afterMoney'] = $log->remain_game_money;
		//             $gameLogs['dealerCharge'] = $log->pay_dealer_charge;
		//             $gameLogs['cardList'] = $log->card_list;
		//             $gameLogs['madeList'] = $log->made_list;
		//             $gameLogs['made'] = $log->made;

		//             array_push($allGameLogs, $gameLogs);
		//         }
		//     }
		// }

		// highlow
		if (in_array('5', $gameTypes)) {
			$channels = Helper::getChannels(5, $betType);
			if (count($channels) > 0) {
				$highLowLogs = HighLowResultLog::getResultlLog($userSeq, 5, $channels, $startDate, $endDate);

				foreach ($highLowLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 5;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->remain_game_money;
					$gameLogs['dealerCharge'] = $log->dealer_charge;
					$gameLogs['cardList'] = $log->card_list;
					$gameLogs['highMadeList'] = $log->high_made_list;
					$gameLogs['lowMadeList'] = $log->low_made_list;
					$gameLogs['highMade'] = $log->high_made;
					$gameLogs['lowMade'] = $log->low_made;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// 7poker
		if (in_array('6', $gameTypes)) {
			$channels = Helper::getChannels(6, $betType);
			if (count($channels) > 0) {
				$sevenPokerLogs = GameResultLog::getResultlLog($userSeq, 6, $channels, $startDate, $endDate);

				foreach ($sevenPokerLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 6;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->remain_game_money;
					$gameLogs['dealerCharge'] = $log->dealer_charge;
					$gameLogs['cardList'] = $log->card_list;
					$gameLogs['madeList'] = $log->made_list;
					$gameLogs['made'] = $log->made;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// texasholdem
		if (in_array('7', $gameTypes)) {
			$channels = Helper::getChannels(7, $betType);
			if (count($channels) > 0) {
				$texasHoldemLogs = GameResultLog::getResultlLog($userSeq, 7, $channels, $startDate, $endDate);

				foreach ($texasHoldemLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 7;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->remain_game_money;
					$gameLogs['dealerCharge'] = $log->pay_dealer_charge;
					$gameLogs['cardList'] = $log->card_list;
					$gameLogs['madeList'] = $log->made_list;
					$gameLogs['made'] = $log->made;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// taisai
		if (in_array('8', $gameTypes)) {
			$channels = Helper::getChannels(8, $betType);
			if (count($channels) > 0) {
				$taisaiLogs = GameResultLog::getResultlLog($userSeq, 8, $channels, $startDate, $endDate);

				foreach ($taisaiLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 8;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->remain_game_money;
					$gameLogs['dealerCharge'] = $log->dealer_charge;
					$gameLogs['cardList'] = $log->card_list;
					$gameLogs['madeList'] = $log->made_list;
					$gameLogs['made'] = $log->made;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// omahaholdem
		if (in_array('9', $gameTypes)) {
			$channels = Helper::getChannels(9, $betType);
			if (count($channels) > 0) {
				$omahaHoldemLogs = GameResultLog::getResultlLog($userSeq, 9, $channels, $startDate, $endDate);

				foreach ($omahaHoldemLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 9;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->remain_game_money;
					$gameLogs['dealerCharge'] = $log->dealer_charge;
					$gameLogs['cardList'] = $log->card_list;
					$gameLogs['madeList'] = $log->made_list;
					$gameLogs['made'] = $log->made;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// badugiholdem
		if (in_array('11', $gameTypes)) {
			$channels = Helper::getChannels(11, $betType);
			if (count($channels) > 0) {
				$badugiHoldemLogs = GameResultLog::getResultlLog($userSeq, 11, $channels, $startDate, $endDate);

				foreach ($badugiHoldemLogs as $log) {
					$gameLogs = [];
					$gameLogs['gameType'] = 11;
					$gameLogs['roomId'] = $log->unique_num;
					$gameLogs['channel'] = $log->channel;
					$gameLogs['logDate'] = $log->log_date;
					$gameLogs['userSeq'] = $log->user_seq;
					$gameLogs['nickname'] = $log->nickname;
					$gameLogs['gameResult'] = $log->game_result;
					$gameLogs['beforeMoney'] = $log->remain_game_money - $log->change_money;
					$gameLogs['changeMoney'] = $log->change_money;
					$gameLogs['afterMoney'] = $log->remain_game_money;
					$gameLogs['dealerCharge'] = $log->dealer_charge;
					$gameLogs['cardList'] = $log->card_list;
					$gameLogs['madeList'] = $log->made_list;
					$gameLogs['made'] = $log->made;
					array_push($allGameLogs, $gameLogs);
				}
			}
		}

		// sort by logDate
		if (!empty($allGameLogs)) {
			$error = false;
			$status = 200;
			$messages = null;

			foreach ((array)$allGameLogs as $key => $value) {
				$sort[$key] = $value['logDate'];
			}
			array_multisort($sort, SORT_ASC, $allGameLogs);
		} else {
			$error = false;
			$status = 200;
			$messages = ['데이터가 없습니다.'];
			$allGameLogs = [];
		}

		return response()->json([
			'error' => $error,
			'targetUser' => $targetUser,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'allGameLogs' => $allGameLogs,
			'messages' => $messages
		], $status);

	}

	/**
	 * Show game packet logs. : popup
	 *
	 * @param Request $request
	 * @return Renderable
	 */
	public function packet(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'gameType' => 'required',
			'channel' => 'required',
			'logDate' => 'required',
			'roomId' => 'required',
		));

	//        if ($validator->fails()) {
	//            return response()->json([
	//                'error'    => true,
	//                'messages' => $validator->errors(),
	//            ], 422);
	//        }

		$gameType = $request->input('gameType');
		$channel = $request->input('channel');
		$logDate = $request->input('logDate');
		$roomId = $request->input('roomId');

		// logs
		$packetLogs = PacketLog::getPacketLogs($logDate, $gameType, $roomId);

		return view('games.packets', compact(
			'gameType', 'channel', 'roomId', 'packetLogs'
		));
	}

	/**
	 * Show game packet logs. : popup
	 *
	 * @param Request $request
	 * @return Renderable
	 */
	public function betting(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'gameType' => 'required',
			'channel' => 'required',
			'logDate' => 'required',
			'roomId' => 'required',
			'userSeq' => 'required',
		));

	//        if ($validator->fails()) {
	//            return response()->json([
	//                'error'    => true,
	//                'messages' => $validator->errors(),
	//            ], 422);
	//        }

		$gameType = $request->input('gameType');
		$channel = $request->input('channel');
		$logDate = $request->input('logDate');
		$roomId = $request->input('roomId');
		$userSeq = $request->input('userSeq');

		// logs
		$packetLogs = PacketLog::getUserPacketLogs($logDate, $gameType, $roomId, $userSeq);

		return view('games.betting', compact(
			'gameType', 'channel', 'roomId', 'packetLogs'
		));
	}

	/**
	 * Show the game payment.
	 *
	 * @return Renderable
	 */
	public function payment()
	{
		return view('games.payment');
	}

	/**
	 * Show the game paymentInfo.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function billing(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$billingInfo = [];
		$gameBilling = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			//'type' => 'required',
			//'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		$accountInfo = null;
		$orderId = null;

		if ($type != "" && $keyword != "") {
			// find user_seq & account info
			if ($type === 'nickname') {
				$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
			} else if ($type === 'userSeq') {
				$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
			} else if ($type === 'email') {
				$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
			} else if ($type === 'orderId')
				$orderId = $keyword;

			if (!is_null($accountInfo)) {
				$error = false;
				$status = 200;
				$messages = null;
				$itemNames = Item::getItemNames();
				// find member by user_seq
				$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
				$gameBilling = BillingInfo::getBillingLog($accountInfo->user_seq, $startDate, $endDate);
				foreach ($gameBilling as $log) {
					$billingLogs = [];
					$billingLogs['logDate'] = $log->update_date;
	//                    if ($log->is_danal > 0) {
	//                        $billingLogs['marketType'] = Helper::marketType(4);
	//                    } else {
	//                        $billingLogs['marketType'] = Helper::marketType($log->market_type);
	//                    }
					$billingLogs['marketType'] = Helper::marketType($log->market_type);
					$billingLogs['data'] = $log;
					array_push($billingInfo, $billingLogs);
				}
			} else {

				if (
					$orderId === null
					&& $accountInfo === null
				) {
					return response()->json([
						'error' => false,
						'messages' => $messages,
					], $status);
				}

				$error = false;
				$status = 200;
				$messages = null;
				$gameBilling = BillingInfo::getBillingLog(null, $startDate, $endDate, $orderId);

				foreach ($gameBilling as $log) {
					$billingLogs = [];
					$billingLogs['logDate'] = $log->update_date;
					$billingLogs['marketType'] = Helper::marketType($log->market_type);
					$billingLogs['data'] = $log;

					array_push($billingInfo, $billingLogs);
				}

			}
		} else { // 전체 유저 대상 검색

			$error = false;
			$status = 200;
			$messages = null;

			$gameBilling = BillingInfo::getBillingLogAll($startDate, $endDate);

			foreach ($gameBilling as $log) {
				$billingLogs = [];
				$billingLogs['logDate'] = $log->update_date;
				// $log->price = (strtr($log->price, ',', ''));

	//                if ($log->is_danal > 0) {
	//                    $billingLogs['marketType'] = Helper::marketType(4);
	//                } else {
	//                    $billingLogs['marketType'] = Helper::marketType($log->market_type);
	//                }
				$billingLogs['marketType'] = Helper::marketType($log->market_type);
				$billingLogs['data'] = $log;
				array_push($billingInfo, $billingLogs);
			}
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'billingInfo' => $billingInfo,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members gem.
	 *
	 * @return Renderable
	 */
	public function gem()
	{
		return view('games.gem');
	}

	/**
	 * Show the Gem Buy & Use Logs.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function gemLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$gemLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'type' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$userSeq = $request->input('userSeq');
		$logType = $request->input('type');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		// find user_seq & account info
		$accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$gemLogs = Gem::getGemLogs($accountInfo->user_seq, $logType, $startDate, $endDate);

			foreach ($gemLogs as $log) {
				$log->reason = Helper::reason($log->reason);
				if ($log->market_type > 0) {
					$log->reason = "[" . Helper::marketTypeKor($log->market_type) . "] " . $log->reason;
				}
			}
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'gemLogs' => $gemLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members vault.
	 *
	 * @return Renderable
	 */
	public function vault()
	{
		return view('games.vault');
	}

	/**
	 * Show the Gem Buy & Use Logs.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function vaultLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$gemLogs = null;
		$safeLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'searchType' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
		}

		$logType = $request->input('searchType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$safeLogs = SafeLog::getSafeLogs($accountInfo->user_seq, $logType, $startDate, $endDate);

		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'safeLogs' => $safeLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members posts.
	 *
	 * @return Renderable
	 */
	public function posts()
	{
		return view('games.posts');
	}

	/**
	 * Show the Presents List.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function presents(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$presents = null;
		$unreadCount = 0;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'searchType' => 'required',
			'itemType' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
		}

		$searchType = $request->input('searchType');
		$itemType = $request->input('itemType');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$unreadCount = collect(Present::getUnreadCount($accountInfo->user_seq))->first()->unreadCount;
			$presents = Present::getPresents($accountInfo->user_seq, $searchType, $itemType, $startDate, $endDate);
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'presents' => $presents,
			'unreadCount' => $unreadCount,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members goods.
	 *
	 * @return Renderable
	 */
	public function goods()
	{
		return view('games.goods');
	}

	/**
	 * Show the Goods List : Inventory.
	 *
	 * @return JsonResponse
	 */
	public function inventory(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$inventory = null;
		$loginLog = null;
		$adminLog = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');
		$from = $request->input('from');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
		}

		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			// inventory
			$inventory = Inventory::getInventory($accountInfo->user_seq, $startDate, $endDate);

			if ($from == "effect") {
				// find login log
				$loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();
				// last admin log
				$adminLog = AdminLog::where('user_seq', $accountInfo->user_seq)->orderby('id', 'DESC')->first();
			}
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'loginLog' => $loginLog,
			'adminLog' => $adminLog,
			'inventory' => $inventory,
			'messages' => $messages
		], $status);
	}


	/**
	 * Show the Goods List : Buy Log.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function buyLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$buyLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			if ($platform == '1') {
				$accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
			} else {
				$accountInfo = AccountInfo::where('login_type', $platform)->where('google_email', $keyword)->first();
			}
		}

		$startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
		$endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			// buy Logs
			$buyLogs = BuyLog::getBuyLogs($accountInfo->user_seq, $startDate, $endDate);
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'buyLogs' => $buyLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members limits.
	 *
	 * @return Renderable
	 */
	public function limits()
	{
		return view('games.limits');
	}

	/**
	 * Show the members rakeBack.
	 *
	 * @return Renderable
	 */
	public function rakeBack()
	{
		return view('games.rakeback');
	}

	/**
	 * Show the rakeBackLogs Logs.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function rakeBackLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$gemLogs = null;
		$rakeBackInLogs = null;
		$rakeBackOutLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			$accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
		}
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		if(strlen($startDate) == 10) $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
		if(strlen($endDate) == 10) $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$rakeBackInLogs = RakeBackLog::inLogs($accountInfo->user_seq, $startDate, $endDate);
			$rakeBackOutLogs = RakeBackLog::outLogs($accountInfo->user_seq, $startDate, $endDate);

		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'rakeBackInLogs' => $rakeBackInLogs,
			'rakeBackOutLogs' => $rakeBackOutLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members gold.
	 *
	 * @return Renderable
	 */
	public function gold()
	{
		return view('games.golds');
	}

	/**
	 * Show the goldLogs Logs.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function goldLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$goldLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			if ($platform == '1') {
				$accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
			} else {
				$accountInfo = AccountInfo::where('login_type', $platform)->where('google_email', $keyword)->first();
			}
		}

		$startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
		$endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$goldLogs = GoldLog::getLogs($accountInfo->user_seq, $startDate, $endDate);
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'goldLogs' => $goldLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the members exchange logs.
	 *
	 * @return Renderable
	 */
	public function exchange()
	{
		return view('games.exchange');
	}

	/**
	 * Show the exchange Logs.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function exchangeLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$exchangeLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'type' => 'required',
			'keyword' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$type = $request->input('type');
		$platform = $request->input('platform');
		$keyword = $request->input('keyword');

		// find user_seq & account info
		if ($type === 'nickname') {
			$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
		} else if ($type === 'userSeq') {
			$accountInfo = AccountInfo::where('user_seq', $keyword)->first();
		} else if ($type === 'email') {
			if ($platform == '1') {
				$accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
			} else {
				$accountInfo = AccountInfo::where('login_type', $platform)->where('google_email', $keyword)->first();
			}
		}

		$startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
		$endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			// find member by user_seq
			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$exchangeLogs = ExchangeLog::getExchangeLogByUser($accountInfo->user_seq, $startDate, $endDate);
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'exchangeLogs' => $exchangeLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * Show the friends Money info
	 *
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function friends(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
		));

		$userSeq = $request->input('userSeq');

		$nowTime = strtotime("Now");
		$weekAgoTime = strtotime("-7 days");

		$startDate = date('Y-m-d', $weekAgoTime) . " 00:00:00";
		$endDate = date('Y-m-d', $nowTime) . " 23:59:59";


		return view('games.friends', compact('userSeq', 'startDate', 'endDate'));
	}

	/**
	 * Show the goldLogs Logs.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function friendsLogs(Request $request)
	{
		$this->middleware('gzip');

		$error = true;
		$accountInfo = null;
		$userInfo = null;
		$loginLog = null;
		$friendsLogs = null;

		$status = 404;
		$messages = ['회원정보를 찾지 못했습니다.'];

		$validator = Validator::make($request->input(), array(
			'userSeq' => 'required',
			'startDate' => 'required',
			'endDate' => 'required',
			'from' => 'required',
		));

		if ($validator->fails()) {
			return response()->json([
				'error' => true,
				'messages' => $validator->errors(),
			], 422);
		}

		$userSeq = $request->input('userSeq');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');


		$accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

		if (!is_null($accountInfo)) {
			$error = false;
			$status = 200;
			$messages = null;

			$userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
			$loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();

			// find member by user_seq
			$friendsLogs = GameResultLog::getResultLogWithFriends($accountInfo->user_seq,  $startDate, $endDate);
		}

		return response()->json([
			'error' => $error,
			'accountInfo' => $accountInfo,
			'userInfo' => $userInfo,
			'loginLog' => $loginLog,
			'friendsLogs' => $friendsLogs,
			'messages' => $messages
		], $status);
	}

	/**
	 * reset daily lose chip change cnt
	 *
	 * @param  Request $request
	 * @return JsonResponse
	 */
	public function resetChangeCnt(Request $request)
	{
		$validator = Validator::make($request->input(), array(
			'user_seq' => 'required',
		));
		if ($validator->fails()) {
			return response()->json(['error' => true, 'messages' => 'invalid parameter'], 422);
		}
		$userSeq = $request->input('user_seq');
		$cert = Certification::find($userSeq);
		if(!$cert) return response()->json(['error' => true, 'messages' => 'Non-exsist user'], 422);
		if(!Helper::isTesterDi($cert->di)) return response()->json(['error' => true, 'messages' => 'Not available user'], 422);
		$loseChip = TodayLoseChip::find($cert->di);
		$loseChip->losechip_changecnt = 0;
		$loseChip->save();

		return response()->json(['error' => false, 'messages' => 'success'], 200);
	}

	/**
	 * 토너먼트 보상타입 출력
	 *
	 * @param  string $reward_type
	 * @return string
	 */
	private function prtTournamentRewardType($reward_type)
	{
		switch($reward_type) {
			case '1':
				return '개런티';
			case '2':
				return '논개런티';
			case '3':
				return '새틀라이트';
			case '4':
				return '챔피언십';
			case '5':
				return '챔피언십칩';
		}
	}

	/**
	 * 토너먼트 상태 출력
	 *
	 * @param  string $status
	 * @return string
	 */
	private function prtTournamentStatus($status)
	{
		switch($status) {
			case '1':
				return '등록중';
			case '2':
				return '등록마감';
			case '3':
				return '입장가능';
			case '4':
				return '진행중';
			case '5':
				return '종료';
			case '6':
				return '보상지급';
			case '99':
				return '취소';
			default:
				return '서버실행전';
		}
	}

	/**
	 * 토너먼트 채널 출력
	 *
	 * @param  string $money
	 * @return string
	 */
	private function prtTournamentChannel($money)
	{
		switch($money) {
			case '2016':
				return '칩';
			case '2018':
				return '골드';
		}
	}

	/**
	 * 토너먼트 조회
	 *
	 * @param  Request $request
	 * @return View
	 */
	public function tournament(Request $request)
	{
		$input = $request->all();
		$sdate = $input['search_start_date'] ?? Carbon::now()->format('Y-m-d');
		$edate = $input['search_end_date'] ?? Carbon::now()->format('Y-m-d');
		$sDateTime = $sdate . ' 00:00:00';
		$eDateTime = $edate . ' 23:59:59';
		$money = $input['money'] ?? '';

		$data = [
			'search' => [
				'search_start_date' => $sdate,
				'search_end_date' => $edate,
				'money' => $money,
			],
		];
		$param = http_build_query($data['search']);
		$builder = Tournament::whereBetween('start_date', [$sDateTime, $eDateTime]);
		if(!empty($money)) $builder = $builder->where('money', $money);
		$builder = $builder->orderBy('tid', 'DESC');
		$records = $builder->paginate(20);
		foreach($records as $row) {
			$row->reward_type = $this->prtTournamentRewardType($row->reward_type);
			$row->status = $this->prtTournamentStatus($row->status);
			$row->channel = $this->prtTournamentChannel($row->money);
		}
		$numberStart = $records->total() - (($records->currentPage() - 1) * $records->perPage());
		$records->withPath(route('games.tournament') . '?' . $param);
		$data['numberStart'] = $numberStart;
		$data['records'] = $records;

		return view('games.tournament', $data);
	}

	/**
	 * 토너먼트 순위
	 *
	 * @param  Request $request
	 * @return JsonResponse
	 */
	public function getTournamentRank(Request $request)
	{
		$tid = $request->input('tid');
		if(empty($tid)) {
			return response()->json(['error' => true, 'messages' => 'invalid parameter'], 200);
		}
		$exists = DB::connection('mysql')->table('information_schema.TABLES')
			->where('TABLE_SCHEMA', 'tournament')
			->where('TABLE_NAME', 'member_' . $tid)->exists();

		if($exists) {
			$records = DB::connection('mysql')->table('tournament.member_' . $tid . ' AS m')
				->leftJoin('tournament.log_reward AS r', function($join) use ($tid) {
					$join->on('m.user_seq', '=', 'r.user_seq')->where('r.tid', '=', $tid);
				})->selectRaw("m.user_seq, m.nickname, IFNULL(r.rank, '-') AS rank, IFNULL(r.item_id, '') AS item_id, IFNULL(r.item_ea, '') AS item_ea")
				->orderByRaw('rank IS NULL, rank ASC')->get();
		} else {
			$records = DB::connection('mysql')->table('tournament.backup_member AS m')
				->leftJoin('tournament.log_reward AS r', function($join) {
					$join->on('m.user_seq', '=', 'r.user_seq')->on('m.tid', '=', 'r.tid');
				})->selectRaw("m.user_seq, m.nickname, IFNULL(r.rank, '-') AS rank, IFNULL(r.item_id, '') AS item_id, IFNULL(r.item_ea, '') AS item_ea")
				->where('m.tid', $tid)->orderByRaw('rank IS NULL, rank ASC')->get();
		}

		return response()->json(['error' => false, 'messages' => 'success', 'records' => $records], 200);
	}

	public function gameDetail($gameType, $idx)
	{
		$httpCode = 200;
		$msg = '정상적으로 데이터를 불러오지 못했습니다.';

		$nameByType = ['2' => 'blackjack', '3' => 'baccarat', '7' => 'holdem', '11' => 'holdembadugi'];

		if (! isset($nameByType[$gameType]))
		{
			$httpCode = 403;
			$msg = '올바르지 못한 접근입니다.';

			return response()->json(['msg' => $msg], $httpCode);
		}

		$res = Http::withHeaders([
					'Authorization' => 'Bearer '.env('API_BEARER_TOKEN'),
					'Accept'		=> '*/*',
				])->get(implode('/',[env('API_URL').'/review', $nameByType[$gameType], $idx]));

		if ($res->failed())
		{
			$httpCode = 400;
			return response()->json(['msg' => $msg], $httpCode);
		}

		return response()->json(['html' => $res->body()], $httpCode);
	}
}
