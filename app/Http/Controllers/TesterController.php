<?php
namespace App\Http\Controllers;

use App\Model\Account\AccountInfo;
use App\Model\Game\TesterInfo;
use App\Model\Game\WebviewSlot;
use App\Model\Game\WebviewExternal;

use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Routing\Redirector;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class TesterController extends Controller
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

		if (!$user->hasPermissionTo('member') && $user->hasPermissionTo('outer0'))
		{
			return redirect('/statistics/exchange');
		}
		else
		{
			return redirect('/member/info');
		}

		return view('dashboard');
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
	 * 테스트 유저 출력
	 *
	 *
	 */
	public function list(Request $request)
	{
		$this->permission('maintenance', 'permission', 'outer0');



		$page	= $request->get('page') ?? 1;
		$offset	= 20;

		$t		= $request->get('t') ?? 'u';
		$q		= $request->get('q') ?? '';


		$where	= [];
		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':	array_push($where, ['a.user_seq', '=', $q]);		break;
				case 'n':	array_push($where, ['a.nickname', 'like', $q]);		break;
			}
		}



		$testers = TesterInfo::get($where, $page, $offset);
		$testers->withQueryString()->links();

		$data = [
			'testers'		=>	$testers,
			'page'			=>	$page,
			'offset'		=>	$offset
		];

		return view('tester.list', $data);
	}



	/**
	 * 테스트 유저 등록
	 *
	 *
	 */
	public function set(Request $request)
	{
		$this->permission('maintenance', 'permission', 'outer0');


		//
		// 데이터 무결성 처리.
		//
		$data		= array
		(
			'result'	=>	0
		);

		// 데이터 검증
		$validator	= Validator::make(
			$request->input(),
			array
			(
				'user_seq'	=>	'required',
			));

		// 오류시 경고 리턴.
		if ($validator->fails())
		{
			$data['msg']	= $validator->errors()->first();
			return response()->json($data, 200);
		}


		// 사용할 데이터 셋팅.
		$user_seq	= $request->input('user_seq') ?? '';
		$corps		= $request->input('corps') ?? [];



		// 데이터 등록.
		$result		= TesterInfo::set(
			$user_seq,
			$corps
			);



		//
		// 결과 처리
		//
		$data['result']	= 1;
		$data['msg']	= '등록/변경되었습니다.';

		return response()->json($data, 200);
	}



	/**
	 * 테스트 유저 삭제
	 *
	 *
	 */
	public function drop(Request $request)
	{
		$this->permission('maintenance', 'permission', 'outer0');


		//
		// 데이터 무결성 처리.
		//
		$data		= array
		(
			'result'	=>	0
		);

		// 데이터 검증
		$validator	= Validator::make(
			$request->input(),
			array
			(
				'user_seq'	=>	'required',
			));

		// 오류시 경고 리턴.
		if ($validator->fails())
		{
			$data['msg']	= $validator->errors()->first();
			return response()->json($data, 200);
		}


		// 사용할 데이터 셋팅.
		$user_seq	= $request->input('user_seq') ?? '';



		// 데이터 등록.
		$result		= TesterInfo::drop(
			$user_seq,
			);



		//
		// 결과 처리
		//
		$data['result']	= 1;
		$data['msg']	= '삭제 되었습니다.';

		return response()->json($data, 200);
	}




	public function search(Request $request)
	{
		$this->permission('maintenance', 'permission', 'outer0');


		//
		// 데이터 무결성 처리.
		//
		$data		= array
		(
			'result'	=>	0
		);


		$q			= $request->get('q') ?? '';
		$m			= $request->get('m') ?? '';

		if (!empty($q))
		{
			if (strtolower($m) == 'match')
				$result = TesterInfo::getTestUser($q, $q);
			else
				$result = AccountInfo::orwhere('user_seq', $q.'%')
					->orwhere('account', 'like', $q.'%')
					->orwhere('nickname', 'like', $q.'%')
					->get();

			$members = [];

			foreach ($result as $key => $row)
			{
				$members[] = [
					'user_seq'		=>	$row->user_seq,
					'nickname'		=>	$row->nickname,
					'account'	=>	$row->account,
				];
			}
		}

		$data['members'] = empty($members) ? [] : $members;


		//
		// 결과 처리
		//
		$data['result']	= 1;

		return response()->json($data, 200);
	}






}

