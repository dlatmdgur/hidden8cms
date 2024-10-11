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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use Illuminate\View\View;

use Spatie\Permission\Models\Permission;

use App\Model\Game\Present;
use App\Model\Relation;
use App\Model\Account\Member;
use App\Model\Account\Certification;
use stdClass;

class RelationController extends Controller
{
	/**
	 * 본 클래스 접근 권한 설정.
	 *
	 * @var array
	 */
	private $permissions = ['outer0', 'outer1', 'outer2', 'outer3', 'outer4', 'outer5'];

	/**
	 * 접속한 유저 댑스 관리
	 *
	 * @var string
	 */
	private $udepth = null;

	/**
	 * 접속 관리자 GAMEID
	 *
	 *
	 */
	private $gameseq = null;

	/**
	 * 본 클래스 접근 SYNC 타입 정의.
	 *
	 * @var boolean
	 */
	private $isAjax = false;

	/**
	 * 관계 타입명
	 *
	 * @var object
	 */
	private $depthname = [
		0	=>	'마스터',
		1	=>	'본사',
		2	=>	'부본',
		3	=>	'총판',
		4	=>	'대리점',
		5	=>	'매장',
	];

	/**
	 * CMS 계정별 권한 설정.
	 *
	 * @var object
	 */
	private $depthaccount = [

		//** 마스터 목록	**/
		'renas@nexnet.co.kr'		=>	[0, ''],
		'pltest@pole.com'			=>	[0, 'pltest'],



		//** 본사 목록		**/
		'wingame@swin.com'			=>	[1, 'wingame'],



		//** 부본			**/
		'donk777@swin.com'			=>	[2, 'donk777'],
		'qqwwee@swin.com'			=>	[2, 'qqwwee'],

		// DEVELOPMENT
		'qa21@swin.com'				=>	[2, 'qa21'],



		//** 총판			**/
		'ppoo01@swin.com'			=>	[3, 'ppoo01'],
		'aaoo1@swin.com'			=>	[3, 'aaoo1'],
		'wwoo1@swin.com'			=>	[3, 'wwoo1'],



		//** 대리점			**/
		'ppoo22@swin.com'			=>	[4, 'ppoo22'],
		'aaoo11@swin.com'			=>	[4, 'aaoo11'],
		'wwoo11@swin.com'			=>	[4, 'wwoo11'],



		//** 매장			**/
		'ppoo02@swin.com'			=>	[5, 'ppoo02'],
		'aaoo01@swin.com'			=>	[5, 'aaoo01'],
		'wwwoo01@swin.com'			=>	[5, 'wwwoo01'],



	];





	/**
	 * 코드 네이밍.
	 *
	 * @var object
	 */
	private $code = [
		'BACCARAT'		=>	'바카라',
		'BADUGI'		=>	'바둑이',
		'BLACKJACK'		=>	'블랙잭',
		'HOLDEM'		=>	'홀덤',
		'HOLDEMT'		=>	'홀덤(토너먼트)',
		'HOLDEMBADUGI'	=>	'홀덤바둑이',
		'SLOT'			=>	'슬롯',
	];

	/**
	 * 뎁스별 우편발송 코드 정의.
	 *
	 * @var object
	 */
	private $depth_seq = [
		0				=>	-3001,
		1				=>	-3002,
		2				=>	-3003,
	];

	/**
	 * 환급 최소금액
	 *
	 *
	 */
	private $refund_minimum = [
		0				=>	0,			// 마스터 최소 환급 금액
		1				=>	0,			// 본사 최소 환급 금액
		2				=>	0,			// 부본 최소 환급 금액
		3				=>	0,			// 총판 최소 환급 금액
		4				=>	0,			// 대리점 최소 환급 금액
		5				=>	100000,		// 매장 최소 환급 금액
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


		//
		$this->isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');


		//
		if (env('APP_ENV') != 'production')
		{
			$this->depthaccount['aacs@swin.com'] = [1, 'qa11'];
			$this->depthaccount['asb1@swin.com'] = [2, 'qa21'];
		}
	}



	/**
	 * 본 클래서 사용 함수 설정 예시.
	 *
	 * @var function
	 */
	private function permission()
	{
		$args = func_get_args();

		if (count($args) <= 0)
			$args = $this->permissions;

		$u				= Auth::user();

		$s				= isset($this->depthaccount[$u->email]);

		$status = 0;
		foreach ($args as $num => $val)
		{
			if ($u->hasPermissionTo($val))
			{
				if (!$s &&
					!isset($this->udepth))
					$this->udepth = $num;

				$status++;
			}
		}

		$this->udepth	= !$s ? $this->udepth : $this->depthaccount[$u->email][0];

		if ($status == 0)
		{
			header('Location: /blank');
			die();
		}

		foreach ($this->depthname as $key => $row)
		{
			if ($this->udepth > $key)
				unset($this->depthname[$key]);
		}

		if ($s)
		{
			$sid = $this->depthaccount[$u->email][1];

//			if (empty($sid))
//				$sid = explode('@', $u->email)[0];

			$m = Member::where('userid', $sid)->first();

			if (!empty($m->user_seq))
				$this->gameseq = $m->user_seq;
		}

		return true;
	}



