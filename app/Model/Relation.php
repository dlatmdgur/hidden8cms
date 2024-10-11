<?php
namespace App\Model;

use App\BaseModel;
use Illuminate\Support\Facades\DB;


class Relation extends BaseModel
{
	protected $connection = 'platform';





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



	public static function getChildMember($userid)
	{
		$query = "SELECT
			m.user_seq, m.userid, ui.nickname, mr.user_seq AS `relation_seq`
		FROM
			auth_platform.member AS m
			INNER JOIN gamedb.user_info AS ui ON m.user_seq = ui.user_seq
			LEFT OUTER JOIN member_relations AS mr ON m.user_seq = mr.user_seq
		WHERE
			m.userid = '".$userid."'
		GROUP BY
			m.user_seq
		";
		$params = [];

		return collect(DB::connection('platform')->select($query, $params));
	}



	public static function getChildMembers($parent_seq, $depth=-1, $keyword='')
	{
		$query = 'SELECT
			r.idx, r.user_seq, r.max_depth AS `depth`, m.userid, ui.nickname
		FROM
			member_relations AS r
			INNER JOIN auth_platform.member AS m ON r.user_seq = m.user_seq
			INNER JOIN gamedb.user_info AS ui ON r.user_seq = ui.user_seq
		WHERE
			r.parent_seq = ?
		';
		$params = [$parent_seq];

		if ($depth >= 0)
		{
			$query .= 'AND r.max_depth = ? ';
			array_push($params, $depth);
		}

		if (!empty($keyword))
			$query .= " AND m.userid LIKE '".$keyword."%' ";

		$query .= '
		ORDER BY
			r.user_seq
		';

		error_log("getChildMembers ::\n".$query."\n".json_encode($params));

		return collect(DB::connection('platform')->select($query, $params));
	}



