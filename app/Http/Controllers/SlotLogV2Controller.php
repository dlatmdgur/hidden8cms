<?php

namespace App\Http\Controllers;

use App\Model\Game\SlotInfoV2;
use App\Model\Game\SlotLogV2;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Exception;


class SlotLogV2Controller extends Controller
{
	private const divInt		= 1000;


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
			return redirect('/statistics/exchange');
		else
		{
			if (!$user->hasPermissionTo('member'))
				return redirect('/blank');
			else
				return redirect('/member/info');
		}

		//return view('dashboard');
	}


	public function spin(Request $request)
	{
		$data = array
		(
			'divInt'	=>	self::divInt
		);


		// 사용할 데이터 정의.
		$data['uid']		= empty($request->query('uid')) ? '' : $request->query('uid');
		$data['slot_id']	= empty($request->query('slot_id')) ? '' : $request->query('slot_id');
        $data['game_type']  = empty($request->query('game_type')) ? '' : $request->query('game_type');

		$data['date']		= empty($request->query('date')) ? date('Y-m-d') : $request->query('date');
		$data['stime']		= empty($request->query('stime')) ? '00:00' : $request->query('stime');
		$data['etime']		= empty($request->query('etime')) ? '23:59' : $request->query('etime');

		$data['page']		= empty($request->query('page')) ? 1 : intval($request->query('page'));
		$data['limit']		= empty($request->query('limit')) ? 20 : intval($request->query('limit'));

		$data['increase']   = 0;

		// 슬롯 레퍼런스 가져오기.
		$data['slots']	= SlotInfoV2::getSlots();
		$data['slotname'] = [];
		foreach ($data['slots'] as $row)
			$data['slotname'][$row->slot_id] = $row->slot_name;


		// 로그 가져오기.
		$result				= SlotLogV2::getGameLog(
			$data['date'],
			$data['stime'],
			$data['etime'],
			$data['uid'],
			$data['slot_id'],
			$data['game_type'],
			$data['page'],
			$data['limit']
			);


		foreach ($result['data'] as $row)
		{
			$data['increase'] += (float)$row->aft_coins + (float)$row->aft_bonus - (float)$row->bef_coins - (float)$row->bef_bonus;
		}

		$data['data']		= $result['data'];
		$data['count']		= $result['count'];


		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;

		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;

		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		return view('slotlogsv2.spin', $data);
	}



	/**
	 * 기간잭팟 지급 유저 데이터
	 *
	 * @return Application|View
	 */
	public function jackpotusers(Request $request)
	{
		$data = [
			'idx'		=>	null,
			'scode'		=>	null,
			'ecode'		=>	null,
			'msg'		=>	'DATA NOT FOUND.'
		];



		//
		// 잭팟 목록 색인.
		//
		$data['jackpots']	= [];
		$data['groups']		= [];

		foreach (SlotInfoV2::getAssignJackpots() as $key => $row)
		{
			if (empty($row->summary))
				$row->summary = "설명없음# 구분:{$row->datetype}, 기간:{$row->term}, 등록일:{$row->created}";

			$data['jackpots'][$row->idx] = (object)[
				'idx'			=>	$row->idx,
				'datetype'		=>	$row->datetype,
				'summary'		=>	$row->summary
			];

			if (!isset($data['groups'][$row->status]))
				$data['groups'][$row->status] = [];

			array_push($data['groups'][$row->status], $row->idx);
}

		krsort($data['groups']);

		foreach ($data['groups'] as $key => $row)
			asort($data['groups'][$key]);



		//
		// 무결성 체크.
		//
		try {

			// 기간잭팟 검색할 경우.
			if (!empty($request->post('idx')))
			{
				$data['idx']	= intval($request->post('idx'));


				// 타입별 검색조건 체크.
				switch (trim($request->post('datetype')))
				{
					case 'H':

						$data['date']		= trim($request->post('date'));

						if (empty($data['date']))
							throw new Exception('시간별잭팟 검색일을 선택해야 합니다.', $data['date']);

						$data['stime']		= trim($request->post('stime'));

						if ($data['stime'] == null ||
							$data['stime'] < 0 ||
							$data['stime'] > 23)
							throw new Exception('시간별잭팟 검색시간(시작)을 입력해야 합니다.', $data['stime']);

						$data['etime']		= trim($request->post('etime'));

						if ($data['etime'] == null ||
							$data['etime'] < 0 ||
							$data['etime'] > 23)
							throw new Exception('시간별잭팟 검색시간(종료)을 입력해야 합니다.', $data['etime']);

						$data['scode']		= intval(str_replace('-', '', $data['date']).str_pad($data['stime'], 2, '0', STR_PAD_LEFT));
						$data['ecode']		= intval(str_replace('-', '', $data['date']).str_pad($data['etime'], 2, '0', STR_PAD_LEFT));

						break;


					case 'D':

						$data['sdate']		= $request->post('sdate');

						if (empty($data['sdate']))
							throw new Exception('일간잭팟 검색일(시작)을 선택해야 합니다.', $data['sdate']);

						$data['edate']		= $request->post('edate');

						if (empty($data['edate']))
							throw new Exception('일간잭팟 검색일(종료)을 선택해야 합니다.', $data['edate']);

						$data['scode']		= intval(str_replace('-', '', $data['sdate']));
						$data['ecode']		= intval(str_replace('-', '', $data['edate']));

						break;


					case 'W':

						$data['sweek']		= trim($request->post('sweek'));

						if (empty($data['sweek']))
							throw new Exception('주간잭팟 검색주(시작)를 선택해야 합니다.', $data['sweek']);

						$data['eweek']		= trim($request->post('eweek'));

						if (empty($data['eweek']))
							throw new Exception('주간잭팟 검색주(종료)를 선택해야 합니다.', $data['eweek']);

						$data['scode']		= intval(str_replace('-', '', $data['sweek']));
						$data['ecode']		= intval(str_replace('-', '', $data['eweek']));

						break;


					case 'M':

						$data['smonth']		= trim($request->post('smonth'));

						if (empty($data['smonth']))
							throw new Exception('월간잭팟 검색월(시작)을 선택해야 합니다.', $data['smonth']);

						$data['emonth']		= trim($request->post('emonth'));

						if (empty($data['emonth']))
							throw new Exception('기간잭팟 기간 데이터형을 알 수 없습니다.', $data['emonth']);

						$data['scode']		= intval(str_replace('-', '', $data['smonth']));
						$data['ecode']		= intval(str_replace('-', '', $data['emonth']));

						break;


					default:
						throw new Exception('검색 대상의 기간잭팟 데이터형을 알 수 없습니다.');
						break;
				}


				if ($data['ecode'] < $data['scode'])
					throw new Exception('검색기간 시작일이 종료일보다 클 수 없습니다.');
			}

		}
		catch(Exception $e)
		{
			$data['msg'] = $e->getMessage().($e->getCode() ? ' ( '.$e->getCode().' )' : '');
			return view('slotlogsv2.jackpotusers', $data);
		}



		//
		// 잭팟 유저 색인.
		//
		$data['users'] = SlotLogV2::getAssignJackpotUsers(
			$data['idx'],
			$data['scode'],
			$data['ecode']
			);

		foreach ($data['users'] as $key => $row)
		{
		}






		return view('slotlogsv2.jackpotusers', $data);
	}
}

