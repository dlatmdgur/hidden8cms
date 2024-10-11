<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;


class SlotInfoV2 extends BaseModel
{
	protected $connection = 'slot_system';

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
	 * 슬롯 데이터 조회.
	 *
	 *
	 */
	public static function getSlots($slot_id='')
	{
		$query = 'SELECT
			*
		FROM
			sw_refer_slots
		WHERE
			1
		';
		$params = [];

		if (!empty($slot_id))
		{
			$query .= 'AND slot_id = ? ';
			array_push($params, $slot_id);
		}

		$query .= 'ORDER BY
			 sorted ASC,
			 group_sorted ASC
		';

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 슬롯 데이터 추가.
	 *
	 * @param array $data 기록할 데이터.
	 */
	public static function setSlot($data)
	{
		$qb = self::buildInsertQuery('sw_refer_slots', $data);
		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}



	/**
	 * 슬롯 배팅 데이터 조회.
	 *
	 *
	 */
	public static function getBettings($slot_id)
	{
		$query = 'SELECT
			*
		FROM
			sw_refer_bettings
		WHERE
			slot_id = ?
		';
		$params = array ($slot_id);

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 슬롯 배팅 데이터 추가.
	 *
	 * @param array $data 배팅 데이터
	 */
	public static function setBetting($data)
	{
		$qb = self::buildInsertQuery('sw_refer_bettings', $data);
		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}



	/**
	 * 슬롯 배팅 데이터 삭제.
	 *
	 * @param string $slot_id 슬롯 ID
	 * @param integer $level 레벨
	 */
	public static function dropBetting($slot_id, $level)
	{
		$query = 'DELETE FROM
			sw_refer_bettings
		WHERE
			slot_id = ?
			AND level = ?
		';
		$params = array ($slot_id, $level);

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 버프 목록 조회.
	 *
	 * @param integer $idx 버프 Index (선택)
	 * @return object
	 */
	public static function getBuffs($idx = '')
	{
		$query = 'SELECT
			*
		FROM
			sw_refer_buffs
		WHERE
			1
		';
		$params = [];

		if (!empty($idx))
		{
			$query = 'AND idx = ? ';
			array_push($params, $idx);
		}

		$query .= 'ORDER BY
			status DESC,
			sorted ASC
		';

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 버프 데이터 업데이트.
	 *
	 *
	 */
	public static function setBuff($data)
	{
		$qb = self::buildInsertQuery('sw_refer_buffs', $data);
		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}



	/**
	 * 버프 데이터 삭제.
	 *
	 * @param integer $idx 버프 Index
	 */
	public static function dropBuff($idx)
	{
		$query = 'DELETE FROM
			sw_refer_buffs
		WHERE
			idx = ?
		';
		$params = [$idx];

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 보정잭팟 목록 조회
	 *
	 * @param integer $idx 버프 Index (선택)
	 * @return object
	 */
	public static function getAssigns($idx = '')
	{
		$query = 'SELECT
			*
		FROM
			sw_refer_assigns
		WHERE
			1
		';
		$params = [];

		if (!empty($idx))
		{
			$query = 'AND idx = ? ';
			array_push($params, $idx);
		}

		$query .= 'ORDER BY
			slot_id ASC, used DESC, rtp_min ASC, idx ASC
		';

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 보정잭팟 업데이트
	 *
	 *
	 */
	public static function setAssigns($data)
	{
		$qb = self::buildInsertQuery('sw_refer_assigns', $data);
		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}



	/**
	 * 보정잭팟 삭제
	 *
	 * @param integer $idx
	 */
	public static function dropAssigns($idx)
	{
		$query = 'DELETE FROM
			sw_refer_assigns
		WHERE
			idx = ?
		';
		$params = [$idx];

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 임의지급에 사용할 날짜 코드 정의.
	 *
	 *
	 */
	public static function getAssignDateset()
	{
		$query = "SELECT
			0 AS `errno`,
			DATE_FORMAT(NOW(), '%Y%m%d%H') AS `hourcode`,
			DATE_FORMAT(NOW(), '%Y%m%d') AS `daycode`,
			DATE_FORMAT(NOW(), '%Y%u') AS `weekcode`,
			DATE_FORMAT(NOW(), '%Y%m') AS `monthcode`
		";

		return collect(DB::connection('slot_system')->select($query));
}



	/**
	 * 기간잭팟 목록 조회
	 *
	 * @param integer $idx 버프 Index (선택)
	 * @return object
	 */
	public static function getAssignJackpots($idx = '')
	{
		$query = "SELECT
			a.*,
			(SELECT COUNT(idx) FROM sw_assigns.sw_jackpot_users WHERE jackpot_idx = a.idx) AS `user`,
			(SELECT GROUP_CONCAT(uid SEPARATOR '|') AS `uid` FROM sw_assigns.sw_jackpot_users WHERE jackpot_idx = a.idx GROUP BY uid) AS `uid`
		FROM
			sw_assigns.sw_jackpot_schedulers AS a
		WHERE
			1
		";
		$params = [];

		if (!empty($idx))
		{
			$query .= ' AND idx = ? ';
			array_push($params, $idx);
		}

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 기간잭팟 업데이트
	 *
	 * @param array $data 등록 할 데이터.
	 * @return mixed
	 */
	public static function setAssignJackpot($data)
	{
		$usecols	= self::createDateFormatQuery($data['datetype'], $data['dt'], null);
		$nextcols	= self::createDateFormatQuery($data['datetype'], $data['dt'], $data['term']);

		$query = 'INSERT INTO
			sw_assigns.sw_jackpot_schedulers
			(idx, datetype, term, slot_id, required_play, required_bet, required_rtp, reward_min, reward_max, reward_user, usecode, nextcode, summary, status)
		VALUES
			(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '.$usecols.', '.$nextcols.', ?, ?)
		ON DUPLICATE KEY UPDATE
			datetype = ?
			, term = ?
			, slot_id = ?
			, required_play = ?
			, required_bet = ?
			, required_rtp = ?
			, reward_min = ?
			, reward_max = ?
			, reward_user = ?
			, usecode = '.$usecols.'
			, nextcode = '.$nextcols.'
			, summary = ?
			, status = ?
		';
		$params = [];

		array_push($params, $data['idx']);

		array_push($params, $data['datetype'], $data['term'], $data['slot_id'], $data['required_play'], $data['required_bet'], $data['required_rtp'], $data['reward_min'], $data['reward_max'], $data['reward_user'], $data['summary'], $data['status']);
		array_push($params, $data['datetype'], $data['term'], $data['slot_id'], $data['required_play'], $data['required_bet'], $data['required_rtp'], $data['reward_min'], $data['reward_max'], $data['reward_user'], $data['summary'], $data['status']);

		return collect(DB::connection('slot_system')->insert($query, $params));
	}



	/**
	 * 기간잭팟 삭제
	 *
	 * @param integer $idx
	 * @return mixed
	 */
	public static function dropAssignJackpot($idx)
	{
		$query = 'DELETE FROM
			sw_assigns.sw_jackpot_schedulers
		WHERE
			idx = ?
		';
		$params = [$idx];

		return collect(DB::connection('slot_system')->select($query, $params));
	}



	/**
	 * 날짜를 쿼리용 함수로 변환.
	 *
	 * @param string type 날짜코드
	 * @param datetime $dt 기준날짜
	 * @param integer ter
	 */
	private static function createDateFormatQuery($datetype, $dt, $term=null)
	{
		switch ($datetype)
		{
			case 'M':
				if ($term > 0)
					return " DATE_FORMAT(DATE_ADD('".$dt."', INTERVAL ".$term." MONTH), '%Y%m') ";
				else
					return " DATE_FORMAT('".$dt."', '%Y%m') ";
				break;

			case 'W':
				if ($term > 0)
					return " YEARWEEK(DATE_ADD('".$dt."', INTERVAL ".$term." WEEK)) ";
				else
					return " YEARWEEK('".$dt."') ";
				break;

			case 'D':
				if ($term > 0)
					return " DATE_FORMAT(DATE_ADD('".$dt."', INTERVAL ".$term." DAY), '%Y%m%d') ";
				else
					return " DATE_FORMAT('".$dt."', '%Y%m%d') ";
				break;

			case 'H':
				if ($term > 0)
					return " DATE_FORMAT(DATE_ADD('".$dt."', INTERVAL ".$term." HOUR), '%Y%m%d%H') ";
				else
					return " DATE_FORMAT('".$dt."', '%Y%m%d%H') ";
				break;
		}

		return '';
	}



}
