<?php

namespace App\Http\Controllers;

use App\Model\Game\SlotInfoV2;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Exception;


class SlotGameV2Controller extends Controller
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
		$data['slots']		= SlotInfoV2::getSlots();


		return view('slotgamesv2.slots', $data);
	}



	/**
	 * 슬롯 데이터 저장.
	 *
	 *
	 */
	public function set_slot(Request $request)
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


		$params = array ();

		$params['slot_id']		= $request->input('slot_id');
		$params['slot_name']	= $request->input('slot_name');
		$params['slot_type']	= $request->input('slot_type');
		$params['sorted']		= $request->input('sorted');
		$params['slot_group']	= $request->input('slot_group');
		$params['group_sorted']	= $request->input('group_sorted');
		$params['opened'] 		= empty($request->input('opened')) ? 0 : $request->input('opened');
		$params['level']		= empty($request->input('level')) ? 0 : $request->input('level');

		$params['is_new']		= empty($request->input('is_new')) ? 0 : $request->input('is_new');
		$params['is_jackpot']	= empty($request->input('is_jackpot')) ? 0 : $request->input('is_jackpot');


		$result = SlotInfoV2::setSlot($params);


		$data['result']	= 1;
		$data['msg']	= '등록/변경되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * 슬롯 상세 정보.
	 *
	 *
	 */
	public function detail($slot_id)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'slot_id'		=>	trim($slot_id),
			'data'			=>	[]
		);


		$result = SlotInfoV2::getSlots($slot_id);

		if (count($result) >= 0)
		{
			$result = $result[0];

			$result->symbols			= json_decode($result->symbols);
			$result->groups				= json_decode($result->groups);
			$result->paylines			= json_decode($result->paylines);
			$result->rules				= json_decode($result->rules);
			$result->scatter			= json_decode($result->scatter);
			$result->wild				= json_decode($result->wild);
			$result->bonus				= json_decode($result->bonus);
			$result->jackpot			= json_decode($result->jackpot);
			$result->rule_freespin		= json_decode($result->rule_freespin);
			$result->rule_bonusgame		= json_decode($result->rule_bonusgame);
			$result->rule_jackpot		= json_decode($result->rule_jackpot);
			$result->rule_mystery		= json_decode($result->rule_mystery);
			$result->rule_normal		= json_decode($result->rule_normal);


			$data['data'] = $result;
		}


		return view('slotgamesv2.detail', $data);
	}



	/**
	 * 슬롯 데이터 설정.
	 *
	 *
	 */
	public function set_custom()
	{

	}



	/**
	 * 슬롯 데이터 삭제.
	 *
	 *
	 */
	public function drop_custom()
	{

	}



	/**
	 * 슬롯 배팅 정보.
	 *
	 *
	 */
	public function bettings($slot_id='')
	{
		$this->permission('maintenance', 'permission');


		$data = array ();


		$data['slot_id']	= trim($slot_id);
		$data['data']		= SlotInfoV2::getBettings($slot_id);
		$data['max_rate']	= 0;

		foreach ($data['data'] as $key => $row)
		{
			$data['data'][$key]->bet_rate = json_decode($row->bet_rate);
			$data['max_rate'] = max($data['max_rate'], count($data['data'][$key]->bet_rate));
		}

		return view('slotgamesv2.bettings', $data);
	}



	/**
	 * 슬롯 배팅정보 저장.
	 *
	 *
	 */
	public function set_betting(Request $request)
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
				'level'		=>	'required',
				'bet'		=>	'required',
				'bet_rate'	=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$params = array ();

		$params['slot_id']		= $request->input('slot_id');

		$params['level']		= empty($request->input('level')) ? 0 : $request->input('level');
		$params['bet']			= intval($request->input('bet'));

		$params['bet_rate']		= '['.$request->input('bet_rate').']';


		$result = SlotInfoV2::setBetting(
			$params
			);


		$data['result']	= 1;
		$data['msg']	= '등록/변경되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * 슬롯 배팅정보 삭제.
	 *
	 *
	 */
	public function drop_betting(Request $request)
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
				'level'		=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfoV2::dropBetting(
			trim($request->input('slot_id')),
			intval($request->input('bet')),
			);


		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * RTP 구간 데이터.
	 *
	 *
	 */
	public function rtps()
	{
		$this->permission('maintenance', 'permission');


		$data = array ();

		$data['data']		= SlotInfoV2::getBuffs();

		return view('slotgamesv2.rtps', $data);
	}



	/**
	 * RTP 구간 데이터 업데이트.
	 *
	 *
	 */
	public function set_rtp(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0,
			'msg'		=>	'등록할 수 없습니다.',
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'buff_type'		=>	'required',
				'play_min'		=>	'required',
				'play_max'		=>	'required',
				'rtp_min'		=>	'required',
				'rtp_max'		=>	'required',
				'buff_min'		=>	'required',
				'buff_max'		=>	'required',
				'expired_min'	=>	'required',
				'expired_max'	=>	'required',
				'use_start'		=>	'required',
				'use_end'		=>	'required',
				'apply_start'	=>	'required',
				'apply_end'		=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfoV2::setBuff(
			[
				'idx'			=>	empty($request->input('idx')) ? null : $request->input('idx'),
				'sorted'		=>	empty($request->input('sorted')) ? 1 : $request->input('sorted'),
				'buff_type'		=>	trim($request->input('buff_type')),
				'play_min'		=>	trim($request->input('play_min')),
				'play_max'		=>	trim($request->input('play_max')),
				'rtp_min'		=>	trim($request->input('rtp_min')),
				'rtp_max'		=>	trim($request->input('rtp_max')),
				'buff_min'		=>	trim($request->input('buff_min')),
				'buff_max'		=>	trim($request->input('buff_max')),
				'expired_min'	=>	trim($request->input('expired_min')),
				'expired_max'	=>	trim($request->input('expired_max')),
				'use_start'		=>	trim($request->input('use_start')),
				'use_end'		=>	trim($request->input('use_end')),
				'apply_start'	=>	trim($request->input('apply_start').':00'),
				'apply_end'		=>	trim($request->input('apply_end').':59'),
				'status'		=>	empty($request->input('status')) ? 0 : $request->input('status')
			]
			);


		$data['result']	= 1;
		$data['msg']	= '등록되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * RTP 구간 데이터 삭제.
	 *
	 *
	 */
	public function drop_rtp(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0,
			'msg'		=>	'삭제할 수 없습니다.'
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'idx'	=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfoV2::dropBuff(
			intval($request->input('idx'))
			);


		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * 슬롯 데이터 반영.
	 *
	 *
	 */
	public function reset()
	{
		$redis = Redis::connection('redis_slots')
			->publish('ADMIN_COMMAND', json_encode(array('cmd' => 'refereload', 'target' => 'new_slot')));

		$data['result']	= 1;
		$data['msg']	= '적용되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * 슬롯 데이터 반영.
	 *
	 *
	 */
	public function reset_rtp()
	{
		$redis = Redis::connection('redis_slots')
			->publish('ADMIN_COMMAND', json_encode(array('cmd' => 'refereload', 'target' => 'rtp_buff')));

		$data['result']	= 1;
		$data['msg']	= '적용되었습니다.';

		return response()->json($data, 200);
	}



    /**
     * 보정잭팟설정
     *
     *
     */
    public function assigns()
    {
        $this->permission('maintenance', 'permission');


        $data = [
            'data' => SlotInfoV2::getAssigns(),
            'slots' => SlotInfoV2::getSlots(),
        ];

        $slotnames = [];
        foreach ($data['slots'] as $key => $row)
            $slotnames[$row->slot_id] = $row->slot_name;

        foreach($data['data'] as $key => $row) {
            if(empty($row->slot_id) || $row->slot_id == 'ALL') $data['data'][$key]->slot_name = '전체';
            else $data['data'][$key]->slot_name = $slotnames[$row->slot_id] . ' ' . $row->slot_id;

            // 구매 기대값 계산.
            if ($row->used === 'B')
            {
                $data['data'][$key]->expected = '구매금액';
                $data['data'][$key]->expected_min = intval($row->range_min);
                $data['data'][$key]->expected_max = intval($row->range_max);
            }
            // 일반 기대값 계산.
            else
            {
                $data['data'][$key]->expected = 'RTP 1당 증가치';
                $data['data'][$key]->expected_min = self::calcAssignRate($row, $row->rtp_max, $row->range_min);
                $data['data'][$key]->expected_max = self::calcAssignRate($row, $row->rtp_min, $row->range_max);
            }
        }

        return view('slotgamesv2.assigns', $data);
    }



    /**
     * 보정잭팟 업데이트
     *
     *
     */
    public function set_assigns(Request $request)
    {
        $this->permission('maintenance', 'permission');


        $data = array
        (
            'result'	=>	0,
            'msg'		=>	'등록할 수 없습니다.',
        );

        $validator = Validator::make(
            $request->input(),
            array
            (
                'used'		    =>	'required',
                'prob'		    =>	'required',
                'rtp_min'		=>	'required',
                'rtp_max'		=>	'required',
                'range_min'		=>	'required',
                'range_max'		=>	'required',
            ));

        if ($validator->fails())
        {
            $data['msg'] = $validator->errors();
            return response()->json($data, 200);
        }


        $result = SlotInfoV2::setAssigns(
            [
                'idx'			=>	empty($request->input('idx')) ? null : $request->input('idx'),
                'slot_id'		=>	empty($request->input('slot_id')) ? null : $request->input('slot_id'),
                'used'  		=>	trim($request->input('used')),
                'rtp_min'		=>	trim($request->input('rtp_min')),
                'rtp_max'		=>	trim($request->input('rtp_max')),
                'prob'  		=>	trim($request->input('prob')),
                'range_min'		=>	trim($request->input('range_min')),
                'range_max'		=>	trim($request->input('range_max')),
                'status'		=>	empty($request->input('status')) ? 0 : $request->input('status'),
                'summary'       =>  empty($request->input('summary')) ? null : $request->input('summary'),
            ]
        );


        $data['result']	= 1;
        $data['msg']	= '등록되었습니다.';

        return response()->json($data, 200);
    }



    /**
     * 보정잭팟 삭제
     *
     *
     */
    public function drop_assigns(Request $request)
    {
        $this->permission('maintenance', 'permission');


        $data = array
        (
            'result'	=>	0,
            'msg'		=>	'삭제할 수 없습니다.'
        );

        $validator = Validator::make(
            $request->input(),
            array
            (
                'idx'	=>	'required',
            ));

        if ($validator->fails())
        {
            $data['msg'] = $validator->errors();
            return response()->json($data, 200);
        }


        $result = SlotInfoV2::dropAssigns(
            intval($request->input('idx'))
        );


        $data['result']	= 1;
        $data['msg']	= '삭제되었습니다.';

        return response()->json($data, 200);
    }



    /**
     * 보정잭팟 반영
     *
     *
     */
    public function reset_assigns()
    {
        $redis = Redis::connection('redis_slots')
            ->publish('ADMIN_COMMAND', json_encode(array('cmd' => 'refereload', 'target' => 'assigns')));

        $data['result']	= 1;
        $data['msg']	= '적용되었습니다.';

        return response()->json($data, 200);
    }



	/**
	 * 기간잭팟 목록
	 *
	 * @var function
	 */
	public function jackpots()
	{
		$this->permission('maintenance', 'permission');


		$data = [
			'data'		=>	SlotInfoV2::getAssignJackpots(),
			'dateset'	=>	SlotInfoV2::getAssignDateset()[0],

			'datetype'	=>	[
				'H'			=>	['시간',	'color: orange'],
				'D'			=>	['일',		'color: green'],
				'W'			=>	['주',		'color: blue'],
				'M'			=>	['월',		'color: red']
			],
			'slots'		=>	[],
		];

		foreach (SlotInfoV2::getSlots() as $row)
			$data['slots'][$row->slot_id] = $row;


		foreach ($data['data'] as $key => $row)
		{
//			$data['data'][$key]

			switch ($row->datetype)
			{
				case 'M':
					$data['data'][$key]->month	= implode('-', [substr($row->usecode, 0, 4), substr($row->usecode, 4, 2)]);
					break;

				case 'W':
					$data['data'][$key]->weekst	= implode('-', [substr($row->usecode, 0, 4), 'W'.substr($row->usecode, 4, 2)]);
					break;

				case 'D':
					$data['data'][$key]->date	= implode('-', [substr($row->usecode, 0, 4), substr($row->usecode, 4, 2), substr($row->usecode, 6, 2)]);
					break;

				case 'H':
					$data['data'][$key]->date	= implode('-', [substr($row->usecode, 0, 4), substr($row->usecode, 4, 2), substr($row->usecode, 6, 2)]);
					$data['data'][$key]->hour	= substr($row->usecode, 8, 2);
					break;
			}
		}

		return view('slotgamesv2.jackpots', $data);
	}



	/**
	 * 기간잭팟 등록/업데이트
	 *
	 * @var function
	 */
	public function set_jackpot(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0,
			'msg'		=>	'등록할 수 없습니다.',
		);


		$validator = Validator::make(
			$request->input(),
			array
			(
				'datetype'			=>	'required',
				'term'				=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$params = [];

		try {
			// 변경 시 사용할 INDEX 값 체크.
			$params['idx']			= empty($request->post('idx')) ? null : trim($request->post('idx'));

			// 슬롯 ID 무결성 체크.
			$params['slot_id']		= empty($request->post('slot_id')) ? null : trim($request->post('slot_id'));

			// 집계구분 체크.
			$params['datetype']		= $request->post('datetype');

			// 집계기간 체크.
			if (intval($request->term) <= 0)
				throw new Exception('집계 기간 값이 올바르지 않습니다.', 1);

			$params['term']			= $request->post('term');

			// 집계 구분에 따른 필수 데이터 체크.
			switch ($params['datetype'])
			{
				case 'M':
					if (empty($request->post('month')))
						throw new Exception('시작기간(월) 값이 올바르지 않습니다.', 1);

					$params['dt']	= $request->post('month').'-01 00:00:00';
					break;

				case 'W':
					if (empty($request->post('week')))
						throw new Exception('시작기간(주) 값이 올바르지 않습니다.', 1);

					$params['dt']	= date('Y-m-d H:i:s', strtotime($request->post('week')));
					break;

				case 'D':
					if (empty($request->post('date')))
						throw new Exception('시작기간(일) 값이 올바르지 않습니다.', 1);

					$params['dt']	= $request->post('date').' 00:00:00';
					break;

				case 'H':
					if (empty($request->post('date')))
						throw new Exception('시작기간(시)의 날짜 값이 올바르지 않습니다.', 1);

					if (empty($request->post('hour')))
						throw new Exception('시작기간(시)의 시간 값이 올바르지 않습니다.', 2);

					$params['dt']	= $request->post('date').' '.str_pad($request->post('hour'), 2, '0', STR_PAD_LEFT).':00:00';
					break;

				default:
					throw new Exception('집계구분 값이 올바르지 않습니다.', 1);
					break;
			}

			// 필요 요구치 체크.
			if ($request->post('required_play') == null ||
				$request->post('required_play') < 0)
				throw new Exception('필요 플레이 횟수 값이 올바르지 않습니다.', 1);

			$params['required_play']	= intval($request->post('required_play'));

			if ($request->post('required_bet') == null ||
				$request->post('required_bet') <= 0)
				throw new Exception('필요 배팅 금액 값이 올바르지 않습니다.', 1);

			$params['required_bet']		= intval($request->post('required_bet'));

			if ($request->post('required_rtp') == null ||
				$request->post('required_rtp') <= 0)
				throw new Exception('기준 RTP 값이 올바르지 않습니다.', 1);

			$params['required_rtp']		= floatval($request->post('required_rtp'));


			// 지급 데이터 체크.
			if (empty($request->post('reward_min')))
				throw new Exception('지급배수(최소) 값이 올바르지 않습니다.', 1);

			$params['reward_min']		= intval($request->post('reward_min'));

			if (empty($request->post('reward_max')))
				throw new Exception('지급배수(최소) 값이 올바르지 않습니다.', 1);

			$params['reward_max']		= intval($request->post('reward_max'));

			if ($params['reward_max'] < $params['reward_min'])
				throw new Exception('지급배수 최소값이 최대값보다 클 수 없습니다.', 2);

			if (empty($request->post('reward_user')))
				throw new Exception('지급 유저 수 값이 올바르지 않습니다.');

			$params['reward_user']		= intval($request->post('reward_user'));

			// 한줄 설명 체크.
			$params['summary']			= empty(trim($request->post('summary'))) ? '' : trim($request->post('summary'));

			// 상태값 체크.
			$params['status']			= empty(intval($request->post('status'))) ? 0 : intval($request->post('status'));
		}
		catch(Exception $e)
		{
			$data['msg'] = $e->getMessage().' ( '.$e->getCode().' )';
			return response()->json($data, 200);
		}


		$result = SlotInfoV2::setAssignJackpot(
			$params
			);


		$data['result']	= 1;
		$data['msg']	= '등록되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * 기간잭팟 삭제
	 *
	 * @var function
	 */
	public function drop_jackpot(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = array
		(
			'result'	=>	0,
			'msg'		=>	'삭제할 수 없습니다.'
		);

		$validator = Validator::make(
			$request->input(),
			array
			(
				'idx'	=>	'required',
			));

		if ($validator->fails())
		{
			$data['msg'] = $validator->errors();
			return response()->json($data, 200);
		}


		$result = SlotInfoV2::dropAssignJackpot(
			intval($request->input('idx'))
		);


		$data['result']	= 1;
		$data['msg']	= '삭제되었습니다.';

		return response()->json($data, 200);
	}













    /**
     * 구매 기대값 계산 공식.
     *
     * @param object $refer 참조 데이터.
     * @param float $rtp 대상 RTP
     * @param float $target 목표 RTP
     * @return integer
     */
    private function calcAssignRate($refer, $rtp, $target)
    {
        $bet            = 1000;
        $standbet       = $bet * 500;


        // 구매는 기준 RTP를 초기화 한다.
        if ($refer->used === 'B')
            $rtp = 0;

        if ($target <= 0)
            return 0;

        // RTP 1당 증가치
        $point          = @intval(@($standbet / $target) * @($target / 100));

        // 계산 공식 === (RTP 1당 증가치) * ( 목표RTP - 시작RTP ) / 배팅금액
        $rate           = intval($point * (($target - $rtp > 0 ? $target : ($target + $rtp)) - $rtp) / $bet);

        $finalrate      = $refer->used === 'B' ? ($rate <= 0 ? rand(10, 50) : $rate) : ($rate < 10 ? 0 : $rate);


        return $finalrate;
    }

}

