<?php

namespace App\Http\Controllers;

use App\Model\Game\SlotInfo;
use App\Model\Game\SlotInfoV2;
use App\Model\Game\SlotLog;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SlotLogController extends Controller
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


	public function daily(Request $request)
	{
		$data = array ();


		// 사용할 데이터 정의.
		$data['slot_id']	= $request->query('slot_id');
		$data['start']		= $request->query('start_date');
		$data['end']		= $request->query('end_date');

		// 데이터 무결성 처리.
		$data['start']		= empty($data['start']) ? date('Y-m-d') : $data['start'];
		$data['end']		= empty($data['end']) ? date('Y-m-d') : $data['end'];

		if (empty($data['slot_id']))
			$data['slot_id'] = '';

		// 로그 데이터 가져오기.
		$result			= SlotLog::getSlotLogs(
			$data['slot_id'],
			str_replace('-', '', $data['start']),
			str_replace('-', '', $data['end']),
			'N'
			);

		$data['data']	= $result['data'];
		$data['date']	= $result['date'];

		// 슬롯 레퍼런스 가져오기.
		$data['slots']	= SlotInfo::getSlots();
        $data['slotsv2'] = SlotInfoV2::getSlots();


		return view('slotlogs.daily', $data);
	}


	public function normalize(Request $request)
	{
		$data = array ();


		// 사용할 데이터 정의.
		$data['slot_id']	= $request->query('slot_id');
		$data['start']		= $request->query('start_date');
		$data['end']		= $request->query('end_date');

		// 데이터 무결성 처리.
		$data['start']		= empty($data['start']) ? date('Y-m-d') : $data['start'];
		$data['end']		= empty($data['end']) ? date('Y-m-d') : $data['end'];

		// 로그 데이터 가져오기.
		$result			= SlotLog::getSlotLogs(
			$data['slot_id'],
			str_replace('-', '', $data['start']),
			str_replace('-', '', $data['end']),
			'N'
			);

		$data['data']	= $result['data'];
		$data['date']	= $result['date'];

		// 슬롯 레퍼런스 가져오기.
		$data['slots']	= SlotInfo::getSlots();


		return view('slotlogs.normalize', $data);
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

		$data['date']		= empty($request->query('date')) ? date('Y-m-d') : $request->query('date');
		$data['stime']		= empty($request->query('stime')) ? '00:00' : $request->query('stime');
		$data['etime']		= empty($request->query('etime')) ? '23:59' : $request->query('etime');

		$data['page']		= empty($request->query('page')) ? 1 : intval($request->query('page'));
		$data['limit']		= empty($request->query('limit')) ? 20 : intval($request->query('limit'));

		$result				= SlotLog::getGameLog(
			$data['date'],
			$data['stime'],
			$data['etime'],
			$data['uid'],
			$data['slot_id'],
			$data['page'],
			$data['limit']
			);

		$data['data']		= $result['data'];
		$data['count']		= $result['count'];
        $data['increase']	= $result['increase'];

		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;

		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;

		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		// 슬롯 레퍼런스 가져오기.
		$data['slots']	= SlotInfo::getSlots();


		return view('slotlogs.spin', $data);
	}

}
