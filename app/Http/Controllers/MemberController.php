<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\Account\BackupBanList;
use App\Model\Account\Certification;
use App\Model\Account\Friend;
use App\Model\Account\Member;
use App\Model\Account\MemberRecommend;
use App\Model\CMS\AdminLog;
use App\Model\Game\GameInfo;
use App\Model\Game\Inventory;
use App\Model\Log\BlackjackResultLog;
use App\Model\Log\LoginLog;
use App\Model\Log\NickLog;
use App\Model\Game\UserInfo;
use App\Model\Log\BaccaratResultLog;
use App\Model\Log\GameResultLog;
use App\Model\Log\HighlowResultLog;
use App\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;




class MemberController extends Controller
{
	private $permissions = ['member', 'outer0'];

    private $adminUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


	private function permission()
	{
		$args = func_get_args();

		if (count($args) <= 0)
			$args = $this->permissions;

		$user = Auth::user();

		$status = 0;
		foreach ($args as $val)
		{
			if ($user->hasPermissionTo($val))
				$status++;
		}

		if ($status == 0)
		{
			header('Location: /blank');
			die();
		}

		return true;
	}


    /**
     * Show members list.
     *
     * @return View
     */
    public function list(Request $request) : View
    {
		$this->permission();

        $search = [];
        $search['search_type'] = $request->input('search_type');
        $search['login_type'] = $request->input('login_type');
        $search['keyword'] = $request->input('keyword');
        $from = $request->input('from');

        // find user_seq & account info
        $where = [];
        if ($search['keyword'] !== null && $search['keyword'] !== '') {
            if ($search['search_type'] === 'nickname') {
                $where[] = ['A.nickname', '=', $search['keyword']];
            } else if ($search['search_type'] === 'userSeq') {
                $where[] = ['L.user_seq', '=', $search['keyword']];
            } else if ($search['search_type'] === 'email') {
                $where[] = ['A.login_type', '=', $search['login_type']];
                $where[] = ['A.account', '=', $search['keyword']];
            }

        }

        $memberList = AccountInfo::getMemberList($where);

        // foreach($memberList as $row) {
        //     $row->rec_mid = intval($row->rec_mid) == Helper::getMemberIdDonk9() ? '(동크나인)' : '';
        // }

        if ($search['search_type'] == '') {
            $search['search_type'] = 'nickname';
        }

        $page = $request->input('page');
        if(empty($page)) $page = 1;
        $data = [
            'search' => $search,
            'memberList' => $memberList,
            'page' => $page,
            'login_type_name' => ['0' => '게스트', '1' => '구글', '2' => '유니티', '3' => '플랫폼'],
        ];

        return view('members.list', $data);

    }

