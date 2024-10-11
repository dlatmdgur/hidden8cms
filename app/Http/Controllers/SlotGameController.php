<?php

namespace App\Http\Controllers;

use App\Model\Game\SlotInfo;

use App\Model\Game\WebviewSlot;
use App\Model\Game\WebviewExternal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class SlotGameController extends Controller
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
	 * Show the application dashboard.
	 *
	 * @return RedirectResponse|Redirector
	 */
	public function index()
	{
		// check permission
		$user = Auth::user();
		if (!$user->hasPermissionTo('member') && $user->hasPermissionTo('exchange'))
		{
			return redirect('/statistics/exchange');
		}
		else
		{
			if (!$user->hasPermissionTo('member'))
				return redirect('/blank');
			else
				return redirect('/member/info');
		}

		//return view('dashboard');
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
	 * 슬롯 목록 출력.
	 *
	 *
	 */
	public function slots()
	{
		$this->permission('maintenance', 'permission');


		$data = array ();


		$data['slot_host']	= env('SLOT_GAME_HOST');
		$data['slots']		= SlotInfo::getSlots();


		return view('slotgames.slots', $data);
	}


	/**
	 * 슬롯 정보 추가/업데이트
	 *
	 *
	 */
	public function slot_set(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'		=>	'required',
				'slot_name'		=>	'required',
				'slot_type'		=>	'required',
				'slot_group'	=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


//		$data['result']	= 1;
//		$data['msg']	= '브레이크.';
//
//		return response()->json($data, 200);


		$params = array ();

		$params['slot_id']				= $request->input('slot_id');
		$params['slot_name']			= $request->input('slot_name');
		$params['slot_type']			= $request->input('slot_type');
		$params['sorted']				= $request->input('sorted');
		$params['slot_group']			= $request->input('slot_group');
		$params['group_sorted']			= $request->input('group_sorted');
		$params['opened'] 				= empty($request->input('opened')) ? 0 : $request->input('opened');
		$params['open_level']			= empty($request->input('open_level')) ? 0 : $request->input('open_level');

		$params['badge_new']			= empty($request->input('badge_new')) ? 0 : $request->input('badge_new');
		$params['badge_jackpot']		= empty($request->input('badge_jackpot')) ? 0 : $request->input('badge_jackpot');

		$result = SlotInfo::setSlot($params);


		$data['result']	= 1;
		$data['msg']	= '등록/변경되었습니다.';

		return response()->json($data, 200);
	}


	/**
	 * 슬롯 정보 삭제
	 *
	 *
	 */
	public function slot_drop(Request $request)
	{
		$this->permission('master');


		$data = array
		(
			'result'	=>	0
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required'
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfo::dropSlot($request->input('slot_id'));


		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}


	/**
	 * 슬롯 확률 변경 처리.
	 *
	 *
	 */
	public function slot_probability(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0
		);


		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required',
				'ver'		=>	'required'
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfo::setSlotActive(
			$request->input('slot_id'),
			$request->input('ver')
			);

		if (!$result)
		{
			$data['msg'] = '확률을 변경할 수 없습니다.';
			return response()->json($data, 200);
		}


		$data['result']	= 1;
		$data['msg']	= '확률이 변경되었습니다.';

		$this->reset();
		return response()->json($data, 200);

	}


	/**
	 * 슬롯 게임데이터 출력.
	 *
	 * @param string $slot_id 슬롯 ID
	 */
	public function gamedata(Request $request, $slot_id='', $ver='')
	{
		if (!$request->ajax())
			$this->permission('master');
		else
			$this->permission('maintenance', 'permission');


		$data = array ();


		$data['slot_host']	= env('SLOT_GAME_HOST');
		$data['slot_id']	= trim($slot_id);
		$data['data']		= SlotInfo::getGameData($slot_id, $ver);


		if ($request->ajax())
		{
			foreach ($data['data'] as $key => $row)
			{
				$row->game_data = json_decode($row->game_data);
				$data['data'][$key] = $row;
			}

			return response()->json($data, 200);
		}
		else
			return view('slotgames.gamedata', $data);
	}


	/**
	 * 슬롯 게임데이터 추가/업데이트
	 *
	 *
	 */
	public function gamedata_set(Request $request)
	{
		$this->permission('master');


		$data = array
		(
			'result'	=>	0
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required',
				'ver'		=>	'required',
				'env'		=>	'required',
				'note'		=>	'required',
				'game_data'	=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$params = array ();

		$params['slot_id']			= $request->input('slot_id');
		$params['ver']				= $request->input('ver');
		$params['env']				= $request->input('env');

		$params['game_data']		= $request->input('game_data');

		$params['note']				= $request->input('note');

		$params['active']			= empty($request->input('active')) ? 0 : $request->input('active');
		$params['alt']				= empty($request->input('alt')) ? 0 : $request->input('alt');


		$result = SlotInfo::setGameData($params);


		$data['result']	= 1;
		$data['msg']	= '등록/변경되었습니다.';

		return response()->json($data, 200);
	}


	/**
	 * 슬롯 게임데이터 삭제
	 *
	 *
	 */
	public function gamedata_drop(Request $request)
	{
		$this->permission('master');


		$data = array
		(
			'result'	=>	0
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required',
				'ver'		=>	'required',
				'env'		=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfo::dropGameData(
			$request->input('slot_id'),
			$request->input('ver'),
			$request->input('env')
			);


		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}


	/**
	 * UID 등록 처리.
	 *
	 *
	 */
	public function uidset(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0
		);


		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required',
				'ver'		=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		// 사용할 데이터 정의.
		$params = array ();
		$params['slot_id']	= $request->input('slot_id');
		$params['ver']		= $request->input('ver');
		$params['env']		= 'live';


		// 저장되어 있는 게임데이터 가져오기.
		$game = SlotInfo::getGameData(
			$params['slot_id'],
			$params['ver']
			)[0];

		// 기존 게임데이터에 UID 갱신.
		$game = json_decode($game->game_data, true);

		for ($i=0; $i < 2; $i++)
		{
			if (empty($game['alts']))
				$game['alts'] = array ();

			if (empty($game['alts'][$i]))
				$game['alts'][$i] = array
				(
					'alt_id'				=>	'base'.($i == 0 ? '' : '_f'),
					'chance'				=>	1,
					'duration'				=>	4,
					'condition'				=>	array
					(
						'uids'				=>	array (),
						'normal_spin_only'	=>	true,
						'comment'			=>	($i == 0 ? 'normal' : 'free').' spin base'
					)
				);


			$game['alts'][$i]['condition']['uids'] = empty($request->post('uids')) ? array () : $request->post('uids');
		}


		$params['game_data'] = preg_replace('/^(  +?)\\1(?=[^ ])/m', '$1', json_encode($game, JSON_PRETTY_PRINT));


		// DB에 정보 기록.
		$result = SlotInfo::setGameData($params);


		$data['result']	= 1;
		$data['msg']	= 'UID값이 업데이트 되었습니다.';

		return response()->json($data, 200);
	}


	/**
	 * 슬롯 배팅정보 출력.
	 *
	 * @param string $slot_id 슬롯 ID
	 */
	public function betinfo($slot_id='')
	{
		$this->permission('master');


		$data = array ();


		$data['slot_host']	= env('SLOT_GAME_HOST');
		$data['slot_id']	= trim($slot_id);
		$data['data']		= SlotInfo::getBetInfo($slot_id);

		foreach ($data['data'] as $key => $row)
		{
			$row->bet_rate_array = explode(',', $row->bet_rate_array);
			$data['data'][$key] = $row;
		}


		return view('slotgames.betinfo', $data);
	}


	/**
	 * 슬롯 배팅정보 추가/업데이트
	 *
	 *
	 */
	public function betinfo_set(Request $request)
	{
		$this->permission('master');


		$data = array
		(
			'result'	=>	0
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required',
				'room'		=>	'required',
				'bet_min'	=>	'required',
				'bet_max'	=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$params = array ();

		$params['slot_id']			= $request->input('slot_id');
		$params['room']				= $request->input('room');

		$params['vip_point']		= empty($request->input('vip_point')) ? 0 : $request->input('vip_point');
		$params['lv_condition']		= empty($request->input('lv_condition')) ? 0 : $request->input('lv_condition');

		$params['bet_min']			= intval($request->input('bet_min'));
		$params['bet_max']			= intval($request->input('bet_max'));

		$params['bet_rate_array']	= [];
		foreach ($request->input('rate') as $val)
		{
			if ($val != null)
				array_push($params['bet_rate_array'], $val);
		}

		$params['bet_rate_array']	= implode(',', $params['bet_rate_array']);

		$result = SlotInfo::setBetInfo($params);


		$data['result']	= 1;
		$data['msg']	= '등록/변경되었습니다.';

		return response()->json($data, 200);
	}


	/**
	 * 슬롯 배팅정보 삭제
	 *
	 *
	 */
	public function betinfo_drop(Request $request)
	{
		$this->permission('master');


		$data = array
		(
			'result'	=>	0
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'slot_id'	=>	'required',
				'room'		=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfo::dropBetInfo(
			$request->input('slot_id'),
			$request->input('room')
			);


		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}



	public function reset()
	{
		$redis = Redis::connection('redis_slots')
			->publish('ADMIN_COMMAND', json_encode(array('cmd' => 'refereload', 'target' => 'old_slot')));

		$data['result']	= 1;
		$data['msg']	= '적용되었습니다.';

		return response()->json($data, 200);
	}

	/**
	 * 웹뷰슬롯 ON/OFF
	 *
	 * @return View
	 */
	public function wv_slot() : View
	{
		$this->permission('maintenance', 'permission');

		$slots =  WebviewSlot::orderBy('view_open', 'DESC')->orderBy('sorted', 'ASC')->orderBy('provider', 'ASC')->get()->toArray();

		$data['records'] = [];
		$data['externals'] = [];

		foreach ($slots as $row)
		{
			switch ($row['provider'])
			{
				case 'hidden8':
					$data['records'][] = $row;
					break;
				default:
					$data['externals'][] = $row;
			}

			unset($row);
		}

		return view('slotgames.wv_slot', $data);
	}

	/**
	 * 웹뷰슬롯 추가/업데이트
	 *
	 * @param  Request $request
	 * @return JsonResponse
	 */
	public function wv_slot_set(Request $request) : JsonResponse
	{
		$this->permission('maintenance', 'permission');

		//
		//초기값 설정
		//

		// 리턴 메시지
		$data = ['result' => 999, 'msg' => '등록/변경에 실패하였습니다.'];

		$providers = ['hidden8', 'bgaming'];

		$validator = Validator::make($request->input(), [
			'slot_id' => 'required|bail',
			'name_kr' => 'required|bail',
		]);

		if($validator->fails()) {
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$params = [];
		$params['provider']		= $request->input('provider');
		$params['slot_id']		= $request->input('slot_id');
		$params['thumbnail']	= $request->input('thumbnail') ?? '';
		$params['name_kr']		= $request->input('name_kr');
		$params['name_en']		= $request->input('name_en');
		$params['is_long']		= $request->input('is_long') ?? 0;
		$params['is_spine']		= $request->input('is_spine') ?? 0;
		$params['sorted'] 		= $request->input('sorted') ?? 999;
		$params['status']		= $request->input('status') ?? 0;
		$params['view_open']	= $request->input('view_open') ?? 0;

		//트랜잭션
		DB::beginTransaction();

		if (in_array($params['provider'], $providers))
		{
			$ustWebviewSlot = WebviewSlot::upsert('external_slotlist', $params);

			if ($ustWebviewSlot < 1)
			{
				DB::rollback();
				return response()->json($data, 200);
			}

			$params['corp'] = $params['provider'];
			unset($params['provider'], $params['is_long'], $params['is_spine']);

			// 2024-10-02 근영님 요청 히든8 슬롯 업데이트의 경우는,  auth_slot도 같이 업데이트해줘야한다고하심
			$updAuthSlot =	WebviewSlot::updateAuthSlots($params, ['corp', 'slot_id']);

			if ($params['status'] === '0')
			{
				$storeServ 		= Redis::connection('redis_store');
				$connUids 		= $storeServ->smembers('ROOM_SLOT_'.$params['slot_id']);
				$slotServ 		= Redis::connection('redis_slots');

				foreach ($connUids as $uid)
					$slotServ->publish('ADMIN_COMMAND', json_encode(['cmd' => 'kick', 'uid' => $uid]));
			}

		}

		$data['result'] = 0;
		$data['msg']	= '등록/변경에 성공하였습니다.';

		//커밋
		DB::commit();

		return response()->json($data, 200);

	}

	/**
	 * 웹뷰슬롯 삭제
	 *
	 * @param  Request $request
	 * @return JsonResponse
	 */
	public function wv_slot_drop(Request $request) : JsonResponse
	{
		$this->permission('master');

		$data = ['result' => 0];

		$validator = Validator::make(
			$request->input(),
			[
				'id' => 'required',
				'corp' => 'required'
			]);

		if($validator->fails()) {
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}

		if ($request->input('corp') == 'superwin')
		{
			$slot = WebviewSlot::find($request->input('id'));
			$redis_store = Redis::connection('redis_store');
			$uids = $redis_store->smembers('ROOM_SLOT_' . $slot->game_id);
			$redis_slot = Redis::connection('redis_slots');
			foreach($uids as $uid) {
				$redis_slot->publish('ADMIN_COMMAND', json_encode(['cmd' => 'kick', 'uid' => intval($uid)]));
			}
		}
		else
			$slot = WebviewExternal::find($request->input('id'));

		$slot->delete();

		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}
}
