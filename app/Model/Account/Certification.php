<?php

namespace App\Model\Account;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Certification extends BaseModel
{
	protected $connection = 'mysql';
	protected $table = 'accountdb.certification';
	protected $primaryKey = 'user_seq';
	public $incrementing = false;

	/**
	 * return same di member list
	 * @param string $di
	 * @return Collection
	 */
	public static function getSameDiMember(string $di) : Collection
	{
		return DB::connection('mysql')
			->table('accountdb.certification as c')
			->join('accountdb.account_info as a', 'c.user_seq', '=', 'a.user_seq')
			->select('c.user_seq', 'a.account', 'a.login_type','c.date')
			->where('c.di', $di)
			->orderBy('c.user_seq', 'ASC')
			->get();
	}



	/**
	 * update platform member di
	 * @param int $userSeq
	 * @return void
	 */
	public static function updatePlatformMemberDi(int $userSeq) : void
	{
		$sql = "UPDATE auth_platform.member SET phone_auth = NULL, CI = '', DI = '' WHERE user_seq = :user_seq";
		DB::connection('mysql')->update($sql, ['user_seq' => $userSeq]);
	}



	/**
	 * 실명인증 처리.
	 *
	 * @param int $user_seq
	 * @return void
	 */
	public static function passPlatformCertification(int $user_seq) : void
	{
		$params = ['user_seq' => $user_seq];

		// $query = "UPDATE auth_platform.member SET phone_auth='Y', CI=md5(CONCAT(userid, UNIX_TIMESTAMP())), DI=md5(CONCAT(user_seq, UNIX_TIMESTAMP())) WHERE user_seq = :user_seq ";
		// DB::connection('mysql')->update($query, $params);

		$query = "INSERT INTO
			accountdb.certification
			(user_seq, di, `date`)
		VALUES
			(:user_seq, md5(CONCAT(:user_seq, UNIX_TIMESTAMP())), NOW())
		ON DUPLICATE KEY UPDATE
			di = md5(CONCAT(user_seq, UNIX_TIMESTAMP())),
			`date` = NOW()
		";
		DB::connection('mysql')->update($query, $params);
	}



	/**
	 * 실명인증 취소.
	 *
	 * @param int $user_seq
	 * @return void
	 */
	public static function cancelPlatformCertification(int $user_seq) : void
	{
		$params = ['user_seq' => $user_seq];

		// $query = "UPDATE auth_platform.member SET phone_auth=NULL, CI='', DI='' WHERE user_seq = :user_seq ";
		// DB::connection('mysql')->update($query, $params);

		$query = "INSERT INTO
			accountdb.certification
			(user_seq, di, `date`)
		VALUES
			(:user_seq, '', '1970-01-01 00:00:00')
		ON DUPLICATE KEY UPDATE
			di = '',
			`date` = '1970-01-01 00:00:00'
		";
		DB::connection('mysql')->update($query, $params);
	}

	/**
	 * 인증 관련 수정
	 *
	 * @param integer $useq
	 * @param [type] $data
	 * @return void
	 */
	public static function upsert(array $data)
	{
		$cols = [];
		$_INS = [];
		$_UPD = [];
		$params = [];

		foreach ($data as $key => $val)
		{
			$cols[] 		= $key;
			$_INS[] 		= '?';
			$_UPD[]	 		= $key.'=?';
			$params[] 		= $val;
		}

		$query = 'INSERT INTO `accountdb`.`certification` ('.implode(', ', $cols).')'.
		          'VALUES ('.implode(', ', $_INS).')'.
				  'ON DUPLICATE KEY UPDATE '.implode(',', $_UPD);

		$params = array_merge($params, $params);

		return DB::connection('mysql')->update($query, $params);
	}

}
