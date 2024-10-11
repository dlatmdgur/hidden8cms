<?php
namespace App\Http\Controllers;


use App\Model\Game\EventJackpot;
use App\Model\Game\GlobalJackpot;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

use Exception;
use Illuminate\Support\Facades\DB;

class GlobalJackpotController extends Controller
{
	private const divInt		= 1000;


	private const event_tiers	= ['GRAND', 'MAJOR', 'MINOR', 'MINI'];





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
	 * 잭팟 스위치 페이지
	 *
	 * @return RedirectResponse|Redirector
	 */
	public function panel(Request $request)
	{
		$data = [
			'active'	=>	'panel'
		];

		$slotCol = ['slot_id AS id', 'slot_name AS name', 'JSON_EXTRACT(rule_normal, \'$.useRoyalJackpot\') AS use_royal_jackpot'];

		$data['tiers'] = GlobalJackpot::getReferJackpot();
		$data['slots'] = GlobalJackpot::getReferSlots($slotCol, 'ASC');


		return view('events.global_panel', $data);
	}



	/**
	 * 잭팟 기능 스위치 처리.
	 *
	 * @param integer $idx 대상 Index
	 * @param integer $status ON/OFF 여부
	 */
	public function switch(Request $request, $idx, $status)
	{
		//
		// 리턴 데이터 정의.
		//
		$data = [
			'result'		=>	999,
			'msg'			=> '상태변경을 실패하였습니다.',
		];

		//
		// 무결성 체크.
		//
		if (empty($idx))
		{
			$data['msg'] = '대상이 올바르지 않습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		$status = empty($status) ? 0 : 1;



		//
		// 데이터 스위치.
		//
		$result = GlobalJackpot::setReferJackpotStatus($idx, $status);

		if (!$result)
		{
			$data['msg'] = 'ON/OFF 를 변경할 수 없습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		//
		// 갱신 요청.
		//
		$slots = Redis::connection('redis_slots');

		$params = [
			'cmd'		=>	'refereload',
			'refer'		=>	'royaljackpots'
		];

		$slots->publish('ADMIN_COMMAND', json_encode($params));



		//
		// 결과 처리.
		//
		$data['result'] = 0;
		$data['msg'] = '상태변경에 성공하였습니다.';

		return json_encode($data);
	}


	public function slotSwitch(Request $request)
	{

		$target = $request->post('target') ?? null;
		$chgStatus = empty($request->post('status')) ? 0 : 1;

		$result = 999;
		$code = 400;
		$message = '변경에 실패했습니다.';

		if (is_null($target))
			return response()->json(['result' => $result, 'message' => $message], $code);

		DB::beginTransaction();

		$updated = GlobalJackpot::setReferSlotStatus($target, $chgStatus);

		if ($target !== 'all' && $updated < 1)
		{
			DB::rollback();
			return response()->json(['result' => $result, 'message' => $message], $code);

		}

		$redis = Redis::connection('redis_slots');

		if (empty($redis))
		{
			DB::rollback();
			return response()->json(['result' => $result, 'message' => $message], $code);
		}

		$result = 0;
		$code = 200;
		$message = '변경에 성공했습니다.';

		//
		// 슬롯 서버 재갱신 요청
		//
		$redisParam = array(
			'cmd' 	=> 'refereload',
			'refer' => 'slots',
		);

		$redis->publish('ADMIN_COMMAND', json_encode($redisParam));
		DB::commit();

		return response()->json(['result' => $result, 'message' => $message], $code);

	}


	/**
	 * 강제 지급 페이지.
	 *
	 * @return RedirectResponse|Redirector
	 */
	public function force(Request $request)
	{
		$data = [
			'active'	=>	'force'
		];


		$data['type']		= $request->get('type') ?? 'nickname';
		$data['keyword']	= $request->get('keyword') ?? '';
		$data['range']		= self::event_tiers;


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


		return view('events.global_force', $data);
	}



	/**
	 * 강제 지급 처리.
	 *
	 *
	 */
	public function apply(Request $request)
	{
		$data = [];



		//
		// 데이터 검증.
		//
		$user_seq	= $request->input('user_seq');

		if (empty($user_seq))
		{
			$data['msg'] = '대상이 올바르지 않습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}


		$tier		= $request->input('tier');

		if (!in_array($tier, self::event_tiers))
		{
			$data['msg'] = '지급할 수 없는 대상입니다. ( TIER ERROR )';
			return response($data)->header('Content-Type', 'application/json');
		}



		//
		// 잭팟 DB 저장.
		//
		$result = GlobalJackpot::setRoyalJackpotUser($user_seq, $tier);



		//
		// 결과 처리.
		//
		$data['result']	= 0;
		$data['msg']	= '지급되었습니다.';

		return json_encode($data);
	}



	/**
	 * 강제 지급 로그 출력.
	 *
	 *
	 */
	public function forcelog(Request $request)
	{
		$data = [];

		$offset = 1;

		//
		// 무결성 체크.
		//
		$data['user_seq']	= $request->get('user_seq') ?? null;
		$data['page']		= $request->get('page') ?? 1;
		$data['offset']		= $request->get('offset') ?? 1;
		$data['type']		= $request->get('type') ?? null;
		$data['into_force'] = false;
		$data['logs']		= GlobalJackpot::getRoyalJackpotUsers($data['user_seq'], $data['page'], $data['offset'], $data['type']);
		$data['logs']->withQueryString()->links();

		//force 페이지로 들어온 경우
		if ($data['user_seq'])
			$data['into_force'] = true;

		$data['html'] = view('events.global_force_history', $data)->render();


		return ['result' => 0, 'html' => $data['html']];

	}



}