    /**
     * Show the members info.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Renderable|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function info(Request $request)
    {
		$this->permission();

        $userSeq = $request->get('user_seq');
        return view('members.info', ['userSeq' => $userSeq]);
    }

    /**
     * Search the members info for Ajax Call.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
		$this->permission();

        $this->middleware('gzip');

        $error = true;
        $accountInfo = null;
        $userInfo = null;
        $banInfo = null;
        $gameInfo = null;
        $loginLog = null;
        $adminLog = null;
        $gameLog = null;
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
        $from = $request->input('from');

        // find user_seq & account info
        if ($type === 'nickname') {
            //$accountInfo = AccountInfo::where('nickname', 'LIKE', $keyword.'%')->first();
            $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
        } else if ($type === 'userSeq') {
            $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
        }
         else if ($type === 'email') {
            $accountInfo = AccountInfo::where('account', $keyword)->where('login_type', $platform)->first();
        }

        $diMember = null;
        $isCert = 0;
        $is_donk9 = false;
        if (!is_null($accountInfo)) {
            // find member by user_seq
            $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
            if($userInfo) {
                $error = false;
                $status = 200;
                $messages = null;

                //보유 티켓이벤트
                $ticketSeed = Inventory::where('user_seq', $accountInfo->user_seq)
                    ->whereIn('item_id', Helper::getTicketSeedItemId())
                    ->where('is_delete', 0)->where('is_use', 0)->sum('item_ea');
                $ticketSeed = intval($ticketSeed);
                $userInfo->seed_ticket = $ticketSeed;

                // find ban list
                $banInfo = BackupBanList::where('user_seq', $accountInfo->user_seq)->get();

                // find login log
                $loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();

                //get di
                $cert = Certification::find($accountInfo->user_seq);

                //if($cert && Helper::isTesterDi($cert->di)) {
                if($cert) {
                    $diMember = Certification::getSameDiMember($cert->di);
                    if (!empty($cert->di))
                        $isCert = 1;
                }

                if ($from === 'edit') {
                    // find admin logs
                    $adminLogs = AdminLog::where('user_seq', $accountInfo->user_seq)->orderby('updated_at', 'DESC')->get();
                    $adminLog = [];
                    foreach ($adminLogs as $log) {
                        $log->logMenu = Helper::adminLogMenu($log->menu, $log->action);
                        $log['sort_date'] = $log->updated_at->format('Y-m-d H:i:s');
                        array_push($adminLog, $log);
                    }

                    // find nick Change log
                    $nickLog = NickLog::where('user_seq', $accountInfo->user_seq)->orderby('log_date', 'DESC')->get();
                    $last_index = count($adminLog);
                    foreach ($nickLog as $log) {
                        $adminLog[$last_index] = [
                            'type' => 'in_game',
                            'menu' => 'nick',
                            'action' => 'change',
                            'params' => '',
                            'reason' => '인게임 닉변경',
                            'logMenu' => '[인게임] 닉네임 변경',
                            'extra' => '이전 : ' . $log->_before . ',
                         변경 후 : ' . $log->_after,
                            'user_seq' => $log->user_seq,
                            'nickname' => $log->_after,
                            'before_value' => $log->_before,
                            'after_value' => $log->_after,
                            'before_state' => 0,
                            'after_state' => 0,
                            'admin_id' => -1,
                            'admin_name' => '',
                            'created_at' => $log->log_date,
                            'updated_at' => $log->log_date,
                            'sort_date' => $log->log_date,
                        ];
                    }

                    // sort by logDate
                    if (count($adminLog) > 0) {
                        foreach ($adminLog as $key => $value) {
                            $sort[$key] = $value['sort_date'];
                        }
                        array_multisort($sort, SORT_DESC, $adminLog);
                    }
                } else {
                    $donk9MemberId = Helper::getMemberIdDonk9();
                    $platformMember = Member::where('user_seq', $accountInfo->user_seq)->first();
                    if($platformMember) {
                        $memberRecommend = MemberRecommend::find($platformMember->id);
                        if($memberRecommend) {
                            if(intval($memberRecommend->rec_mid) == $donk9MemberId) $is_donk9 = true;
                        }
                    }

                    // find game info
                    $gameInfo = GameInfo::where('user_seq', $accountInfo->user_seq)->get();

                    // find admin logs
                    $adminLog = AdminLog::where('user_seq', $accountInfo->user_seq)->orderby('id', 'DESC')->first();

                    // find game logs
                    $gameLog['blackjack']['game_type'] = 2;
                    $gameLog['blackjack'] = BlackjackResultLog::where('user_seq', $accountInfo->user_seq)
                        ->orderby('log_date', 'DESC')->first();
                    $gameLog['baccarat']['game_type'] = 3;
                    $gameLog['baccarat'] = BaccaratResultLog::where('user_seq', $accountInfo->user_seq)
                        ->orderby('log_date', 'DESC')->first();
                    $gameLog['badugi'] = GameResultLog::getLatestLog( 4, $accountInfo->user_seq);
                    $gameLog['highlow'] = null;
                    $gameLog['sevenpoker'] = GameResultLog::getLatestLog( 6, $accountInfo->user_seq);
                    $gameLog['texasholdem'] = GameResultLog::getLatestLog( 7, $accountInfo->user_seq);
                    $gameLog['omaha'] = GameResultLog::getLatestLog( 9, $accountInfo->user_seq);
                    $gameLog['badugiholdem'] = GameResultLog::getLatestLog( 11, $accountInfo->user_seq);
                }
            }
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'banInfo' => $banInfo,
            'gameInfo' => $gameInfo,
            'loginLog' => $loginLog,
            'adminLog' => $adminLog,
            'gameLog' => $gameLog,
            'messages' => $messages,
            'diMember' => $diMember,
            'is_cert' => $isCert,
            'is_donk9' => $is_donk9,
            'loginTypeName' => [ '0' => '게스트', '1' => '구글', '2' => '유니티', '3' => '플랫폼'],

        ], $status);
    }

    /**
     * Show the members edit.
     *
     * @return Renderable
     */
    public function edit()
    {
		$this->permission();

        return view('members.edit');
    }

