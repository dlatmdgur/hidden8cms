<?php

namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;


class TesterInfo extends BaseModel
{
	protected $connection = 'mysql';
	protected $table = '`sw_common`.games_tester_users';
	protected $primaryKey = ['corp', 'user_seq'];

	protected $guarded = [];





	public static function get($where, $page=1, $offset=20)
	{
		$result = DB::connection('mysql')
			->table('sw_common.games_tester_users AS a')
			->leftJoin('gamedb.user_info AS b', 'b.user_seq', 'a.user_seq')
			->leftJoin('accountdb.account_info AS c', 'c.user_seq', 'a.user_seq')
			->where($where)
			->groupBy('a.user_seq')
			->select(DB::raw("a.user_seq, GROUP_CONCAT(a.corp SEPARATOR '|') AS `corps`, GROUP_CONCAT(a.game_id SEPARATOR '::') AS `games`, MAX(a.created) AS `created`, b.nickname, c.account"))
			->orderBy('a.user_seq', 'ASC')
			->paginate($offset, ['*'], 'page', $page);

		return $result;
	}



	public static function set($user_seq, $corps=[])
	{
		// $query ='CALL setTestUser(?, ?) ';
		// $params = [$user_seq, implode('|', $corps)];

		$params = [];

		$query = 'DELETE FROM `sw_common`.`games_tester_users` WHERE user_seq = ?';

		$params[] = $user_seq;

		//삭제 처리
		DB::connection('platform')->delete($query, $params);

		array_unshift($params, implode('|', $corps));

		$query2 = 'INSERT INTO `sw_common`.games_tester_users (corp, user_seq) VALUES (?, ?)';

		return DB::connection('platform')->insert($query2, $params);
	}



	public static function drop($user_seq)
	{
		$query = 'DELETE FROM `sw_common`.games_tester_users WHERE user_seq = ? ';
		$params = [$user_seq];

		return collect(DB::connection('platform')->delete($query, $params));
	}



	public static function getTestUser($account = '', $nickname = '')
	{
		$query = 'SELECT
			a.user_seq, a.account, a.nickname
		FROM
			accountdb.account_info AS a
			INNER JOIN `sw_common`.games_tester_users AS b ON b.user_seq = a.user_seq
		WHERE
			1
		';
		$params = [
			'account'			=>	$account,
			'nickname'			=>	$nickname,
		];

		$squery = [];
		if (!empty($account))
			$squery[] = 'a.account = :account ';

		if (!empty($nickname))
			$squery[] = 'a.nickname = :nickname ';

		if (!empty($squery))
			$query .= ' AND ('.implode(' OR ', $squery).') ';

		return collect(DB::connection('platform')->select($query, $params));
	}





}