	public static function getParentMember($depth)
	{
		$query = "SELECT
			m.user_seq, m.userid, ui.nickname, mr.parent_seq
		FROM
			member_relations AS mr
			INNER JOIN auth_platform.member AS m ON mr.user_seq = m.user_seq
			INNER JOIN gamedb.user_info AS ui ON m.user_seq = ui.user_seq
		WHERE
			mr.max_depth = ?
		GROUP BY
			mr.user_seq
		";
		$params = [$depth];

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 관계 유저 검색.
	 *
	 * @param integer $d 관계 깊이.
	 * @param string $t 검색 종류 ( u : userid, n : nickname )
	 * @param string $q 검색 내용
	 * @return mixed
	 */
	public static function getRelationUsers($d, $t='userid', $q='', $page=1, $limit=20, $master=null)
	{
		$retval = [];


		$page	= empty($page) ? 0 : intval($page);
		$limit	= empty($limit) ? 20 : intval($limit);


		$query = "SELECT
			COUNT(DISTINCT m.user_seq) AS `cnt`
		FROM
			auth_platform.member AS m
			INNER JOIN member_relations AS mr1 ON mr1.user_seq = m.user_seq AND mr1.max_depth = ? AND mr1.depth = mr1.max_depth
			INNER JOIN member_relations AS mr2 ON mr2.idx = mr1.idx
			LEFT OUTER JOIN member_relation_points AS mp1 ON mp1.user_seq = m.user_seq AND mp1.datecode = 0
			LEFT OUTER JOIN member_relation_points AS mp2 ON mp2.idx = mp1.idx
		WHERE
			1
		";
		$params = [$d];

		if (!empty($master))
			$query .= 'AND m.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = '.$master.' UNION ALL SELECT '.$master.') ';

		if (!empty($t) &&
			!empty($q))
			$query .= "AND m.".$t." LIKE '".$q."%' ";

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = empty($result->cnt) ? 0 : intval($result->cnt);

		error_log("getRelationUsers ::\n".$query."\n".json_encode($params));


		$query = "SELECT
			m.id, m.userid, m.nickname, mr1.idx, mr2.parent_seq, mr2.depth, mr2.max_depth, mr2.user_seq, mr2.created, SUM(mp2.total_point) AS `point`
		FROM
			auth_platform.member AS m
			INNER JOIN member_relations AS mr1 ON m.user_seq = mr1.user_seq AND mr1.max_depth = ? AND mr1.depth = mr1.max_depth
			INNER JOIN member_relations AS mr2 ON mr2.idx = mr1.idx
			LEFT OUTER JOIN member_relation_points AS mp1 ON mp1.user_seq = m.user_seq AND mp1.datecode = 0
			LEFT OUTER JOIN member_relation_points AS mp2 ON mp2.idx = mp1.idx
		WHERE
			1
		";
		$params = [$d];

		if (!empty($master))
			$query .= 'AND m.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = '.$master.' UNION ALL SELECT '.$master.') ';

		if (!empty($t) &&
			!empty($q))
			$query .= "AND m.".$t." LIKE '".$q."%' ";

		$query .= '
		GROUP BY
			m.user_seq
		ORDER BY
			m.userid ASC
		LIMIT
			?, ?
		';
		array_push($params, ($page - 1) * $limit, $limit);

		error_log("getRelationUsers ::\n".$query."\n".json_encode($params));

		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

		return $retval;
	}



	/**
	 * 인덱스로 관계 검색.
	 *
	 * @param integer $idx 관계 INDEX
	 * @return mixed
	 */
	public static function getRelationForIndex($idx)
	{
		$query = 'SELECT
			m.userid, ui.nickname, mr.*, SUM(mp.total_point) AS `point`
		FROM
			member_relations AS mr
			LEFT OUTER JOIN member AS m ON mr.user_seq = m.user_seq
			LEFT OUTER JOIN gamedb.user_info AS ui ON mr.user_seq = ui.user_seq
			LEFT OUTER JOIN member_relation_points AS mp ON mr.user_seq = mp.user_seq AND mp.datecode = 0
		WHERE
			mr.idx = ?
		';
		$params = [$idx];

		error_log("getRelationForIndex ::\n".$query."\n".json_encode($params));

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 인덱스로 관계 검색.
	 *
	 * @param integer $idx 관계 INDEX
	 * @return mixed
	 */
	public static function getRelationForUserSeq($seq)
	{
		$query = 'SELECT
			m.userid, ui.nickname, mr.*, SUM(mp.total_point) AS `point`
		FROM
			member_relations AS mr
			INNER JOIN member AS m ON mr.user_seq = m.user_seq
			INNER JOIN gamedb.user_info AS ui ON mr.user_seq = ui.user_seq
			LEFT OUTER JOIN member_relation_points AS mp ON mr.user_seq = mp.user_seq AND mp.datecode = 0
		WHERE
			mr.user_seq = ?
			AND mr.depth = mr.max_depth
		';
		$params = [$seq];

		error_log("getRelationForUserSeq ::\n".$query."\n".json_encode($params));

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * SEQ 로 마스터 색인.
	 *
	 *
	 */
	public static function getRelationForMaster($seq)
	{
		$query = 'SELECT
			parent_seq, MIN(depth) AS depth, user_seq
		FROM
			member_relations
		WHERE
			user_seq = ?
		';
		$params = [$seq];

		return collect(DB::connection('platform')->select($query, $params))[0];
	}



	public static function getRelationParentForIndex($idx)
	{
		$query = "SELECT
			GROUP_CONCAT(CONCAT(ui.nickname, ' ( ', m.userid, ' )') SEPARATOR ' / ') AS `parents`,
			GROUP_CONCAT(mr.parent_seq SEPARATOR ' / ') AS `parent_seq`
		FROM
			member_relations AS mr
			INNER JOIN auth_platform.member AS m ON m.user_seq = mr.parent_seq
			INNER JOIN gamedb.user_info AS ui ON ui.user_seq = mr.parent_seq
		WHERE
			mr.user_seq IN (SELECT user_seq FROM member_relations WHERE idx = ?)
		GROUP BY
			mr.user_seq
		ORDER BY
			mr.depth ASC
		";
		$params = [$idx];

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 인덱스로
	 *
	 * @param integer $idx 관계 INDEX
	 */
	public static function getRelationChildForIndex($idx)
	{
		$query = 'SELECT
			m.userid, ui.nickname, mrb.*, SUM(mp.total_point) AS `point`
		FROM
			member_relations AS mr
			INNER JOIN member_relations AS mrb ON mr.user_seq = mrb.parent_seq AND mrb.max_depth = mr.depth + 1
			INNER JOIN member AS m ON mrb.user_seq = m.user_seq
			INNER JOIN gamedb.user_info AS ui ON mrb.user_seq = ui.user_seq
			LEFT OUTER JOIN member_relation_points AS mp ON mrb.user_seq = mp.user_seq AND mp.datecode = 0
		WHERE
			mr.idx = ?
		GROUP BY
			mrb.user_seq
		';

		$params = [$idx];

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 인덱스로
	 *
	 * @param integer $idx 관계 INDEX
	 */
	public static function getRelationChildAllForIndex($idx)
	{
		$query = "SELECT
			mr.parent_seq, pm.userid AS `puserid`, pui.nickname AS `pnickname`
			, mr.depth
			, mr.user_seq, cm.userid, cui.nickname
		FROM
			member_relations AS mr
			LEFT OUTER JOIN member AS pm ON pm.user_seq = mr.parent_seq
			LEFT OUTER JOIN gamedb.user_info AS pui ON pui.user_seq = mr.parent_seq
			LEFT OUTER JOIN member AS cm ON cm.user_seq = mr.user_seq
			LEFT OUTER JOIN gamedb.user_info AS cui ON cui.user_seq = mr.user_seq
		WHERE
			mr.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = (SELECT user_seq FROM member_relations WHERE idx = ?))
			AND mr.depth = mr.max_depth
		";
		$params = [$idx];

		error_log("getRelationChildAllForIndex ::\n".$query."\n".json_encode($params));

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 뎁스별 맴버 포인트 조회.
	 *
	 * @param integer $idx 관계 INDEX
	 */
	public static function getRelationChildDepthPoint($idx)
	{
		$query = 'SELECT
			a.parent_seq, a.max_depth + 1 AS `depth`, COUNT(DISTINCT a.user_seq) AS `member`, SUM(b.total_point) AS `point`
		FROM
			member_relations AS a
			LEFT OUTER JOIN member_relation_points AS b ON a.user_seq = b.user_seq AND b.datecode = 0
		WHERE
			a.parent_seq = (SELECT user_seq FROM member_relations WHERE idx = ?)
		GROUP BY
			a.max_depth
		';
		$params = [$idx];

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 유저별 적립요율 색인.
	 *
	 * @param integer $seq 유저 INDEX
	 * @return mixed
	 */
	public static function getRelationRates($seq)
	{
		$query = 'SELECT
			*
		FROM
			member_relation_rates
		WHERE
			rel_seq = ?
		';
		$params = [$seq];

		return collect(DB::connection('platform')->select($query, $params));
	}



	/**
	 * 하위 유저별 적립요율 색인.
	 *
	 * @param integer $seq 유저 INDEX
	 * @return mixed
	 */
	public static function getRelationRateChilds($seq)
	{
		$query = 'SELECT
			*
		FROM
			member_relation_rates
		WHERE
			rel_seq = ?
		';
		$params = [$seq];

		return collect(DB::connection('platform')->select($query, $params));
	}


	public static function setMemberRelation($parent_seq, $user_seq)
	{
		if ($parent_seq == 0)
		{
			$query = 'INSERT INTO member_relations (parent_seq, depth, user_seq) VALUES (?, 0, ?) ';
			$params = [$parent_seq, $user_seq];

			return collect(DB::connection('platform')->insert($query, $params));
		}
		else
		{
			$query ='CALL setMemberRelation(?, ?, ?) ';
			$params = [$parent_seq, $user_seq, 0];

			return DB::connection('platform')->statement($query, $params);
		}
	}


	public static function dropMemberRelation($user_seq)
	{
		$query = 'DELETE FROM member_relations WHERE user_seq = ? ';
		$params = [$user_seq];

		return collect(DB::connection('platform')->insert($query, $params));
	}


	public static function setRelationRates($user_seq, $code, $depth, $rate, $depthname)
	{
		$query = 'INSERT INTO
			member_relation_rates
			(rel_seq, code, depth, rate1, depth_name)
		VALUES
			(?, ?, ?, ?, ?)
		ON DUPLICATE KEY UPDATE
			rate1 = ?,
			depth_name = ?
		';
		$params = [$user_seq, $code, $depth, $rate, $depthname, $rate, $depthname];

		return collect(DB::connection('platform')->insert($query, $params));
	}


	public static function getRelationConfig($master_seq, $code='', $keycode='')
	{
		$query = 'SELECT
			code, keycode, keyval
		FROM
			member_relation_configs
		WHERE
			master_seq = ?
		';
		$params = [$master_seq];

		if (!empty($code))
		{
			$query .= ' AND code = ? ';
			array_push($params, $code);
		}

		if (!empty($keycode))
		{
			$query .= ' AND keycode = ? ';
			array_push($params, $keycode);
		}

		$result = collect(DB::connection('platform')->select($query, $params));


		$data = [];

		if ($result == false)
			return $data;


		foreach ($result as $key => $row)
		{
			if (empty($code))
				$data[$row->code][$row->keycode] = $row->keyval;
			else
				$data[$row->keycode] = $row->keyval;
		}

		return $data;
	}



	public static function getRelationPoints($depth='', $method='', $keyword='', $start='', $end='', $page=1, $limit=20, $master=null)
	{
		$retval = [];

		$start	= empty($start) ? date('Ymd') : str_replace('-', '', $start);
		$end	= empty($end) ? date('Ymd') :  str_replace('-', '', $end);


		$query = 'SELECT
			COUNT(*) AS `cnt`
		FROM
			(
				SELECT
					mr.user_seq, mp.datecode
				FROM
					member_relations As mr
					INNER JOIN member_relation_points AS mp ON mr.user_seq = mp.user_seq AND mp.datecode BETWEEN ? AND ?
					INNER JOIN gamedb.user_info AS ui ON mr.user_seq = ui.user_seq
					INNER JOIN accountdb.account_info AS ai ON mr.user_seq = ai.user_seq
				WHERE
					mr.depth = ?
					AND mr.max_depth = mr.depth
		';
		$params = [$start, $end, $depth];

		if (!empty($master))
			$query .= 'AND mr.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = '.$master.' UNION ALL SELECT '.$master.') ';

		if (!empty($keyword))
		{
			switch ($method)
			{
				case 'u':
					$query .= " AND ai.platform_id LIKE '".$keyword."%' ";
					break;

				case 'n':
					$query .= " AND ui.nickname LIKE '".$keyword."%' ";
					break;
			}
		}

		$query .= '
				GROUP BY
					mr.user_seq, mp.datecode
			) AS a
		';

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = empty($result->cnt) ? 0 : intval($result->cnt);

		error_log("getRelationPoints ::\n".$query."\n".json_encode($params));



		$query = 'SELECT
			mr.idx, mr.parent_seq, mr.depth, mr.user_seq, mp.datecode, SUM(mp.total_spend) AS `total_spend`, SUM(mp.total_purchase) AS `total_purchase`, SUM(mp.total_point) AS `total_point`, ui.nickname
		FROM
			member_relations As mr
			INNER JOIN member_relation_points AS mp ON mr.user_seq = mp.user_seq AND mp.datecode BETWEEN ? AND ?
			INNER JOIN gamedb.user_info AS ui ON mr.user_seq = ui.user_seq
			INNER JOIN accountdb.account_info AS ai ON mr.user_seq = ai.user_seq
		WHERE
			mr.depth = ?
			AND mr.max_depth = mr.depth
		';
		$params = [$start, $end, $depth];

		if (!empty($master))
			$query .= 'AND mr.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = '.$master.' UNION ALL SELECT '.$master.') ';

		if (!empty($keyword))
		{
			switch ($method)
			{
				case 'u':
					$query .= " AND ai.platform_id LIKE '".$keyword."%' ";
					break;

				case 'n':
					$query .= " AND ui.nickname LIKE '".$keyword."%' ";
					break;
			}
		}

		$query .= '
		GROUP BY
			mr.user_seq, mp.datecode
		ORDER BY
			mp.datecode DESC
		LIMIT
			?, ?
		';
		array_push($params, intval(($page - 1) * $limit), intval($limit));

		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

		error_log("getRelationPoints ::\n".$query."\n".json_encode($params));

		return $retval;
	}



	public static function getRelationPointLogs($user_seq, $datecode, $page=1, $limit=20)
	{
		$retval = [];


		$query = 'SELECT
			COUNT(*) AS `cnt`
		FROM
			member_relation_pointlogs AS pl
			INNER JOIN gamedb.user_info AS ui ON pl.user_seq = ui.user_seq
		WHERE
			pl.parent_seq = ?
			AND pl.datecode = ?
		';
		$params = [$user_seq, $datecode];

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = intval($result->cnt);


		$query = 'SELECT
			pl.*, ui.nickname
		FROM
			member_relation_pointlogs AS pl
			INNER JOIN gamedb.user_info AS ui ON pl.user_seq = ui.user_seq
		WHERE
			pl.parent_seq = ?
			AND pl.datecode = ?
		ORDER BY
			pl.idx DESC
		LIMIT
			?, ?
		';
		$params = [$user_seq, $datecode, intval(($page - 1) * $limit), intval($limit)];


		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

		return $retval;
	}



	public static function getRelationMembers($user_seq, $depth, $page=1, $limit=20)
	{
		$retval = [];


		$query = 'SELECT
			COUNT(DISTINCT r.user_seq) AS `cnt`
		FROM
			member_relations AS r
			LEFT OUTER JOIN member_relation_points AS rp ON r.user_seq = rp.user_seq AND datecode = 0
			LEFT OUTER JOIN gamedb.user_info AS ui ON r.user_seq = ui.user_seq
			LEFT OUTER JOIN accountdb.account_info AS ai ON r.user_seq = ai.user_seq
			LEFT OUTER JOIN auth_platform.member AS m ON r.user_seq = m.user_seq
		WHERE
			r.parent_seq = ?
			AND r.max_depth = ? - 1
		';
		$params = [$user_seq, $depth];

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = intval($result->cnt);


		$query = 'SELECT
			r.idx, r.parent_seq, r.user_seq, ui.nickname, m.phone, m.phone_auth, IF (c.di IS NULL, 0, 1) AS `phone_auth2`, ai.platform_id, r.max_depth, SUM(IFNULL(rp.total_point, 0)) AS `point`, r.created
		FROM
			member_relations AS r
			LEFT OUTER JOIN member_relation_points AS rp ON rp.user_seq = r.user_seq AND datecode = 0
			LEFT OUTER JOIN gamedb.user_info AS ui ON ui.user_seq = r.user_seq
			LEFT OUTER JOIN accountdb.account_info AS ai ON ai.user_seq = r.user_seq
			LEFT OUTER JOIN auth_platform.member AS m ON m.user_seq = r.user_seq
			LEFT OUTER JOIN accountdb.certification AS c ON c.user_seq = r.user_seq
		WHERE
			r.parent_seq = ?
			AND r.max_depth = ? - 1
		GROUP BY
			r.user_seq
		ORDER BY
			r.idx ASC
		LIMIT
			?, ?
		';
		$params = [$user_seq, $depth, intval(($page - 1) * $limit), intval($limit)];

		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

		error_log("getRelationMembers ::\n".$query."\n".json_encode($params));

		return $retval;
	}



	public static function getRelationMemberDetail($user_seq)
	{
		$query = 'SELECT
		m.id, m.userid, m.user_seq, m.name, m.sex, m.phone, m.phone_auth, IF (c.di IS NULL, 0, 1) AS `phone_auth2`, m.CI, m.DI, m.login_ip, m.created_at, ui.nickname, ui.gold, SUM(IFNULL(rp.total_point, 0)) AS `point`
		FROM
			auth_platform.member AS m
			INNER JOIN gamedb.user_info AS ui ON m.user_seq = ui.user_seq
			LEFT OUTER JOIN auth_platform.member_relation_points AS rp ON m.user_seq = rp.user_seq AND rp.datecode = 0
			LEFT OUTER JOIN accountdb.certification AS c ON c.user_seq = m.user_seq
		WHERE
			m.user_seq = ?
		';
		$params = [$user_seq];

		error_log("getRelationMemberDetail ::\n".$query."\n".json_encode($params));

		return collect(DB::connection('platform')->select($query, $params))->first();
	}



	public static function setMemberNickname($user_seq, $nickname)
	{
		$query = 'UPDATE
			gamedb.user_info
		SET
			nickname = ?
		WHERE
			user_seq = ?
		';
		$params = [$nickname, $user_seq];


		return collect(DB::connection('platform')->update($query, $params));
	}



	public static function setMemberName($user_seq, $name, $sex)
	{
		$query = 'UPDATE
			auth_platform.member
		SET
			name = ?
			, sex = ?
		WHERE
			user_seq = ?
		';
		$params = [$name, $sex, $user_seq];

		return collect(DB::connection('platform')->update($query, $params));
	}



	public static function setMemberPhone($user_seq, $phone, $auth)
	{
		$query = 'UPDATE
			auth_platform.member
		SET
			phone = ?
			, phone_auth = ?
		WHERE
			user_seq = ?
		';
		$params = [$phone, $auth, $user_seq];

		return collect(DB::connection('platform')->update($query, $params));
	}



	public static function sendMemberRelationPoint($code, $sender_seq, $user_seq, $point, $reason='')
	{
		$query = "CALL auth_platform.sendMemberRelationPoint(?, ?, ?, ?, ?)";
		$params = [$code, $sender_seq, $user_seq, $point, $reason];

		$result =  DB::connection('mysql')->select($query, $params);

		return empty($result[0]) ? false : $result[0];
	}



	public static function cancelMemberRelationPointReward($idx, $reason)
	{
		$query = "CALL auth_platform.cancelMemberRelationPointReward(?, ?)";
		$params = [$idx, $reason];

		$result =  DB::connection('mysql')->select($query, $params);

		return empty($result[0]) ? false : $result[0];
	}



	public static function getMemberRelationSendPointLogs($t='', $q='', $page=1, $limit=20, $master=null)
	{
		$retval = [];


		// 카운트.
		$query = 'SELECT
			COUNT(rsp.idx) AS `cnt`
		FROM
			member_relation_sendpoints AS rsp
			LEFT OUTER JOIN auth_platform.member AS um ON rsp.user_seq = um.user_seq
			LEFT OUTER JOIN gamedb.user_info AS uu ON rsp.user_seq = uu.user_seq
			LEFT OUTER JOIN auth_platform.member AS sm ON rsp.sender_seq = sm.user_seq
			LEFT OUTER JOIN gamedb.user_info AS su ON rsp.sender_seq = su.user_seq
			LEFT OUTER JOIN gamedb.present AS ps ON rsp.send_idx = ps.present_seq
		WHERE
			1
		';
		$params = [];

		if (!empty($master))
			$query .= ' AND rsp.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = '.$master.' UNION ALL SELECT '.$master.') ';

		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':
					$query .= ' AND um.userid = ? ';
					array_push($params, $q);
					break;

				case 't':
					$query .= " AND uu.nickname LIKE '".$q."%' ";
					break;
			}
		}

		error_log("getMemberRelationSendPointLogs ::\n".$query."\n".json_encode($params));

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = intval($result->cnt);



		// 데이터 목록 색인.
		$query = 'SELECT
			rsp.*,
			sm.userid AS `sender_id`,
			su.nickname AS `sender_nickname`,
			um.userid AS `user_id`,
			uu.nickname AS `user_nickname`,
			IF (ps.is_read IS NULL OR is_read = 1, 1, ps.is_read) AS `read`
		FROM
			member_relation_sendpoints AS rsp
			LEFT OUTER JOIN auth_platform.member AS um ON rsp.user_seq = um.user_seq
			LEFT OUTER JOIN gamedb.user_info AS uu ON rsp.user_seq = uu.user_seq
			LEFT OUTER JOIN auth_platform.member AS sm ON rsp.sender_seq = sm.user_seq
			LEFT OUTER JOIN gamedb.user_info AS su ON rsp.sender_seq = su.user_seq
			LEFT OUTER JOIN gamedb.present AS ps ON rsp.send_idx = ps.present_seq
		WHERE
			1
		';
		$params = [];

		if (!empty($master))
			$query .= ' AND rsp.user_seq IN (SELECT user_seq FROM member_relations WHERE parent_seq = '.$master.' UNION ALL SELECT '.$master.') ';

		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':
					$query .= ' AND um.userid = ? ';
					array_push($params, $q);
					break;

				case 't':
					$query .= " AND uu.nickname LIKE '".$q."%' ";
					break;
			}
		}

		$query .= '
		ORDER BY
			idx DESC
		LIMIT
			?, ?
		';
		array_push($params, ($page - 1) * $limit, $limit);

		error_log("getMemberRelationSendPointLogs ::\n".$query."\n".json_encode($params));

		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

		return $retval;
	}



	public static function getLogs($parent_seq=null, $depth=0, $t='', $q='', $sdate='', $edate='', $page=1, $limit=20)
	{
		$retval = [];


		$query = 'SELECT
			COUNT(r.idx) AS `cnt`
		FROM
			member_relations AS r
			INNER JOIN member AS m ON m.user_seq = r.user_seq
			LEFT OUTER JOIN gamedb.user_info AS uu ON uu.user_seq = r.user_seq
		WHERE
			1
		';

		$params = [];

		if (!empty($parent_seq))
		{
			$query .= ' AND r.parent_seq = ? ';
			array_push($params, $parent_seq);
		}

		$query .= ' AND r.max_depth = ? ';
		array_push($params, $depth);


		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':
					$query .= ' AND m.userid = ? ';
					array_push($params, $q);
					break;

				case 'n':
					$query .= " AND uu.nickname LIKE '".$q."%' ";
					break;
			}
		}

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = intval($result->cnt);



		$sdate = intval(empty($sdate) ? date('Ymd') : str_replace('-', '', $sdate));
		$edate = intval(empty($edate) ? date('Ymd') : str_replace('-', '', $edate));


		$query = 'SELECT
			r.idx, r.parent_seq, r.user_seq, r.max_depth,
			m.userid, uu.nickname, uu.gold,
			SUM(rp.total_point) AS `sum_point`,
			SUM(rp.total_purchase) AS `sum_purchase`,
			SUM(rp.total_spend) AS `sum_spend`,
			SUM(rp.total_payout) AS `sum_payout`,
			SUM(rp.total_payout) - SUM(rp.total_spend) - SUM(rp.total_purchase) AS `sum_gains`,
			(SELECT COUNT(sr.idx) AS `cnt` FROM member_relations AS sr INNER JOIN auth_platform.member AS sm ON sm.user_seq = sr.user_seq WHERE sr.parent_seq = r.user_seq AND sr.max_depth = r.max_depth + 1) AS `childs`,
			(SELECT COUNT(sr.idx) AS `cnt` FROM member_relations AS sr INNER JOIN auth_platform.member AS sm ON sm.user_seq = sr.user_seq WHERE sr.parent_seq = r.user_seq) AS `totals`
		FROM
			member_relations AS r
			INNER JOIN member AS m ON m.user_seq = r.user_seq
			LEFT OUTER JOIN gamedb.user_info AS uu ON uu.user_seq = r.user_seq
			LEFT OUTER JOIN member_relation_points AS rp ON rp.user_seq = r.user_seq AND datecode '.($sdate == $edate ? '='.$sdate : 'BETWEEN '.$sdate.' AND '.$edate).' AND rp.code = ?
		WHERE
			1
		';
		$params = ['SLOT'];

		if (!empty($parent_seq))
		{
			$query .= ' AND r.parent_seq = ? ';
			array_push($params, $parent_seq);
		}

		$query .= ' AND r.max_depth = ? ';
		array_push($params, $depth);

		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':
					$query .= ' AND m.userid = ? ';
					array_push($params, $q);
					break;

				case 't':
					$query .= " AND uu.nickname LIKE '".$q."%' ";
					break;
			}
		}

		$query .= '
		GROUP BY
			r.user_seq
		ORDER BY
			r.user_seq
		LIMIT
			?, ?
		';
		array_push($params, ($page - 1) * intval($limit), intval($limit));

		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

		return $retval;
	}



	public static function getUserLogs($parent_seq=null, $depth=0, $t='', $q='', $sdate='', $edate='', $page=1, $limit=20)
	{
		$retval = [];


		$query = 'SELECT
			COUNT(r.idx) AS `cnt`
		FROM
			member_relations AS r
			INNER JOIN member AS m ON m.user_seq = r.user_seq
			LEFT OUTER JOIN gamedb.user_info AS uu ON uu.user_seq = r.user_seq
		WHERE
			1
		';

		$params = [];

		if (!empty($parent_seq))
		{
			$query .= ' AND r.parent_seq = ? ';
			array_push($params, $parent_seq);
		}

		$query .= ' AND r.max_depth = ? ';
		array_push($params, $depth);


		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':
					$query .= ' AND m.userid = ? ';
					array_push($params, $q);
					break;

				case 'n':
					$query .= " AND uu.nickname LIKE '".$q."%' ";
					break;
			}
		}

		$result = collect(DB::connection('platform')->select($query, $params))->first();

		$retval['count'] = intval($result->cnt);



		$sdate = intval(empty($sdate) ? date('Ymd') : str_replace('-', '', $sdate));
		$edate = intval(empty($edate) ? date('Ymd') : str_replace('-', '', $edate));

		$query = 'SELECT
			r.idx, r.parent_seq, r.user_seq, r.max_depth,
			m.userid, uu.nickname, uu.gold,
			0 AS `sum_point`,
			SUM(rl.purchase) AS `sum_purchase`,
			SUM(rl.spend) AS `sum_spend`,
			SUM(rl.payout) AS `sum_payout`,
			SUM(rl.payout) - SUM(rl.spend) - SUM(rl.purchase) AS `sum_gains`,
			(SELECT COUNT(sr.idx) AS `cnt` FROM member_relations AS sr INNER JOIN auth_platform.member AS sm ON sm.user_seq = sr.user_seq WHERE sr.parent_seq = r.user_seq AND sr.max_depth = r.max_depth + 1) AS `childs`,
			(SELECT COUNT(sr.idx) AS `cnt` FROM member_relations AS sr INNER JOIN auth_platform.member AS sm ON sm.user_seq = sr.user_seq WHERE sr.parent_seq = r.user_seq) AS `totals`
		FROM
			member_relations AS r
			INNER JOIN member AS m ON m.user_seq = r.user_seq
			LEFT OUTER JOIN gamedb.user_info AS uu ON uu.user_seq = r.user_seq
			LEFT OUTER JOIN member_relation_logs AS rl ON rl.user_seq = r.user_seq AND datecode '.($sdate == $edate ? '='.$sdate : 'BETWEEN '.$sdate.' AND '.$edate).' AND rl.code = ?
		WHERE
			1
		';
		$params = ['SLOT'];

		if (!empty($parent_seq))
		{
			$query .= ' AND r.parent_seq = ? ';
			array_push($params, $parent_seq);
		}

		$query .= ' AND r.max_depth = ? ';
		array_push($params, $depth);

		if (!empty($q))
		{
			switch ($t)
			{
				case 'u':
					$query .= ' AND m.userid = ? ';
					array_push($params, $q);
					break;

				case 't':
					$query .= " AND uu.nickname LIKE '".$q."%' ";
					break;
			}
		}

		$query .= '
		GROUP BY
			r.user_seq
		ORDER BY
			r.user_seq
		LIMIT
			?, ?
		';
		array_push($params, ($page - 1) * intval($limit), intval($limit));

		DB::connection('platform')->enableQueryLog();

		$retval['data'] = collect(DB::connection('platform')->select($query, $params));

//		var_dump(DB::connection('platform')->getQueryLog());

		return $retval;
	}

}