	/**
	 * 추천인 조회.
	 *
	 * @param object $request
	 */
	public function search(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			'udepth'		=>	$this->udepth,

			'depthname'		=>	$this->depthname,

			'd'				=>	empty($request->input('d')) ? $request->query('d') : $request->input('d'),
			't'				=>	empty($request->input('t')) ? $request->query('t') : $request->input('t'),
			'q'				=>	empty($request->input('q')) ? $request->query('q') : $request->input('q'),

			'page'		=>	empty($request->input('page')) ? $request->query('page') : $request->input('page'),
			'limit'		=>	empty($request->input('limit')) ? $request->query('limit') : $request->input('limit'),
			'total_page'	=>	1,
			'start_page'	=>	1,
			'end_page'		=>	1,
		];

		$data['qs']		= $this->genQueryString($data);


		// 무결성 체크.
		$data['d']		= empty($data['d']) ? $this->udepth : max($this->udepth, $data['d']);

		$data['page']	= empty($data['page']) ? 1 : $data['page'];
		$data['limit']	= empty($data['limit']) ? 20 : $data['limit'];


		$result = Relation::getRelationUsers(
			$data['d'],
			$data['t'],
			$data['q'],
			$data['page'],
			$data['limit'],
			$this->gameseq,
			);


		$data['data']		= $result['data'];

		$data['count']		= $result['count'];
		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		foreach ($data['data'] as $key => $row)
			$data['data'][$key]->depthname = $this->depthname[$row->max_depth];


