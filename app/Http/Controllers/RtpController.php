<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

use Illuminate\Validation\ValidationException;

use Illuminate\View\View;

use Spatie\Permission\Models\Permission;

use App\Model\Game\Rtp;
use App\Model\Game\EventJackpot;

use Exception;

class RtpController extends Controller
{
	/**
	 *
	 *
	 * @var object
	 */
	private const rtp_range	= [
		"88"	=>	80,		// 88
		"91"	=>	85,		// 91
		"93"	=>	90,		// 93
		"96"	=>	95,		// 96
	];



	/**
	 * 생성자 처리.
	 *
	 * @var function
	 */
	public function __construct()
	{
		// 권한 체크.
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
	 * 범용 목록 출력.
	 *
	 *
	 */
	public function general(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = [];

		$data['page']		= $request->get('page') ?? 1;
		$data['offset']		= $request->get('offset') ?? 20;

		//2024-10-11 조팀장님 요청 일단 rtp 큰 값이 먼저 나오게 수정
		$rtpRange			= self::rtp_range;

		//키 값으로 정렬
		krsort($rtpRange);

		$data['rtp_range']	= $rtpRange;

		$data['rtps']		= Rtp::getGenerals([], $data['page'], $data['offset']);

		if (count($data['rtps']) <= 0)
		{
			$data['rtps'][0] = (object)[
				'rtp'		=>	0
			];
		}

		return view('rtps.general', $data);
	}



	public function general_set(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = [
			'result'	=>	1,
			'msg'		=>	'',
		];



		//
		// 무결성 체크.
		//
		try {
			// 레디스 확인.
			$redis = Redis::connection('redis_store');

			if (empty($redis))
				throw new Exception('REDIS 서버가 응답하지 않습니다.');


			// RTP 체크.
			$rtp	= $request->get('rtp') ?? 0;

			if (empty($rtp))
				throw new Exception('설정할 RTP를 선택하세요.');

//			if (!in_array($rtp, self::rtp_range))
//				throw new Exception('설정할 수 없는 RTP 입니다.');

		}
		catch (Exception $e)
		{
			$data['msg'] = $e->getMessage();
			return json_encode($data);
		}



		//
		// 데이터 저장.
		//
		$result = Rtp::setGeneral(0, $rtp);

		if (!$result)
		{
			$data['msg'] = '등록할 수 없습니다.';
			return json_encode($data);
		}

		$result = Rtp::resetSlot();
		// $result = Rtp::resetUser();


		// 등록되었으니 REDIS 갱신.
		$redis->hset('RTP_PROFILE_GENERAL', '0', $rtp);

		// 하위 개념 삭제.
		$redis->del('RTP_PROFILE_SLOT', 'RTP_PROFILE_USER');



		//
		// 결과 처리.
		//
		$data['result']		= 0;
		$data['msg']		= '등록되었습니다.';

		return json_encode($data);
	}



	/**
	 * 슬롯 목록 출력.
	 *
	 *
	 */
	public function slot(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = [];

		$data['type']		= $request->get('type') ?? 'nickname';
		$data['keyword']	= $request->get('keyword') ?? '';

		$data['page']		= $request->get('page') ?? 1;
		$data['offset']		= $request->get('offset') ?? 20;

		//2024-10-11 조팀장님 요청 일단 rtp 큰 값이 먼저 나오게 수정
		$rtpRange			= self::rtp_range;

		//키 값으로 정렬
		krsort($rtpRange);

		$data['rtp_range']	= $rtpRange;


		$data['rtps']		= Rtp::getSlots(
			[
				['status', '=', 1],
			],
			$data['page'],
			$data['offset']
			);

		return view('rtps.slot', $data);
	}



	public function slot_set(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = [
			'result'	=>	1,
			'msg'		=>	'',
		];

		//
		// 무결성 체크.
		//
		try {
			// 레디스 확인.
			$redis = Redis::connection('redis_store');

			if (empty($redis))
				throw new Exception('REDIS 서버가 응답하지 않습니다.');


			// 슬롯 ID
			$slot_id	= $request->get('slot_id') ?? '';

			if (empty($slot_id))
				throw new Exception('대상 슬롯이 올바르지 않습니다.');


			// RTP 체크.
			$rtp		= $request->get('rtp') ?? 0;

			if (empty($rtp))
				throw new Exception('설정할 RTP를 선택하세요.');

//			if (!in_array($rtp, self::rtp_range))
//				throw new Exception('설정할 수 없는 RTP 입니다.');

		}
		catch (Exception $e)
		{
			$data['msg'] = $e->getMessage();
			return json_encode($data);
		}



		//
		// 데이터 저장.
		//
		$result = Rtp::setSlot(0, $slot_id, $rtp);

		if (!$result)
		{
			$data['msg'] = '등록할 수 없습니다.';
			return json_encode($data);
		}

		// 등록되었으니 REDIS 갱신.
		$redis->hset('RTP_PROFILE_SLOT', implode('_', ['0', $slot_id]), $rtp);



		//
		// 결과 처리.
		//
		$data['result']		= 0;
		$data['msg']		= '등록되었습니다.';

		return json_encode($data);
	}



	/**
	 * 범용 목록 출력.
	 *
	 *
	 */
	public function user(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = [];

		$data['type']		= $request->get('type') ?? 'nickname';
		$data['keyword']	= $request->get('keyword') ?? '';

		$data['page']		= $request->get('page') ?? 1;
		$data['offset']		= $request->get('offset') ?? 99999;

		//2024-10-11 조팀장님 요청 일단 rtp 큰 값이 먼저 나오게 수정
		$rtpRange			= self::rtp_range;

		//키 값으로 정렬
		krsort($rtpRange);

		$data['rtp_range']	= $rtpRange;
		$data['rtps']		= [];


		// 검색 유저 정의.
		$seqs = [];
		if (!empty($data['keyword']))
		{
			if ($data['type'] == 'userseq')
			{
				if (is_numeric($data['keyword']))
					$seqs[] = $data['keyword'];
			}
			else
			{
				$result = EventJackpot::getUserSeq(
					$data['type'],
					$data['keyword']
					);

				foreach ($result as $key => $row)
					$seqs[] = intval($row->user_seq);
			}
		}

		if (count($seqs) > 0)
		{
			$temps = [];

			$where	= ['user_seq', $seqs];
			$users	= EventJackpot::getList($seqs);

			foreach ($users as $key => $row)
			{
				$temps[$row->user_seq] = [
					'user_seq'		=>	$row->user_seq,
					'uid'			=>	$row->user_seq,
					'nickname'		=>	$row->nickname,
					'play'			=>	$row->play,
					'bet'			=>	$row->bet,
					'win'			=>	$row->win,
					'real_rtp'		=>	$row->rtp,
					'rtp'			=>	null
				];
			}

			$where	= ['uid', $seqs];
			$rtps	= Rtp::getUsers($where, $data['page'], $data['offset']);

			foreach ($rtps as $key => $row)
			{
				$temps[$row->uid]['rtp'] = $row->rtp;
				$temps[$row->uid]['updated'] = $row->updated;
			}

			foreach ($temps as $key => $row)
				$data['rtps'][] = (object)$row;
		}


		return view('rtps.user', $data);
	}



	public function user_set(Request $request)
	{
		$this->permission('maintenance', 'permission');


		$data = [
			'result'	=>	1,
			'msg'		=>	'',
		];

		//
		// 무결성 체크.
		//
		try {
			// 레디스 확인.
			$redis = Redis::connection('redis_store');

			if (empty($redis))
				throw new Exception('REDIS 서버가 응답하지 않습니다.');


			// 슬롯 ID
			$uid		= $request->get('user_seq') ?? '';

			if (empty($uid))
				throw new Exception('대상 유저가 올바르지 않습니다.');


			// RTP 체크.
			$rtp		= $request->get('rtp') ?? 0;

			if (empty($rtp))
				throw new Exception('설정할 RTP를 선택하세요.');

//			if (!in_array($rtp, self::rtp_range))
//				throw new Exception('설정할 수 없는 RTP 입니다.');

		}
		catch (Exception $e)
		{
			$data['msg'] = $e->getMessage();
			return json_encode($data);
		}



		//
		// 데이터 저장.
		//
		$result = Rtp::setUser(0, $uid, $rtp);

		if (!$result)
		{
			$data['msg'] = '등록할 수 없습니다.';
			return json_encode($data);
		}

		// 등록되었으니 REDIS 갱신.
		$redis->hset('RTP_PROFILE_USER', $uid, $rtp);



		//
		// 결과 처리.
		//
		$data['result']		= 0;
		$data['msg']		= '등록되었습니다.';

		return json_encode($data);
	}

}

