<?php
namespace App\Http\Controllers;


use App\Model\Game\EventJackpot;
use DateInterval;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventJackpotController extends Controller
{
	private const divInt		= 1000;


	private const event_range	= [100,200,300,400,500,600,700,800,900,1000,2000,3000,4000,5000];




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
	public function search(Request $request)
	{
		$data = [];


		$data['type']		= $request->get('type') ?? 'nickname';
		$data['keyword']	= $request->get('keyword') ?? '';
		$data['range']		= self::event_range;

		$data['result']		= [];

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


		$result = [];

		if (count($seqs) > 0)
		{
			$result = EventJackpot::getList(
				$seqs
				);
		}


		foreach ($result as $key => $row)
		{
			$row->play		= $row->play ?? 0;
			$row->bet		= $row->bet ?? 0;
			$row->fee		= $row->fee ?? 0;

			$row->spend		= $row->bet + $row->fee;
			$row->payout	= $row->payout ?? 0;
			$row->win		= $row->win ?? 0;

			$row->rtp		= $row->spend <= 0 ? 0 : ($row->payout / $row->spend * 100);

			$data['result'][] = $row;
		}


		return view('events.jackpot', $data);
	}



	/**
	 * 이벤트 지급 처리.
	 *
	 * @param object $request
	 * return json
	 */
	public function apply(Request $request)
	{

		$data = [
			'result'	=>	1
		];

		//
		// 데이터 검증.
		//
		$user_seq = $request->input('user_seq');
		$evtDelayMin = $request->post('event_delay_min') ?? 60;

		if (empty($user_seq))
		{
			$data['msg'] = '대상이 올바르지 않습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}


		$multiple		= $request->input('multiple');

		if (!in_array($multiple, self::event_range))
		{
			$data['msg'] = '지급할 수 없는 배수입니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		$rand_id		= date('Ymd_his').'_'.$user_seq.'_';
		$ticket			= $rand_id.substr(uniqid(), 0, (30 - strlen($rand_id)));

		//현재시간과 현재시간을 한번 더 사용해야하기에 2개 선언
		$now = $nowCopy = new DateTime();

		//evtDelayMin 지금 시간에서 더한 시간
		$startTime = $now->add(new DateInterval("PT{$evtDelayMin}M"))->format('Y-m-d H:i:s');

		//expireTime을 보통 한달로 잡음
		$expired = $nowCopy->add(new DateInterval("P1M"))->format('Y-m-d H:i:s');

		DB::beginTransaction();

		//
		// 데이터 기록.
		//
		$result = EventJackpot::setEventJackpot(
			$user_seq,
			$multiple,
			$ticket,
			$startTime,
			$expired
			);

		unset($now);

		if (!$result)
		{
			DB::rollback();

			$data['msg'] = '등록할 수 없습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		$slots = Redis::connection('redis_slots');

		$params = [
			'idx'			=>	$result,
			'rate'			=>	$multiple,
			'ticket'		=>	$ticket,
			'start_time'	=>	$startTime,
			'expired'		=> 	$expired,
		];


		DB::commit();
		$slots->hset('JACKPOT_EVENT', $user_seq, json_encode($params));

		//
		// 결과 처리.
		//
		$data['result']	= 0;
		$data['msg']	= '지급되었습니다.';

		return json_encode($data);
	}

	public function jackpotsByUser($useq, Request $request)
	{
		//
		//초기값 설정
		//
		$data = [];
		$data['page'] = $request->get('page') ?? 1;
		$data['offset']  = $request->get('offset') ?? 10;

		//페이징 처리함
		$jackpots = EventJackpot::get($useq, $data['page'], $data['offset']);
		$jackpots->withQueryString()->links();

		//현재시간
		$now = new DateTime();

		//값 정리함
		foreach ($jackpots as &$row)
		{
			$createdTime 	=	new DateTime($row->created);
			$status 		=	'Completed';

			$row->delay_min = '';
			$possibleCancel = false;

			//dateTime 오브젝트면
			if (!empty($row->start_time))
			{
				$startTime = new DateTime($row->start_time);
				$delayMin = abs($startTime->getTimestamp() - $createdTime->getTimestamp()) / 60;
				$row->delay_min = (is_float($delayMin)) ? ceil($delayMin) : $delayMin;
			}


			if (isset($startTime))
			{
				if ($row->status === '0')
				{
					$updated = new DateTime($row->updated);

					if ($updated != $createdTime && $startTime > $updated)
						$status = 'Cancelled';
				}
				else
				{
					$now = new DateTime();
					$status = 'Pending';

					if ($startTime > $now)
						$possibleCancel = true;
				}

			}

			$row->possible_cancel = $possibleCancel;
			$row->status = $status;
		}

		$data['rs'] = $jackpots;
		$html = view('events.jackpot_list', $data)->render();

		return ['result' => 0, 'html' => $html];

	}

	public function cancelJackpot($useq, $idx, Request $request)
	{
		$startTime = new DateTime($request->input('start_time'));
		$scriptRunTime = $_SERVER['REQUEST_TIME'];

		if ($startTime->getTimestamp() < $scriptRunTime)
		{
			return response(['result' => 999, 'message' => '이미 지급 처리된 잭팟입니다.'], 200);
		}

		$canceled = EventJackpot::drop($idx);

		if (! $canceled)
			return response(['result' => 999, 'message' => '변경에 실패했습니다.'], 404);

		$redis = Redis::connection('redis_slots');

		if (empty($redis))
			return response(['result' => 999, 'message' => '변경에 실패했습니다.'], 404);

		$redis->hdel('JACKPOT_EVENT', $useq);

		return response(['result' => 0, 'message' => '변경에 성공하였습니다.'], 200);

	}
}