		return $this->isAjax === true ?
			response($data)->header('Content-Type', 'application/json'):
			view('relations.search', $data);
	}



	/**
	 * 추천인 상세정보
	 *
	 * @param object $request
	 */
	public function detail($idx)
	{
		$this->permission();


		$data = [];


		//
		// 마스터 정보.
		//
		$data['master']	= Relation::getRelationForIndex($idx)[0];
		$master			= Relation::getRelationForMaster($data['master']->user_seq);

		// 마스터
		$data['master']->depthname = $this->depthname[$data['master']->max_depth];


		//
		// 상위 유저 색인.
		//
		$result = Relation::getRelationParentForIndex($idx);
		$data['parents'] = count($result) > 0 ? $result[0]->parents : '';

		//
		// 요율 정보 색인.
		//
		$rates	= Relation::getRelationRates($data['master']->user_seq);

		if (count($rates) <= 0)
			$rates	= Relation::getRelationRates($master->parent_seq);

		$depthname = [];
		foreach ($rates as $key => $row)
			$depthname[$row->depth] = $row->depth_name;

		$data['max_depth'] = 0;
		foreach ($rates as $key => $row)
		{
			$data['max_depth'] = max($data['max_depth'], $row->depth);
			$data['rates'][$row->code][$row->depth] = $row;
		}

		$data['depthname']	= [];
		$data['codename']	= $this->code;


		//
		// 하위 유저 색인.
		//
		$result		= Relation::getRelationChildAllForIndex($idx);

		$users		= [];
		$childs		= [];
		$trees		= [];
		$rows		= [];

		$min		= 999;
		$max		= $data['max_depth'];


		foreach ($result as $key => $row)
		{
			$users[$row->parent_seq]	= [$row->puserid, $row->pnickname];
			$users[$row->user_seq]		= [$row->userid, $row->nickname];

			if (empty($childs[$row->parent_seq]))
				$childs[$row->parent_seq] = [];

			$childs[$row->parent_seq][] = $row->user_seq;

			$parents[$row->user_seq]	= $row->parent_seq;

			if (empty($trees[$row->depth]))
				$trees[$row->depth] = [];

			if (!in_array($row->user_seq, $trees[$row->depth]))
				$trees[$row->depth][] = $row->user_seq;

			$min = intval(min($min, $row->depth));
			$max = intval(max($max, $row->depth));
		}


		$data['html'] = '';

		if ($min < $max)
		{
			$temps	= [];
			$rows	= [];

			foreach ($trees[$min] as $seq)
				$temps[$seq] = $this->dataSerialize($childs, $max, $min, $seq);

			$this->depthCount($rows, $temps);


			$html = '<table class="table table-bordered"><thead class="thead-dark text-center"><tr>';

			for ($i = $min + 1; $i <= $max; $i++)
				$html .= '<th width="20%">'.$depthname[$i].'</th>';

			$html .= '</tr></thead><tbody><tr class="text-center">'.$this->genChildBody($temps, $rows, $users, $min, $max).'</tr></tbody></table>';

			$data['html']	= $html;
		}
		else
		{
			$min = $max;
		}

		for ($i = $min; $i <= $max; $i++)
			$data['depthname'][$i] = @$depthname[$i];



		//
		// 뎁스별 유저&포인트 합계 산출.
		//
		$summary = Relation::getRelationChildDepthPoint($idx);

		$total_user = [0, 0];
		$total_point = 0;

		foreach ($summary as $key => $row)
		{
			if (!empty($this->depthname[$row->depth - 1]))
			{
				$summary[$key]->depthname = $this->depthname[$row->depth - 1];

				$total_user[0] += intval($row->member);
				$total_point += floatval($row->point);
			}
			else
			{
				$total_user[1] += intval($row->member);
//				unset($summary[$key]);
				$summary[$key]->depthname = '유저';
			}
		}

		$data['summary']		= $summary;

		$data['total_user']		= $total_user;
		$data['total_point']	= $total_point;


		return view('relations.detail', $data);
	}



	public function usearch($search)
	{
		$data = [
			'result'	=>	0,
		];


		if (empty($search))
		{
			$data['result']	= 1;
			$data['msg']	= '유저를 찾을 수 없습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		$data['data'] = [];

		$members = Relation::getChildMember($search);
		foreach ($members as $key => $row)
		{
			if (empty($row->relation_seq))
				array_push($data['data'], ['user_seq' => $row->user_seq, 'userid' => $row->userid, 'nickname' => $row->nickname]);
		}

		if (count($data['data']) <= 0)
			$data['msg'] = '검색된 유저가 없습니다.';


		return response($data)->header('Content-Type', 'application/json');
	}



	public function parents($depth)
	{
		$data = [
			'result'	=>	0
		];

		$data['data'] = Relation::getParentMember($depth);

		if (count($data['data']) <= 0)
			$data['msg'] = '검색된 유저가 없습니다.';


		return response($data)->header('Content-Type', 'application/json');
	}



	public function childs($parent_seq, $depth, $keyword='')
	{
		$data = [
			'result'	=>	0,
			'depthname'	=>	$this->depthname,
		];

		$data['data'] = Relation::getChildMembers($parent_seq, $depth, $keyword);

		if (count($data['data']) <= 0)
			$data['msg'] = '검색된 유저가 없습니다.';


		return response($data)->header('Content-Type', 'application/json');
	}



	public function set(Request $request)
	{
		$data = array
		(
			'result'	=>	1
		);


		$depth		= $request->input('depth');
		$seq		= $request->input('seq');
		$parent_seq	= $request->input('parent_seq');


		if (empty($depth))
		{
			$parent_seq = 0;
			$data['msg'] = '당분간 마스터는 등록 사절입니다.';
			return response($data)->header('Content-Type', 'application/json');
		}
		else
		{
			if (empty($parent_seq))
			{
				$data['msg'] = '상위 유저가 올바르지 않습니다.';
				return response($data)->header('Content-Type', 'application/json');
			}
		}

		if (count($seq) <= 0)
		{
			$data['msg'] = '등록할 대상 유저가 올바르지 않습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		foreach ($seq as $key => $val)
		{
			$result = Relation::setMemberRelation(
				$parent_seq,
				intval($val)
				);

			error_log('setMemberRelation ::'.implode(', ', [$parent_seq, $val, $result]));
		}


		$data['result']	= 0;
		$data['msg']	= '등록되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function drop(Request $request)
	{
		$data = array
		(
			'result'	=>	1
		);


		$user_seq = $request->input('user_seq');

		if (empty($user_seq))
		{
			$data['msg'] = '제거 대상이 올바르지 않습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}

		$childs = Relation::getChildMembers(
			$user_seq,
			-1,
			''
			);

		if (count($childs) > 0)
		{
			$data['msg'] = '등록할 대상 유저가 올바르지 않습니다.';
			return response($data)->header('Content-Type', 'application/json');
		}


		$result = Relation::dropMemberRelation(
			$user_seq
			);


		$data['result']	= 0;
		$data['msg']	= '관계가 제거되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function rates($seq)
	{
		$this->permission();


		$data = [
			'result'	=>	0,
			'code'		=>	$this->code,
		];


		//
		// 마스터 정보.
		//
		$master = Relation::getRelationForUserSeq($seq)[0];
		$data['master_seq'] = $master->user_seq;


		//
		// 요율 정보 색인.
		//
		$rates	= Relation::getRelationRates($master->user_seq);

		if (count($rates) <= 0)
		{
			$master	= Relation::getRelationForMaster($master->user_seq);
			$rates	= Relation::getRelationRates($master->parent_seq);
		}

		foreach ($rates as $key => $row)
		{
			if ($row->depth <= $this->udepth)
				continue;

			$data['rates'][$row->code][$row->depth] = $row;
		}

		foreach ($this->code as $key => $name)
		{
			if (!empty($data['rates'][$key]))
				continue;


			foreach ($this->depthname as $i => $name)
			{
				$data['rates'][$key][$i+1] = [
					"rel_seq"		=>	$master->user_seq,
					"depth"			=>	$i+1,
					"code"			=>	$key,
					"rate1"			=>	0.00,
					"rate2"			=>	0.00,
					"rate3"			=>	0.00,
					"depth_name"	=>	$name,
					"updated"		=>	date('Y-m-d H:i:s'),
					"created"		=>	date('Y-m-d H:i:s'),
				];
			}
		}

		foreach ($this->depthname as $i => $name)
			$data['depthname'][] = $name;

		$data['udepth'] = $this->udepth;

		return response($data)->header('Content-Type', 'application/json');
	}



	public function setrates(Request $request)
	{
		$data = array
		(
			'result'	=>	0
		);


		$user_seq	= $request->input('user_seq');
		$rates		= $request->input('rates');
		$parent_seq	= $user_seq;


		$master		= Relation::getRelationForMaster($user_seq);
		if ($master->depth > 0)
			$parent_seq = $master->parent_seq;


		//
		// 최대 요율값 색인.
		//
		$maxrates	= Relation::getRelationConfig($parent_seq, null, 'MAX_RATE');

		$prates		= [];

		foreach (Relation::getRelationRates($parent_seq) AS $row)
			$prates[$row->code][$row->depth] = floatval($row->rate1) + floatval($row->rate2) + floatval($row->rate3);

		$urates		= [];
		if ($parent_seq != $user_seq)
		{
			foreach (Relation::getRelationRates($user_seq) AS $row)
				$urates[$row->code][$row->depth] = floatval($row->rate1) + floatval($row->rate2) + floatval($row->rate3);
		}
		else
			$urates	= $prates;


		//
		// 기본 요율만큼 반복
		//
		foreach ($prates as $c => $rows)
		{
			$ratesum = 0;

			$summary = [];

			foreach ($rows as $d => $prate)
			{
				$summary[] = implode(' - ', [$c, $d, !empty($rates[$c][$d]) ? 'I : '.$rates[$c][$d] : (!empty($urates) ? 'U : '.$urates[$c][$d] : 'P : '.$prate)]);
				$ratesum += !empty($rates[$c][$d]) ? $rates[$c][$d] : (!empty($urates) ? $urates[$c][$d] : $prate);
			}

			error_log("\n".implode("\n", $summary));

			if (empty($maxrates[$c]))
				$maxrates[$c] = ['MAX_RATE' => 0];

			if ($maxrates[$c]['MAX_RATE'] < $ratesum)
			{
				$data['result']	= 1;
				$data['msg']	= "게임타입 [ ".$this->code[$c]." ] 의 최대 설정요율을 초과하였습니다.\n\n    입력값 : {$ratesum} %\n    최대값 : {$maxrates[$c]['MAX_RATE']} %";
				return response($data)->header('Content-Type', 'application/json');
			}
		}


//		foreach ($rates as $code => $rows)
//		{
//			$ratesum = 0;
//
//			foreach ($rows as $depth => $rate)
//				$ratesum += $rate;
//
//			if ($maxrates[$code]['MAX_RATE'] < $ratesum)
//			{
//				$data['result']	= 1;
//				$data['msg']	= $this->code[$code].' 게임타입의 최대 설정요율을 초과하였습니다.';
//				return response($data)->header('Content-Type', 'application/json');
//			}
//		}


		foreach ($rates as $code => $rows)
		{
			foreach ($rows as $depth => $rate)
			{
				Relation::setRelationRates(
					$user_seq,
					$code,
					$depth,
					$rate,
					$this->depthname[$depth - 1]
					);
			}
		}


		$data['msg'] = '등록되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function setuserrate(Request $request)
	{
		$data = array
		(
			'result'	=>	0
		);


		$user_seq	= $request->input('user_seq');
		$rates		= $request->input('rates');


		foreach ($rates as $code => $rows)
		{
			foreach ($rows as $depth => $rate)
			{
				Relation::setRelationRates(
					$user_seq,
					$code,
					$depth,
					$rate,
					$this->depthname[$depth - 1]
					);
			}
		}

		$data['msg'] = '등록되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function points(Request $request)
	{
		$this->permission();


		$data = [
			'result'	=>	0,
			'msg'		=>	'',

			'depthname'	=>	$this->depthname,

			'd'			=>	empty($request->input('d')) ? $request->query('d') : $request->input('d'),
			't'			=>	empty($request->input('t')) ? $request->query('t') : $request->input('t'),
			'q'			=>	empty($request->input('q')) ? $request->query('q') : $request->input('q'),

			's'			=>	empty($request->input('s')) ? $request->query('s') : $request->input('s'),
			'e'			=>	empty($request->input('e')) ? $request->query('e') : $request->input('e'),

			'page'		=>	empty($request->input('page')) ? $request->query('page') : $request->input('page'),
			'limit'		=>	empty($request->input('limit')) ? $request->query('limit') : $request->input('limit'),
		];

		$data['s']		= empty($data['s']) ? date('Y-m-d') : $data['s'];
		$data['e']		= empty($data['e']) ? date('Y-m-d') : $data['e'];

		$data['qs']		= $this->genQueryString($data);


		// 무결성 체크.
		$data['d']		= empty($data['d']) ? $this->udepth : max($this->udepth, $data['d']);

		$data['page']	= empty($data['page']) ? 1 : $data['page'];
		$data['limit']	= empty($data['limit']) ? 20 : $data['limit'];


		//
		// 포인트 적립 내역 색인.
		//
		$result = Relation::getRelationPoints(
			$data['d'],
			$data['t'],
			$data['q'],
			$data['s'],
			$data['e'],
			$data['page'],
			$data['limit'],
			$this->gameseq,
			);

		$data['points'] = $result['data'];

		$data['count']		= $result['count'];
		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];

		return view('relations.points', $data);
	}



	public function pointdetail($user_seq, $date, $page=1, $limit=20)
	{
		$data = [
			'result'	=>	0,
			'msg'		=>	'',

			'page'		=>	empty($page) ? 1 : intval($page),
			'limit'		=>	empty($limit) ? 20 : intval($limit),

			'data'		=>	[]
		];


		$data['qs']	= implode('/', ['/relations', 'pointdetail', $user_seq, $date, '%PAGE%', $limit]);


		$result = Relation::getRelationPointLogs(
			$user_seq,
			$date,
			$page,
			$limit
			);


		foreach ($result['data'] as $key => $row)
		{
			$row->codename		= $this->code[$row->code];
			$row->date			= implode('-', [substr($row->datecode, 0, 4), substr($row->datecode, 4, 2), substr($row->datecode, 6, 2)]);

			$row->total_spend	= $row->spend + $row->purchase;

			$data['data'][] = $row;
		}


		$data['count']		= $result['count'];
		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		return response($data)->header('Content-Type', 'application/json');
	}



	public function depthdetail($user_seq, $depth, $page=1, $limit=20)
	{
		$data = [
			'result'	=>	0,
			'msg'		=>	'',

			'page'		=>	empty($page) ? 1 : intval($page),
			'limit'		=>	empty($limit) ? 20 : intval($limit),

			'data'		=>	[]
		];


		$data['qs']	= implode(':', [$user_seq, $depth, $limit]).':';


		$result = Relation::getRelationMembers(
			$user_seq,
			$depth,
			$page,
			$limit
			);

		foreach ($result['data'] as $key => $row)
		{
			$row->depthname = empty($this->depthname[$row->max_depth]) ? '유저' : $this->depthname[$row->max_depth];
			$data['data'][] = $row;
		}


		$data['count']		= $result['count'];
		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		return response($data)->header('Content-Type', 'application/json');
	}



	public function memberdetail($user_seq)
	{
		$this->permission();

		$data = [
			'result'	=>	0,
			'user_seq'	=>	$user_seq,
		];



		//
		// 포인트 적립 내역 색인.
		//
		$data['member'] = Relation::getRelationMemberDetail(
			$user_seq
			);

		$childs = Relation::getChildMembers(
			$user_seq,
			-1,
			''
			);

		$data['childs'] = count($childs);


		return $this->isAjax === true ?
			response($data)->header('Content-Type', 'application/json') :
			view('relations.memberdetail', $data);
	}



	public function memberset(Request $request)
	{
		$data = array
		(
			'result'	=>	0
		);


		$method		= $request->input('method');
		$user_seq	= $request->input('user_seq');


		try {
			if (empty($method))
				throw new Exception('변경 대상이 올바르지 않습니다.');

			switch ($method)
			{
				case 'nickname':
					$nickname	= $request->input('nickname');

					$result		= Relation::setMemberNickname(
						$user_seq,
						$nickname
						);
					break;


				case 'name':
					$name		= $request->input('name');
					$sex		= $request->input('sex');

					$result		= Relation::setMemberName(
						$user_seq,
						$name,
						$sex
						);
					break;


				case 'cert':
					$cert		= $request->input('cert');

					if ($cert == 'Y')
						Certification::passPlatformCertification($user_seq);
					else
						Certification::cancelPlatformCertification($user_seq);
					break;


				case 'phone':
					$phone		= $request->input('phone');
					$auth		= $request->input('phone_auth');

					$result		= Relation::setMemberPhone(
						$user_seq,
						$phone,
						$auth
						);
					break;

			}
		}
		catch (Exception $e)
		{
			$data['result']	= 1;
			$data['msg']	= $e->getMessage();

			return response($data)->header('Content-Type', 'application/json');
		}



		$data['msg'] = '등록되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function rewards(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			'depthname'		=>	$this->depthname,

			'd'				=>	empty($request->input('d')) ? $request->query('d') : $request->input('d'),
			't'				=>	empty($request->input('t')) ? $request->query('t') : $request->input('t'),
			'q'				=>	empty($request->input('q')) ? $request->query('q') : $request->input('q'),

			'page'			=>	empty($request->input('page')) ? $request->query('page') : $request->input('page'),
			'limit'			=>	empty($request->input('limit')) ? $request->query('limit') : $request->input('limit'),
			'total_page'	=>	1,
			'start_page'	=>	1,
			'end_page'		=>	1,
		];

		$data['qs']		= $this->genQueryString($data);


		// 무결성 체크.
		$data['d']		= empty($data['d']) ? $this->udepth : max($this->udepth, $data['d']);

		$data['page']	= empty($data['page']) ? 1 : $data['page'];
		$data['limit']	= empty($data['limit']) ? 20 : $data['limit'];


		$result = Relation::getRelationUsers(
			$data['d'],
			$data['t'],
			$data['q'],
			$data['page'],
			$data['limit'],
			$this->gameseq,
			);

		$data['data'] = $result['data'];

		$data['count']		= $result['count'];
		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		foreach ($data['data'] as $key => $row)
			$data['data'][$key]->depthname = $this->depthname[$row->max_depth];


		return $this->isAjax === true ?
			response($data)->header('Content-Type', 'application/json'):
			view('relations.rewards', $data);
	}



	public function rewardset(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			'parent_seq'	=>	empty($request->input('parent_seq')) ? null : $request->input('parent_seq'),
			'depth'			=>	empty($request->input('parent_depth')) ? 0 : $request->input('parent_depth'),
			'picks'			=>	empty($request->input('picks')) ? null : $request->input('picks'),
			'point'			=>	empty($request->input('point')) ? null : $request->input('point'),
			'reason'		=>	empty($request->input('reason')) ? null : $request->input('reason'),
		];


		$result = Relation::sendMemberRelationPoint(
			$this->depth_seq[$data['depth']],
			$data['parent_seq'],
			implode(',', $data['picks']),
			$data['point'],
			$data['reason']
			);

		if ($result == false)
		{
			$data['result']	= 1;
			$data['msg']	= '요청을 처리하는 중 문제가 발생했습니다. (-1)';

			return response($data)->header('Content-Type', 'application/json');
		}

		if ($result->errno > 0)
		{
			$data['result']	= $result->errno;
			$data['msg']	= $result->message;

			return response($data)->header('Content-Type', 'application/json');
		}

		$result = Relation::getRelationForUserSeq($data['parent_seq']);

		$data['total_point'] = 0;
		if ($result != false)
			$data['total_point'] = $result[0]->point;


		$data['result'] = 0;
		$data['msg'] = '처리되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function refunds(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			'depthname'		=>	$this->depthname,

			'd'				=>	empty($request->input('d')) ? $request->query('d') : $request->input('d'),
			't'				=>	empty($request->input('t')) ? $request->query('t') : $request->input('t'),
			'q'				=>	empty($request->input('q')) ? $request->query('q') : $request->input('q'),

			'page'			=>	empty($request->input('page')) ? $request->query('page') : $request->input('page'),
			'limit'			=>	empty($request->input('limit')) ? $request->query('limit') : $request->input('limit'),
			'total_page'	=>	1,
			'start_page'	=>	1,
			'end_page'		=>	1,
		];

		$data['qs']		= $this->genQueryString($data);


		// 무결성 체크.
		$data['d']		= empty($data['d']) ? $this->udepth : max($this->udepth, $data['d']);

		$data['page']	= empty($data['page']) ? 1 : $data['page'];
		$data['limit']	= empty($data['limit']) ? 20 : $data['limit'];


		// 최소 환급 금.
		$data['minimum']	= $this->refund_minimum[$data['d']];


		$result = Relation::getRelationUsers(
			$data['d'],
			$data['t'],
			$data['q'],
			$data['page'],
			$data['limit'],
			$this->gameseq,
			);

		$data['data'] = $result['data'];

		$data['count']		= $result['count'];
		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		foreach ($data['data'] as $key => $row)
			$data['data'][$key]->depthname = $this->depthname[$row->max_depth];


		return $this->isAjax === true ?
			response($data)->header('Content-Type', 'application/json'):
			view('relations.refunds', $data);
	}



	public function refundset(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			'parent_seq'	=>	empty($request->input('parent_seq')) ? null : $request->input('parent_seq'),
			'depth'			=>	empty($request->input('parent_depth')) ? 0 : $request->input('parent_depth'),
			'point'			=>	empty($request->input('point')) ? null : $request->input('point'),
			'reason'		=>	empty($request->input('reason')) ? null : $request->input('reason'),
		];


		$result = Relation::sendMemberRelationPoint(
			$this->depth_seq[$data['depth']],
			$data['parent_seq'],
			$data['parent_seq'],
			$data['point'],
			$data['reason']
			);

		if ($result == false)
		{
			$data['result']	= 1;
			$data['msg']	= '요청을 처리하는 중 문제가 발생했습니다. (-1)';

			return response($data)->header('Content-Type', 'application/json');
		}

		if ($result->errno > 0)
		{
			$data['result']	= $result->errno;
			$data['msg']	= $result->message;

			return response($data)->header('Content-Type', 'application/json');
		}


		$data['result'] = 0;
		$data['msg'] = '처리되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function rewardcancel(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			'idx'			=>	empty($request->input('idx')) ? null : $request->input('idx'),
			'reason'		=>	empty($request->input('reason')) ? null : $request->input('reason')
		];


		// 취소 스크립트 처리.
		$result = Relation::cancelMemberRelationPointReward(
			$data['idx'],
			$data['reason']
			);

		if ($result == false)
		{
			$data['result']	= 1;
			$data['msg']	= '요청을 처리하는 중 문제가 발생했습니다. (-1)';

			return response($data)->header('Content-Type', 'application/json');
		}

		if ($result->errno > 0)
		{
			$data['result']	= $result->errno;

			switch ($result->errno)
			{
				case 10001:
					$data['msg'] = '처리할 수 없는 요청입니다.';
					break;

				case 10002:
					$data['msg'] = '이미 취소된 요청입니다.';
					break;

				case 10003:
					$data['msg'] = '유저가 이미 보상받아 처리할 수 없습니다.';
					break;
			}

			return response($data)->header('Content-Type', 'application/json');
		}



		$data['result'] = 0;
		$data['msg'] = '처리되었습니다.';
		return response($data)->header('Content-Type', 'application/json');
	}



	public function rewardlogs(Request $request)
	{
		$this->permission();


		$data = [
			'result'		=>	0,
			'msg'			=>	'',

			't'				=>	empty($request->input('t')) ? $request->query('t') : $request->input('t'),
			'q'				=>	empty($request->input('q')) ? $request->query('q') : $request->input('q'),

			'page'			=>	empty($request->input('page')) ? $request->query('page') : $request->input('page'),
			'limit'			=>	empty($request->input('limit')) ? $request->query('limit') : $request->input('limit'),
			'total_page'	=>	1,
			'start_page'	=>	1,
			'end_page'		=>	1,
		];

		$data['qs']		= $this->genQueryString($data);


		// 무결성 체크.
		$data['d']		= empty($data['d']) ? $this->udepth : max($this->udepth, $data['d']);

		$data['page']	= empty($data['page']) ? 1 : $data['page'];
		$data['limit']	= empty($data['limit']) ? 20 : $data['limit'];


		$result = Relation::getMemberRelationSendPointLogs(
			$data['t'],
			$data['q'],
			$data['page'],
			$data['limit'],
			$this->gameseq,
			);

		$data['data']		= $result['data'];
		$data['count']		= $result['count'];

		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];


		return $this->isAjax === true ?
			response($data)->header('Content-Type', 'application/json'):
			view('relations.rewardlogs', $data);
	}


	/**
	 * 실시간 유저 페이지
	 *
	 * @param Request $request
	 * @return void
	 */
	public function userstat(Request $request)
	{

		// 필수 값 초기화
		$data = [
			'result'		=>	0,
			'ccu'			=>	[]
		];

		// redis 접속
		$poker = Redis::connection('redis_poker');
		$slots = Redis::connection('redis_slots');


		$ccu = new stdClass();

		$ccu->holdem_reg_users 			= 0;
		$ccu->holdemb_reg_users 		= 0;
		$ccu->blackjack_reg_users		= 0;
		$ccu->baccarat_reg_users		= 0;
		$ccu->slot_reg_users			= 0;

		$nowSlotUser		= $slots->keys('USER_SOCK*');
		$nowHoldemUser      = $poker->smembers('holdem_ingame');
		$nowBadugiUser      = $poker->smembers('badugi_ingame');
		$nowBlackJackUser	= $poker->smembers('blackjack_ingame');
		$nowBaccaratUser	= $poker->smembers('baccarat_ingame');

		$ccu->holdem		= count($nowHoldemUser);
		$ccu->holdemb		= count($nowBadugiUser);
		$ccu->blackjack		= count($nowBlackJackUser);
		$ccu->baccarat		= count($nowBaccaratUser);
		$ccu->slot			= count($nowSlotUser);

		//접속해 있는 유저가 없으면 굳이 가져올 필요가 없다.
		if ( ($ccu->holdem + $ccu->holdemb + $ccu->blackjack + $ccu->baccarat + $ccu->slot) > 0)
		{
			$path = storage_path('reg_users').'.txt';
			$regUsers = fopen($path, 'r');

			while (!feof($regUsers))
			{
				$userSeq = trim(fgets($regUsers));

				if ($userSeq === '')
					continue;

				//슬롯 유저의 경우
				foreach ($nowSlotUser as $idx => $val)
				{
					$extUseq = str_replace('USER_SOCK_', '', $val);

					if ($userSeq === $extUseq)
					{
						$ccu->slot_reg_users++;
						unset($nowSlotUser[$idx]);
					}

				}

				//홀덤의 경우
				foreach ($nowHoldemUser as $idx => $pUseq)
				{
					if ($pUseq === $userSeq)
					{
						$ccu->holdem_reg_users++;
						unset($nowHoldemUser[$idx]);
					}
				}

				//바둑이의 경우
				foreach ($nowBadugiUser as $idx => $pUseq)
				{
					if ($pUseq === $userSeq)
					{
						$ccu->holdemb_reg_users++;
						unset($nowBadugiUser[$idx]);
					}
				}

				//블랙잭의 경우
				foreach ($nowBlackJackUser as $idx => $pUseq)
				{
					if ($pUseq === $userSeq)
					{
						$ccu->blackjack_reg_users++;
						unset($nowBlackJackUser[$idx]);
					}
				}

				//바카라의 경우
				foreach ($nowBaccaratUser as $idx =>$pUseq)
				{
					if ($pUseq === $userSeq)
					{
						$ccu->baccarat_reg_users++;
						unset($nowBaccaratUser[$idx]);
					}
				}

			}

			fclose($regUsers);
		}

		$data['ccu']	= $ccu;

		return $this->isAjax === true ?
			response($data)->header('Content-Type', 'application/json'):
			view('relations.userstat', $data);
	}



	public function logs(Request $request, $parent='')
	{
		$this->permission();


		$data = [
			'parent'		=>	$parent,
			'depth'			=>	0,

			's'				=>	empty($request->input('s')) ? $request->query('s') : $request->input('s'),
			'e'				=>	empty($request->input('e')) ? $request->query('e') : $request->input('e'),

			't'				=>	empty($request->input('t')) ? $request->query('t') : $request->input('t'),
			'q'				=>	empty($request->input('q')) ? $request->query('q') : $request->input('q'),

			'page'			=>	empty($request->input('page')) ? $request->query('page') : $request->input('page'),
			'limit'			=>	empty($request->input('limit')) ? $request->query('limit') : $request->input('limit'),
			'total_page'	=>	1,
			'start_page'	=>	1,
			'end_page'		=>	1,

			'depthname'		=>	[
				0	=>	'마스터',
				1	=>	'본사',
				2	=>	'부본',
				3	=>	'총판',
				4	=>	'대리점',
				5	=>	'매장',
			],
		];

		$data['s']	= empty($data['s']) ? date('Y-m-d') : $data['s'];
		$data['e']	= empty($data['e']) ? date('Y-m-d') : $data['e'];

		$data['page']	= empty($data['page']) ? 1 : intval($data['page']);
		$data['limit']	= empty($data['limit']) ? 20 : intval($data['limit']);


		if (empty($data['parent']) &&
			!empty($this->gameseq))
			$data['parent'] = $this->gameseq;

		if (!empty($data['parent']))
		{
			$result = Relation::getRelationForUserSeq($data['parent']);

			if (count($result) > 0 &&
				!empty($result[0]->userid))
				$data['depth'] = intval($result[0]->depth) + 1;
		}



		if (count($data['depthname']) == $data['depth'])
		{
			$result = Relation::getUserLogs(
				$data['parent'],
				$data['depth'],
				$data['t'],
				$data['q'],
				$data['s'],
				$data['e'],
				$data['page'],
				$data['limit']
				);
		}
		else
		{
			$result = Relation::getLogs(
				$data['parent'],
				$data['depth'],
				$data['t'],
				$data['q'],
				$data['s'],
				$data['e'],
				$data['page'],
				$data['limit']
				);
		}


		$data['data']		= $result['data'];
		$data['count']		= $result['count'];

		$data['total_page']	= $data['count'] > 0 ? ceil($data['count'] / $data['limit']) : 1;
		$data['start_page']	= ((ceil($data['page'] / 10) - 1) * 10) + 1;
		$data['end_page']	= ceil($data['page'] / 10) * 10;
		$data['end_page']	= $data['end_page'] >= $data['total_page'] ? $data['total_page'] : $data['end_page'];

		$data['qs']		= $this->genQueryString($data);


		return view('relations.logs', $data);
	}



	private function genQueryString($data)
	{
		$qs = [];

		foreach ($data as $key => $val)
		{
			switch ($key)
			{
				// 페이지당 표시 갯수
				case 'limit':
				// 댑스 타입
				case 'd':
				// 검색 대상
				case 't':
				// 검색어
				case 'q':
				// 시작일
				case 's':
				// 종료일
				case 'e':
					array_push($qs, implode("=", [$key, $val]));
					break;
			}
		}

		return implode("&", $qs);
	}



	private function dataSerialize($childs, $max, $dep, $pseq)
	{
		$ret = [];

		if ($max > $dep+1 &&
			!empty($childs[$pseq]))
		{
			foreach ($childs[$pseq] as $seq)
				$ret[$seq] = $this->dataSerialize($childs, $max, $dep+1, $seq);
		}
		else
			$ret = $pseq;

		return $ret;
	}



	/**
	 * 테이블
	 *
	 *
	 */
	private function genChildBody($childs, $rows, $users, $min, $max)
	{
		$ret = '';

		if (!is_array($childs))
			return $ret;

		$n = 0;
		foreach ($childs as $seq => $val)
		{
			if ($n > 0)
				$ret .= '</tr><tr class="text-center">';

			if (is_array($val))
				$ret .= '<td width="10%" rowspan="'.$rows[$seq].'"><span data-seq="'.$seq.'" data-drop="0" data-toggle="modal" data-target="#info">'.(empty($users[$seq][1]) ? 'UNKNOWN' : $users[$seq][1]).' ( '.$users[$seq][0].' )'.'</span></td>'.(is_array($val) ? $this->genChildBody($val, $rows, $users, $min+1, $max) : '');
			else
			{
				$ret .= '<td width="10%"><span data-seq="'.$val.'" data-drop="1" data-toggle="modal" data-target="#info">'.(empty($users[$seq][1]) ? 'UNKNOWN' : $users[$seq][1]).' ( '.$users[$val][0].' )'.'</span></td>';
				for ($i = $min + 1; $i < $max; $i++)
					$ret .= '<td width="10%">-</td>';
//				$ret .= '<td width="10%">'.($min + 1 == $max ? '<button type="button" class="btn btn-sm btn-info text-nowrap" data-user-seq="'.$val.'" data-toggle="modal" data-target="#rates">요율변경</button>' : '-').'</td>';
			}

			$n++;
		}

		return $ret;
	}


	/**
	 * 댑스별 깊이 색인.
	 *
	 * @param string $ret 취합 데이터 ( 포인터 )
	 * @param array $childs 참조 하위 데이터
	 * @return integer 하위 카운트 수
	 */
	private function depthCount(&$ret, $childs)
	{
		$cnt = 0;

		if (is_array($childs))
		{
			foreach ($childs as $seq => $row)
			{
				if (empty($ret[$seq]))
					$ret[$seq] = 0;

				$ret[$seq] += is_array($row) ? $this->depthCount($ret, $row) : 1;
				$cnt += is_array($row) ? $ret[$seq] : 1;
			}
		}

		return $cnt;
	}




}