    /**
     * Update the members info for Ajax Call.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
		$this->permission();

        $error = true;
        $accountInfo = null;
        $adminLog = null;
        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'user_seq' => 'required',
            'nickname' => 'required',
            'state' => 'required',
            'reason' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['user_seq'] = $request->input('user_seq');
        $params['nickname'] = $request->input('nickname');
        $params['state'] = $request->input('state');
        $params['reason'] = $request->input('reason');
        $extra = '';
        $before_nick = '';
        $before_state = 0;

        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $params['user_seq'])->first();

        if (!is_null($accountInfo)) {
            $checkDupNick = false;
            // check nick change
            if ($accountInfo->nickname !== $params['nickname']) {
                // check existing nickname
                if (AccountInfo::where(DB::raw('BINARY `nickname`'), $params['nickname'])->exists()) {
                    $checkDupNick = true;
                }
            }

            if ($checkDupNick === true) {
                $error = true;
                $status = 422;
                $messages = ['이미 사용 중인 닉네임 입니다.'];
            } else {
                $error = false;
                $status = 200;
                $before_nick = $accountInfo->nickname;
                $before_state = $accountInfo->user_state;
            }
        }

        if ($error === true) {
            return response()->json([
                'error' => $error,
                'messages' => $messages,
            ], 422);
        }

        // nickname update
        if ($accountInfo->nickname !== $params['nickname']) {
            $extra .= '이전 닉네임 : ' . $before_nick;

            $accountInfo->nickname = $params['nickname'];

            Friend::where('friend_seq', $accountInfo->user_seq)
                ->update(['friend_nickname' => $params['nickname']]);

            UserInfo::where('user_seq', $accountInfo->user_seq)
                ->update(['nickname' => $params['nickname'], 'update_date' => DB::raw('now()')]);

            // nick log
            NickLog::create([
                'user_seq' => $accountInfo->user_seq,
                'item_seq' => -1,
                '_before' => $before_nick,
                '_after' => $params['nickname'],
                'log_date' => DB::raw('now()')
            ]);
        }

        // state update
        if ($accountInfo->user_state !== $params['state']) {
            $extra .= ', 이전 상태 : ' . $before_state;
            $accountInfo->user_state = $params['state'];
        }
        $accountInfo->save();

        //개발자 회원탈퇴는 즉시
        if(intval($params['state']) === 2) {
            //get di
            $cert = Certification::find($accountInfo->user_seq);
            $diMember = null;
            if($cert && Helper::isTesterDi($cert->di)) {
                $conn = DB::connection('mysql');
                $conn->table('accountdb.friend')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('accountdb.friend')->where('friend_seq', $accountInfo->user_seq)->delete();
                $conn->table('accountdb.certification')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.attendance_event_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.avatar')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.avatar_collection')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.billing_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.free_charge_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.free_charge_info_admob')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.game_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.inventory')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.mission')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.post')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.present')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.purchase_event_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.user_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.club_member_req')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('gamedb.club_member')->where('user_seq', $accountInfo->user_seq)->delete();
                $conn->table('accountdb.account_info')->where('user_seq', $accountInfo->user_seq)->delete();
                $member = $conn->table('auth_platform.member')->where('user_seq', $accountInfo->user_seq)->first();
                $conn->table('auth_platform.member_goods')->where('member_id', $member->id)->delete();
                $conn->table('auth_platform.member_recommend')->where('rec_mid', $member->id)->update(['rec_mid' => 0]);
                $conn->table('auth_platform.member_recommend')->where('mid', $member->id)->delete();
                $member = null;
                $conn->table('auth_platform.member')->where('user_seq', $accountInfo->user_seq)->delete();
            }
        }

        // admin log
        $this->adminUser = User::find(Auth::id());
        AdminLog::create([
            'type' => 'member_edit',
            'menu' => 'member',
            'action' => 'edit',
            'log_type' => 'admin',
            'params' => json_encode($params),
            'reason' => $params['reason'],
            'extra' => $extra,
            'user_seq' => $accountInfo->user_seq,
            'nickname' => $params['nickname'],
            'before_state' => $before_state,
            'after_state' => $params['state'],
            'admin_id' => $this->adminUser->id,
            'admin_name' => $this->adminUser->name,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);

        $messages = '유저의 정보가 변경되었습니다.';

        return response()->json([
            'error' => $error,
            'messages' => $messages,
            'params' => $params,
        ], $status);
    }

    /**
     * check duplicate Nickname for Ajax Call.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkNickname(Request $request)
    {
		$this->permission();

        $error = true;
        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'nickname' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['user_seq'] = $request->input('userSeq');
        $params['nickname'] = $request->input('nickname');
        $before_nick = '';

        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $params['user_seq'])->first();

        if (!is_null($accountInfo)) {
            $checkDupNick = false;
            // check existing nickname
            if (AccountInfo::where(DB::raw('BINARY `nickname`'), $params['nickname'])->exists()) {
                $checkDupNick = true;
            }

            if ($checkDupNick === true) {
                $error = true;
                $status = 422;
                $messages = ['이미 사용 중인 닉네임 입니다.'];
            } else {
                $error = false;
                $status = 200;
                $messages = ['사용가능한 닉네임 입니다.'];
            }
        }

        return response()->json([
            'error' => $error,
            'messages' => $messages
        ], $status);

    }

    /**
     * Update the members info for Ajax Call.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forceClose(Request $request)
    {
		$this->permission();

        $error = true;
        $accountInfo = null;
        $adminLog = null;
        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];
        $banState = 4;
        $banMessage = "운영툴 강제종료";

        $validator = Validator::make($request->input(), array(
            'user_seq' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['user_seq'] = $request->input('user_seq');

        if (isset($request['type']) && $request['type'] != 4) {
            $banState = 3;
            $banMessage = "운영툴 강제종료[정지]";
        }

        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $params['user_seq'])->first();

        if (!is_null($accountInfo)) {

            $banChannel = 'ban_cs';
            if ($banState == 3) {
                $banChannel = 'ban';
            }
            // redis pub
            Redis::publish($banChannel, $accountInfo->user_seq);

            // backup ban list
            $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
            BackupBanList::updateOrCreate(
                [
                    'user_seq' => $accountInfo->user_seq,
                ],
                [
                    'account' => $accountInfo->google_email,
                    'nickname' => $userInfo->nickname,
                    'chip' => $userInfo->chip,
                    'safe_chip' => $userInfo->safe_chip,
                    'gold' => $userInfo->gold,
                    'safe_gold' => $userInfo->safe_gold,
                    'gem' => $userInfo->gem,
                    'event_gem' => $userInfo->gem_event,
                    'comment' => $banMessage,
                    'date' => DB::raw('now()'),
                ]
            );

            $error = false;
            $status = 200;
        }

        if ($error === true) {
            return response()->json([
                'error' => $error,
                'messages' => $messages,
            ], 422);
        }

        // admin log
        $this->adminUser = User::find(Auth::id());
        AdminLog::create([
            'type' => 'member_out',
            'menu' => 'member',
            'action' => 'forceOut',
            'log_type' => 'admin',
            'params' => json_encode($params),
            'reason' => '운영툴 강제종료',
            'extra' => '',
            'user_seq' => $accountInfo->user_seq,
            'nickname' => $accountInfo->nickname,
            'before_state' => $accountInfo->user_state,
            'after_state' => $banState,
            'admin_id' => $this->adminUser->id,
            'admin_name' => $this->adminUser->name,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);

        $messages = '유저의 정보가 변경되었습니다.';

        return response()->json([
            'error' => $error,
            'messages' => $messages
        ], $status);
    }

    /**
     * 하루 가입 유저
     *
     * @return \Illuminate\View\View
     */
    public function oneday()
    {
        $this->permission();

        return view('members.oneday');
    }

