<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventJackpot extends BaseModel
{
	protected $connection	= 'assigns';
	protected $table		= 'global_events_users';
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
	 * 유저 SEQ 검색용 함수
	 *
	 * @param string $target 검색 대상
	 * @param string $keyword 검색어
	 * @return mixed
	 */
	public static function getUserSeq($target='id', $keyword)
	{
		$query = 'SELECT
			user_seq
		FROM
			`accountdb`.account_info
		WHERE
			1
		';

		$params = [];


		switch ($target)
		{
			case 'id':
				$query .= " AND account LIKE '%".$keyword."%' ";
				break;

			case 'nickname':
				$query .= " AND nickname LIKE '%".$keyword."%' ";
				break;
		}

		return collect(DB::connection('mysql')->select($query, $params));
	}



	public static function getList($seqs=[])
	{
		$query = '	SELECT
						a.*,
						SUM(b.play) AS `play`,
						SUM(b.win) AS `win`,
						SUM(b.bet) AS `bet`,
						SUM(b.fee) AS `fee`,
						SUM(b.payout) AS `payout`,
						SUM(b.payout) / (SUM(b.bet) + SUM(b.fee)) * 100 AS `rtp`
					FROM
						`accountdb`.`account_info` AS a
					LEFT OUTER JOIN '.env('DB_DATABASE_SLOT_COMMON').'.sw_user_cumulatives AS b ON b.uid = a.user_seq AND b.datetype=\'D\' AND b.datecode=DATE_FORMAT(NOW(),\'%Y%m%d\')
					WHERE 1 ';

		if (count($seqs))
			$query .= 'AND a.user_seq IN ('.implode(',', $seqs).') ';

		$query .= ' GROUP BY a.user_seq ';
		$query .= ' ORDER BY rtp ASC ';

		return collect(DB::connection('mysql')->select($query));
	}



	/**
	 * 이벤트 잭팟 지급 처리.
	 *
	 * @param integer $uid 유저 UID
	 * @param integer $multiple 지급 배수
	 * @param string $ticket 티캣 번호
	 */
	public static function setEventJackpot($uid, $multiple, $ticket, $start_time, $expired)
	{
		$params = [
			'uid'			=>	$uid,
			'reward_rate'	=>	$multiple,
			'ticket'		=>	$ticket,
			'start_time'	=> 	$start_time,
			'expired'		=>	$expired,
		];

		return DB::connection('assigns')->table('global_events_users')->insertGetId($params);
	}

	public static function get($useq, $page, $offset = 20)
	{
		$rs	= DB::connection('assigns')->table('global_events_users')
								->select('idx', 'created', 'start_time', 'reward_rate', 'status', 'updated')
								->where('uid', $useq)
								->orderby('created', 'DESC')
								->paginate($offset, ['*'], 'page', $page);
		return $rs;

	}

	public static function drop($idx)
	{
		$sql = 'UPDATE global_events_users SET status = 0 WHERE idx = ?';

		return DB::connection('assigns')->update($sql, array($idx));
	}

}


