<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;


class SlotUser extends BaseModel
{

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
	 * 유저 정보 조회.
	 *
	 * @param string $type 검색 타입
	 * @param mixed $keyword 검색 대상
	 */
	public static function getUsers($type, $keyword, $order='')
	{
		$query = 'SELECT
			a.user_seq AS id, a.account AS `userid`, a.nickname, b.nickname, a.login_type AS `join_site`, a.update_date AS `site_logined`,
			a.user_seq, a.nickname AS `game_nickname`, a.update_date,
			b.level, b.exp, b.gold, b.chip, b.gem, c.ip AS login_ip ,c.log_date AS `game_logined`
		FROM
			accountdb.account_info AS a
			LEFT OUTER JOIN gamedb.user_info AS b ON a.user_seq = b.user_seq
			LEFT OUTER JOIN `logdb`.`login_log` AS c ON c.log_seq = (SELECT MAX(log_seq) FROM `logdb`.`login_log` WHERE user_seq = a.user_seq)
		WHERE
		';

		switch (strtoupper($type))
		{
			case 'ID':
				$query .= "a.account LIKE '".$keyword."%' ";
				break;

			case 'NICKNAME':
				$query .= "a.nickname LIKE '".$keyword."%' OR b.nickname LIKE '".$keyword."%' ";
				break;

			case 'USERSEQ':
				$query .= "a.user_seq = '".$keyword."' ";
				break;
		}

		return collect(DB::connection('mysql')->select($query));
	}



	public static function getUserRtps($uid)
	{
		$query = 'SELECT
            slot_id,
            bet,
            range_bet,
            range_payout,
            range_payout / range_bet * 100 AS `rtp`
		FROM
			sw_user_rtps
		WHERE
		    uid = ?
        ORDER BY
            slot_id, bet
		';
		$params = [$uid];

		return collect(DB::connection('slot_user')->select($query, $params));
	}



	/**
	 * RTP RESET 처리.
	 *
	 * @param integer $uid 유저 UID
	 * @param string $slot_id 슬롯 ID
	 * @return mixed
	 */
	public static function resetUserRtp($uid, $slot_id)
	{
		$query = 'UPDATE
			sw_user_rtps
		SET
			range_bet = bet * 500,
            range_payout = bet * 500,
            bet_interval = 0,
            play_interval = 0
		WHERE
			uid = ?
			AND slot_id = ?
		';
		$params = [$uid, $slot_id];

		return collect(DB::connection('slot_user')->delete($query, $params));
	}



	public static function getUserBuffs($uid)
	{
		$query = 'SELECT
			*
		FROM
			sw_user_buffs
		WHERE
			uid = ?
		ORDER BY
			expired DESC
		';
		$params = [$uid];

		return collect(DB::connection('slot_user')->select($query, $params));
	}

    public static function getUserBuffsOne($uid, $slot_id, $target, $code)
    {
        $query = 'SELECT
			*
		FROM
			sw_user_buffs
		WHERE
			uid = ?
		AND slot_id = ? AND target = ? AND code = ?
		';
        $params = [$uid, $slot_id, $target, $code];

        return collect(DB::connection('slot_user')->select($query, $params));
    }



	public static function setUserBuff($uid, $slot_id, $target, $code, $amount, $expired)
	{
		$query = 'INSERT INTO
			sw_user_buffs
			(uid, slot_id, target, code, amount, expired)
		VALUES
			(?, ?, ?, ?, ?, ?)
		ON DUPLICATE KEY UPDATE
			amount = ?,
			expired = ?
		';
		$params = [$uid, $slot_id, $target, $code, $amount, $expired, $amount, $expired];

		return collect(DB::connection('slot_user')->delete($query, $params));
	}



	public static function dropUserBuff($uid, $slot_id, $target, $code)
	{
		$query = 'DELETE FROM
			sw_user_buffs
		WHERE
			uid = ?
			AND slot_id = ?
			AND target = ?
			AND code = ?
		';
		$params = [$uid, $slot_id, $target, $code];

		return collect(DB::connection('slot_user')->delete($query, $params));
	}

}

