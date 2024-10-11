<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\CMS\AdminLog;
use App\Model\Game\SlotUser;
use App\Model\Game\SlotInfo;

use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class SlotUserController extends Controller
{

	private $joinTypeNames = ['0' => '게스트', '1' => '구글', '2' => '유니티', '3' => '플랫폼'];

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
	 * Show the application dashboard.
	 *
	 * @return RedirectResponse|Redirector
	 */
	public function index()
	{
		$this->list();
	}



	private function permission()
	{
		$args = func_get_args();

		if (count($args) <= 0)
		{
			header('Location: /member/info');
			die();
		}

		$user = Auth::user();

		$status = 0;
		foreach ($args as $val)
		{
			if ($user->hasPermissionTo($val))
				$status++;
		}

		if ($status == 0)
		{
			header('Location: /member/info');
			die();
		}

		return true;
	}



	/**
	 * 유저 검색 페이지
	 *
	 *
	 */
	public function search(Request $request)
	{
		$this->permission('member', 'outer0');


		$input = $request->all();


		$data = array
		(
			'users'		=>	[],
			'type'		=>	empty($input['type']) ? '' : trim($input['type']),
			'keyword'	=>	empty($input['keyword']) ? '' : trim($input['keyword'])
		);


		if (!empty($data['keyword']) &&
			!empty($data['type']))
		{

			$data['users']		= SlotUser::getUsers($input['type'], $input['keyword']);

			foreach ($data['users'] as &$user)
			{
				$user->name = '';
				$user->join_site = $this->joinTypeNames[$user->join_site];
			}
		}

		return view('slotusers.list', $data);
	}



	/**
	 * 유저 상세 정보 페이지.
	 *
	 *
	 */
	public function detail(Request $request)
	{
		$this->permission('member', 'outer0');


		$input = $request->all();

		if (empty($input['id']))
		{
			header('Location: /slotusers/list');
			die();
		}


		$data = [];

        // 유저 기본정보 색인.
		$data['user'] = SlotUser::getUsers('userseq', $input['id'])[0];

		$data['user']->name = '';
		$data['user']->join_site = $this->joinTypeNames[$data['user']->join_site];

        // 유저 RTP 색인.
        $data['cols'] = [];
        $data['rtps'] = [];

        $res = SlotUser::getUserRtps($data['user']->user_seq);

        foreach($res as $key => $row) {
            if(!in_array($row->bet, $data['cols'])) array_push($data['cols'], $row->bet);

            $data['rtps'][$row->slot_id][$row->bet] = [
               'bet' => $row->bet,
               'range_bet' => $row->range_bet,
               'range_payout' => $row->range_payout,
               'rtp' => $row->rtp
            ];
        }

        asort($data['cols']);

        // 유저 버프 색인.
        $data['buffs'] = SlotUser::getUserBuffs($data['user']->user_seq);

        // 슬롯 목록 색인.
		$data['slots'] = SlotInfo::getSlotAll();

		return view('slotusers.detail', $data);
	}



	/**
	 * 초기화 처리.
	 *
	 *
	 */
	public function reset_rtp(Request $request)
	{
		$this->permission('member');


		$data = array
		(
			'result'	=>	0
		);


		$validator = Validator::make(
			$request->input(),
			array
			(
				'uid'			=>	'required',
				'slot_id'		=>	'required'
			));


		$result = SlotUser::resetUserRtp($request->input('uid'), $request->input('slot_id'));

		$redis = Redis::connection('redis_store')
			->del(implode('_', ['USER_SLOT_', $request->input('uid'), $request->input('slot_id')]));


		$data['result']	= 1;
		$data['msg']	= '초기화 되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 *
	 *
	 *
	 */
	public function set_buff(Request $request)
	{
		$this->permission('member', 'outer0');


		// 데이터 검증.
		$validator = Validator::make(
			$request->input(),
			array
			(
				'uid'			=>	'required',
				'slot_id'		=>	'required',
				'target'		=>	'required',
				'code'			=>	'required',
				'amount'		=>	'required',
				'expired_date'	=>	'required',
				'expired_time'	=>	'required'
			));


		// 리턴 데이터 정의.
		$data = array
		(
			'result'	=>	0
		);

        $beforeValue = SlotUser::getUserBuffsOne($request->input('uid'), $request->input('slot_id'), $request->input('target'), $request->input('code'));

		// 데이터 등록.
		$result = SlotUser::setUserBuff(
			$request->input('uid'),
			$request->input('slot_id'),
			$request->input('target'),
			$request->input('code'),
			$request->input('amount'),
			implode(' ', [$request->input('expired_date'), $request->input('expired_time').':00'])
			);

		$redis = Redis::connection('redis_store')
			->hdel('USER_BUFFS', implode('_', [$request->input('uid'), $request->input('slot_id')]));


		$data['result']	= 1;
		$data['msg']	= '등록 되었습니다.';

        // admin Log
        $accountInfo = AccountInfo::where('user_seq', $request->input('uid'))->first();
        $afterValue = SlotUser::getUserBuffsOne($request->input('uid'), $request->input('slot_id'), $request->input('target'), $request->input('code'));
        $adminUser = User::find(Auth::id());
        AdminLog::create([
            'type' => 'buff_edit',
            'menu' => 'slotuser',
            'action' => 'editBuff',
            'log_type' => 'admin',
            'params' => json_encode($request->post()),
            'reason' => Helper::adminLogType('admin'),
            'extra' => $request->input('amount'),
            'user_seq' => $accountInfo->user_seq,
            'nickname' => $accountInfo->nickname,
            'before_value' => json_encode($beforeValue),
            'after_value' => json_encode($afterValue),
            'before_state' => 0,
            'after_state' => 0,
            'admin_id' => $adminUser->id,
            'admin_name' => $adminUser->name,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);

		return response()->json($data, 200);
	}



	/**
	 * 버프 삭제 처리.
	 *
	 *
	 */
	public function drop_buff(Request $request)
	{
		$this->permission('member', 'outer0');


		// 데이터 검증.
		$validator = Validator::make(
			$request->input(),
			array
			(
				'uid'			=>	'required',
				'slot_id'		=>	'required',
				'target'		=>	'required',
				'code'			=>	'required'
			));


		// 리턴 데이터 정의.
		$data = array
		(
			'result'	=>	0
		);

        $beforeValue = SlotUser::getUserBuffsOne($request->input('uid'), $request->input('slot_id'), $request->input('target'), $request->input('code'));

		// 버프 삭제 처리.
		$result = SlotUser::dropUserBuff(
			$request->input('uid'),
			$request->input('slot_id'),
			$request->input('target'),
			$request->input('code')
			);

        $redis = Redis::connection('redis_store');
        $redisKey = 'USER_BUFFS';
        if($request->input('slot_id') == 'ALL') {
            $option = ['match' => $request->input('uid') . '_*'];
            $buffScan = $redis->hscan($redisKey, null, $option);
            if(!empty($buffScan)) {
                foreach($buffScan[1] as $idx => $row) {
                    $redis->hdel($redisKey, $idx);
                }
            }
        } else {
            $redis->hdel($redisKey, implode('_', [$request->input('uid'), $request->input('slot_id')]));
        }

		$data['result']	= 1;
		$data['msg']	= '삭제 되었습니다.';

        // admin Log
        $accountInfo = AccountInfo::where('user_seq', $request->input('uid'))->first();
        $adminUser = User::find(Auth::id());
        AdminLog::create([
            'type' => 'buff_drop',
            'menu' => 'slotuser',
            'action' => 'dropBuff',
            'log_type' => 'admin',
            'params' => json_encode($request->post()),
            'reason' => Helper::adminLogType('admin'),
            'extra' => 'DROP',
            'user_seq' => $accountInfo->user_seq,
            'nickname' => $accountInfo->nickname,
            'before_value' => json_encode($beforeValue),
            'after_value' => json_encode([]),
            'before_state' => 0,
            'after_state' => 0,
            'admin_id' => $adminUser->id,
            'admin_name' => $adminUser->name,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);

		return response()->json($data, 200);
	}

}