    /**
     * 하루 가입 유저 리스트 for Ajax Call.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneday(Request $request)
    {
        $this->permission();

        $keyword = $request->input('keyword');
        $searchType = $request->input('type');
        $regDate = $request->input('reg_date') ?? Carbon::now()->toDateString();
        $referrer = $request->input('referrer');
        if(empty($keyword)) $searchType = $keyword = null;
        $page = $request->input('page') ?? 1;
        $list = 20;

        $members = AccountInfo::getMemberListOneDay($searchType, $keyword, $regDate, $referrer, $list, $page);

        return response()->json(['data' => $members], 200);
    }

    /**
     * 관리자 di 삭제
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delDi(Request $request) : JsonResponse
    {
        $this->permission();

        $result = true;
        $userSeq = $request->input('user_seq');

        Certification::where('user_seq', $userSeq)->delete();
        return response()->json(['result' => $result, 'message' => '인증 상태변경 성공!'], 200);

    }

    /**
     * 추천인 아이디별 가입자
     *
     * @return \Illuminate\View\View
     */
    public function referrer()
    {
        $this->permission();

        return view('members.referrer');
    }

    /**
     * 추천인 아이디별 가입자 리스트 for Ajax Call.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReferrer(Request $request)
    {
        $this->permission();

        $referrerId = $request->input('referrer_id');
        $page = $request->input('page') ?? 1;
        $list = 20;
        if(empty($referrerId)) return response()->json(['data' => [
            'record_cnt' => 0,
            'records' => null,
            'pagination' => Helper::getPagination($list, $page, 10, 0),
            ]], 200);

        $referrers = AccountInfo::getMemberByReferrer($referrerId, $list, $page);
        return response()->json(['data' => $referrers], 200);
    }

    public function friend(Request $request)
    {
        $this->permission();
        $search = [];
        $search['search_type'] = $request->input('search_type') ?? 'nickname';
        $search['login_type'] = $request->input('login_type');
        $search['keyword'] = $request->input('keyword');
        $page = $request->input('page') ?? 1;
        $where = [];
        if(!empty($search['keyword'])) {
            if($search['search_type'] === 'nickname') {
                $where[] = ['A.nickname', $search['keyword']];
            } elseif($search['search_type'] === 'email') {

                $where[] = ['A.login_type', $search['login_type']];
                $where[] = ['A.account', $search['keyword']];
            }

            if($search['search_type'] === 'userSeq') {
                $where = [];
                $where[] = ['F.user_seq', $search['keyword']];
            }
            $friends_i = DB::connection('mysql')->table('accountdb.friend AS F')
                ->join('accountdb.account_info AS A', 'F.user_seq', '=', 'A.user_seq')
                ->join('accountdb.account_info AS N', 'F.friend_seq', '=', 'N.user_seq')
                ->select('F.friend_seq', 'N.nickname', 'F.update_date')
                ->where($where)->get();
            if($search['search_type'] === 'userSeq') {
                $where = [];
                $where[] = ['F.friend_seq', $search['keyword']];
            }
            $friends_me = DB::connection('mysql')->table('accountdb.friend AS F')
                ->join('accountdb.account_info AS A', 'F.friend_seq', '=', 'A.user_seq')
                ->join('accountdb.account_info AS N', 'F.user_seq', '=', 'N.user_seq')
                ->select('F.user_seq', 'N.nickname', 'F.update_date')
                ->where($where)->get();
        } else {
            $friends_i = [];
            $friends_me = [];
        }

        $data = [
            'search' => $search,
            'friends_i' => $friends_i,
            'friends_me' => $friends_me,
        ];

        return view('members.friend', $data);
    }



	public function certification($user_seq, $type)
	{
        try {

            $this->permission();

            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? true : false;

            $httpCode		        = 200;
            $result             = true;
            $message            = '인증상태 변경완료!';
            $params['user_seq'] = $user_seq;

            if ($type === 'pass') {
                $params['di'] = md5($user_seq.time());
                $params['date'] = date('Y-m-d H:i:s');
                Certification::upsert($params);
            } elseif ($type === 'cancel') {
                Certification::where('user_seq', $user_seq)->delete();
            } else {
                $httpCode = 400;
                $result = false;
                throw new Exception();
            }

            if (! $isAjax)
                echo    '<script>
                            window.onload = () => window.history.back();
                        </script>';
        } finally {

            DB::disconnect('mysql');

            if ($isAjax)
                return response()->json(['result' => $result, 'message' => $message], $httpCode);

            http_response_code($httpCode);
            exit();
        }




    }
}
