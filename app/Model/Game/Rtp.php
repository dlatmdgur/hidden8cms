<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;



class Rtp extends BaseModel
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
	 * 범용 RTP 색인.
	 *
	 * @param array $where 검색 조건
	 * @param integer $page 가져올 페이지 ( default 1 )
	 * @param integer $offset 페이지당 가져올 갯수 ( default 20 )
	 * @return object
	 */
	public static function getGenerals($where, $page, $offset)
	{
		$result = DB::connection('slot_common')
			->table('rtp_generals')
			->where($where)
			->orderBy('client_uid', 'ASC')
			->paginate($offset, ['*'], 'page', $page);

		$result->withQueryString()->links();

		return $result;
	}



	/**
	 * 범용 RTP 셋팅.
	 *
	 * @param integer $cuid 대상 UID
	 * @param integer $rtp 저장할 RTP
	 * @return boolean
	 */
	public static function setGeneral($cuid, $rtp)
	{
		$query = '	INSERT INTO rtp_generals
						(client_uid, rtp)
					VALUES
						(?, ?)
					ON DUPLICATE KEY UPDATE
						rtp = ?
					';
		$params = [$cuid, $rtp, $rtp];

		return collect(DB::connection('slot_common')->insert($query, $params));
	}



	/**
	 * 슬롯별 RTP 색인.
	 *
	 * @param array $where 검색 조건
	 * @param integer $page 가져올 페이지 ( default 1 )
	 * @param integer $offset 페이지당 가져올 갯수 ( default 20 )
	 * @return object
	 */
	public static function getSlots($where, $page, $offset, array $provider = [])
	{
		$result = DB::connection('slot_common')
			->table('external_slotlist AS es')
			->leftjoin('rtp_slots AS rs', 'rs.slot_id', '=', 'es.slot_id')
			->select('es.slot_id', 'es.name_kr', 'es.name_en', 'rs.updated', 'rs.rtp');
		if (count($provider) > 0)
		{
			$result = $result->whereIn('provider', $provider);
		}

		$result = $result->where($where)
						->orderBy('es.slot_id', 'ASC')
						->paginate($offset, ['*'], 'page', $page);

		$result->withQueryString()->links();

		return $result;
	}



	/**
	 * 슬롯별 RTP 셋팅.
	 *
	 * @param integer $cuid 대상 UID
	 * @param integer $rtp 저장할 RTP
	 * @return boolean
	 */
	public static function setSlot($cuid, $slot_id, $rtp)
	{
		$query = 'INSERT INTO rtp_slots
					(client_uid, slot_id, rtp)
				VALUES
					(?, ?, ?)
				ON DUPLICATE KEY UPDATE
					rtp = ?
				';
		$params = [$cuid, $slot_id, $rtp, $rtp];

		return collect(DB::connection('slot_common')->insert($query, $params));
	}



	/**
	 * 슬롯별 RTP 데이터 리셋.
	 *
	 * @return boolean
	 */
	public static function resetSlot()
	{
		return collect(DB::connection('slot_common')->insert('DELETE FROM rtp_slots'));
	}



	/**
	 * 유저별 RTP 색인.
	 *
	 * @param array $where 검색 조건
	 * @param integer $page 가져올 페이지 ( default 1 )
	 * @param integer $offset 페이지당 가져올 갯수 ( default 20 )
	 * @return object
	 */
	public static function getUsers($where, $page, $offset)
	{
		$result = DB::connection('slot_common')
			->table('rtp_users')
			->whereIn(...$where)
			->orderBy('uid', 'ASC')
			->paginate($offset, ['*'], 'page', $page);

		$result->withQueryString()->links();

		return $result;
	}



	/**
	 * 유저별 RTP 셋팅.
	 *
	 * @param integer $cuid 대상 UID
	 * @param integer $rtp 저장할 RTP
	 * @return boolean
	 */
	public static function setUser($cuid, $uid, $rtp)
	{
		$query = '	INSERT INTO rtp_users
						(client_uid, uid, rtp)
					VALUES
						(?, ?, ?)
					ON DUPLICATE KEY UPDATE
						rtp = ?
					';
		$params = [$cuid, $uid, $rtp, $rtp];

		return collect(DB::connection('slot_common')->insert($query, $params));
	}



	/**
	 * 유저별 RTP 데이터 리셋.
	 *
	 * @return boolean
	 */
	public static function resetUser()
	{
		return collect(DB::connection('slot_common')->insert('DELETE FROM rtp_users'));
	}


}







