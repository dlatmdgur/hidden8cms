<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;



class SlotLogV2 extends BaseModel
{
    protected $connection = 'slot_log';



	private static function buildInsertQuery($table, $data)
	{
		$cols		= array ();
		$vals		= array ();
		$params		= array ();
		$updates	= array ();

		foreach ($data as $key => $val)
		{
			array_push($cols,		$key);
			array_push($vals,		'?');
			array_push($params,		$val);
			array_push($updates,	$key.'=?');
		}

		$query		= 'INSERT INTO '.$table.' ('.implode(', ', $cols).') VALUES ('.implode(', ', $vals).') ON DUPLICATE KEY UPDATE '.implode(', ', $updates).' ';
		$params		= array_merge($params, $params);

		return array
		(
			'query'		=>	$query,
			'params'	=>	$params
		);
	}



	/**
	 * 게임로그 색인.
	 *
	 * @param string $date 검색일
	 * @param string $stime 검색 시작 시간
	 * @param string $etime 검색 종료 시간
	 * @param integer $uid 검색할 유저 (선택)
	 * @param string $slot_id 검색할 슬롯 (선택)
	 * @param string $game_type 로그 유형 (선택)
	 * @param integer $page 검색할 페이지
	 * @param integer $limit 페이지당 데이터 갯수
	 * @return array
	 */
    public static function getGameLog($date, $stime, $etime, $uid=null, $slot_id=null, $game_type=null, $page=1, $limit=20)
	{
		$retval = array ();


//		$tbl_name	= 'sw_slot_log_'.str_replace('-', '', $date);
		$tbl_name	= 'sw_slot_log';


		$start		= ($page - 1) * $limit;
		$end		= $limit;

		$start_date	= $date.' '.$stime.':00';
		$end_date	= $date.' '.$etime.':59';

		$query_c	= "SELECT COUNT(*) AS `count`, SUM(aft_coins + aft_bonus - bef_coins - bef_bonus) AS `increase` FROM ".$tbl_name." WHERE 1 ";
		$query_l	= "SELECT * FROM (SELECT idx FROM ".$tbl_name." WHERE 1 ";

		$params		= array ();

		if (!empty($uid))
		{
			$query_c .= "AND uid=? ";
			$query_l .= "AND uid=? ";
			array_push($params, $uid);
		}

		if (!empty($slot_id))
		{
			$query_c .= "AND slot_id=? ";
			$query_l .= "AND slot_id=? ";
			array_push($params, $slot_id);
		}

        if (!empty($game_type))
        {
            $query_c .= "AND game_type=? ";
            $query_l .= "AND game_type=? ";
            array_push($params, $game_type);
        }

		$query_c	.= " AND `created` BETWEEN ? AND ? ";
		$query_l	.= " AND `created` BETWEEN ? AND ? ";
		array_push($params, $start_date, $end_date);

		$result_count = collect(DB::connection('slot_log')->select($query_c, $params));
		$retval['count'] = !isset($result_count[0]->count) ? 0 : $result_count[0]->count;
        $retval['increase'] = !isset($result_count[0]->increase) ? 0 : $result_count[0]->increase;

		$query_l	.= "ORDER BY idx DESC LIMIT ?, ?) AS a ";
		$query_l	.= "INNER JOIN ".$tbl_name." AS b ON a.idx = b.idx ";
		array_push($params, $start, $end);

		$result = collect(DB::connection('slot_log')->select($query_l, $params));
		$retval['data'] = array ();


		foreach ($result as $key => $row)
		{
			$row->results	= json_decode($row->results);
			$row->pick		= json_decode($row->pick);
			array_push($retval['data'], $row);
		}


		return $retval;
	}



	/**
	 * 기간잭팟 지급 유저 색인.
	 *
	 * @param integer $idx 검색할 기간잭팟 INDEX (선택)
	 * @param integer $scode 검색할 시작 코드 (선택)
	 * @param integer $ecode 검색할 종료 코드 (선택)
	 * @return array
	 */
	public static function getAssignJackpotUsers($idx=null, $scode=null, $ecode=null)
	{
		$query	= 'SELECT * FROM sw_assigns.sw_jackpot_users WHERE 1 ';
		$params = [];

		if (!empty($idx))
		{
			$query .= ' AND jackpot_idx = ? ';
			array_push($params, $idx);
}

		if (!empty($scode) &&
			!empty($ecode))
		{
			if ($scode == $ecode)
			{
				$query .= ' AND nowcode = ? ';
				array_push($params, $scode);
			}
			else
			{
				$query .= ' AND nowcode BETWEEN ? AND ? ';
				array_push($params, $scode, $ecode);
			}
		}

		return collect(DB::connection('slot_log')->select($query, $params));
	}



}
