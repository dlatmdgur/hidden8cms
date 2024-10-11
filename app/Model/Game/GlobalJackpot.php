<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;



class GlobalJackpot extends BaseModel
{
	protected $connection	= 'slot_common';
	protected $table 		= 'sw_royaljackpot_users';
	protected $primaryKey	= 'idx';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idx', 'uid', 'reward_rate', 'ticket', 'status', 'expired', 'updated', 'created'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
//		'created'	=>	'datetime',
	];


	/**
	 * 슬롯을 불러옴
	 *
	 * @param array $cols 가져올 컬럼(배열)
	 * @param string $sort 정렬 방향(sorted 기준)
	 * @return void
	 */
	public static function getReferSlots(array $cols = ['*'], string $sort = 'ASC')
	{
		$selCol = implode(', ', $cols);

		$query = 'SELECT '.$selCol.' FROM `sw_refer_slots` ORDER BY sorted '.$sort;

		return DB::connection('slot_system')->select($query);

	}


	public static function getReferJackpot()
	{
		$query = 'SELECT
			a.idx, a.tier, a.status, b.balance
		FROM
			`sw_refer_jackpot` AS a
			LEFT JOIN `'.env('DB_DATABASE_SLOT_COMMON').'`.sw_royaljackpot AS b ON b.tier = a.tier
		ORDER BY
			a.idx
		';

		return collect(DB::connection('slot_system')->select($query));
	}



	public static function setReferJackpotStatus($idx, $status)
	{
		$query = '	UPDATE
					sw_refer_jackpot
					SET
						status = ?
					WHERE
						idx = ?
					';
		$params = [$status, $idx];

		return collect(DB::connection('slot_system')->update($query, $params));
	}



	public static function setRoyalJackpotUser($uid, $tier)
	{
		$query = 'INSERT INTO sw_royaljackpot_users (uid, tier) VALUES (?, ?)';

		$params = [$uid, $tier];

		return collect(DB::connection('slot_common')->insert($query, $params));
	}



	public static function getRoyalJackpotUsers($uid, $page = 1, $offset = 20, string $type = null, int $userSeq = null , string $dateType = 'D')
	{

		$where[] = ['uc.datetype', '=', $dateType];

		if ($uid !== null) $where[] = ['ju.uid', '=', $uid];

		if ($type !== null ) $where[] = ['ju.tier', '=', $type];

		if ($userSeq !== null ) $where[] = ['ju.uid', '=', $userSeq];


		$usrByRtps = DB::connection('slot_common')->table('sw_user_cumulatives')
						->select('uid', DB::raw('SUM(payout) / SUM(bet) + SUM(fee) * 100 AS `rtp`'))
						->where('datetype', $dateType)
						->groupBy('uid');

		array_shift($where);

		return DB::connection('slot_common')
						->table('sw_royaljackpot_users AS ju')
						->join('accountdb.account_info AS ai', 'ju.uid', '=', 'ai.user_seq')
						->leftJoinSub($usrByRtps, 'uc', function($join){
							$join->on('ju.uid', '=', 'uc.uid');
						})
						->select('ju.idx', 'ju.tier' ,'ju.uid AS user_seq', 'ai.account as id', 'ju.prize', 'ju.status', 'ju.created AS confirm_date', 'ju.updated AS win_date','uc.rtp')
						->where($where)
						->orderBy('idx', 'DESC')
						->paginate($offset, ['*'], 'page', $page);
	}



	public static function getRoyalJackpotUserTotal($uid)
	{
		$page	= empty($page) ? 1 : $page;
		$offset	= empty($offset) ? 20 : $offset;

		$query	= 'SELECT
			COUNT(*) AS `count`
		FROM
			sw_royaljackpot_users
		WHERE
			uid = ?
		';
		$params = [$uid];

		return collect(DB::connection('slot_common')->select($query, $params))[0];
	}


	public static function getJackpotLog($uid, $page, $limit)
	{
		$page	= empty($page) ? 1 : $page;
		$offset	= empty($offset) ? 20 : $offset;

		$query	= 'SELECT
			*
		FROM
			sw_jackpot_log
		WHERE
			uid = ?
		ORDER BY
			idx DESC
		LIMIT
			?, ?
		';
		$params = [$uid, (--$page) * $offset, $offset];

		return collect(DB::connection('slot_log')->select($query, $params));
	}

	/**
	 * 슬롯의 상태를 변경
	 *
	 * @param string $target all이면 전부, 아니면 slot_id
	 * @param boolean $changeStatus 변경할 상태값
	 * @return void
	 */
	public static function setReferSlotStatus(string $target, int $changeStatus)
	{
		$bindValue = [];

		$query = 	'UPDATE `sw_refer_slots`
						SET rule_normal = JSON_SET(rule_normal, \'$.useRoyalJackpot\', '.$changeStatus.')';
		if ($target !== 'all')
		{
			$bindValue[] = $target;
			$query .= ' WHERE slot_id = ?';
		}

		return DB::connection('slot_system')->update($query, $bindValue);

	}
}


