<?php

namespace App\Model\CMS;

use App\BaseModel;
use App\Model\Tables\Item;
use App\Model\Tables\Member;
use App\Model\Tables\ProductName;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminLog extends BaseModel
{
	protected $connection = 'mysql';
	protected $table = 'cms_game.admin_logs';
	protected $primaryKey = 'id';
	public $timestamps = true;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'type', 'menu', 'action', 'log_type', 'params', 'reason', 'extra', 'user_seq', 'nickname',
		'before_value', 'after_value', 'before_state', 'after_state', 'admin_id', 'admin_name',
	//        'created_at', 'updated_at'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
	//        'created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s',
	];

	public static function getDailyChipGoldLogs($startDate, $endDate, $actionType, $target)
	{
		$whereSql = " WHERE 1 = 1";
		if ($actionType != "all") {
			$whereSql .= " AND L.actionType = '" . $actionType . "' ";
		}
		if ($target != "all") {
			$whereSql .= " AND L.target = '" . $target . "' ";
		}
		$sql = "SELECT L.logDate, L.target, L.actionType, L.logType, SUM(L.changeAmount) AS changeAmount ".
				" FROM ( ".
				"       SELECT `action`, log_type AS logType, reason, extra, user_seq, ".
				"       	    JSON_UNQUOTE(JSON_EXTRACT(params, '$.target')) AS target, ".
				"               JSON_UNQUOTE(JSON_EXTRACT(params, '$.actionType')) AS actionType, ".
				"               JSON_UNQUOTE(JSON_EXTRACT(params, '$.changeAmount')) AS changeAmount, ".
				"               DATE_FORMAT(created_at, '%Y-%m-%d') AS logDate ".
				"       FROM cms_game.admin_logs ".
				"       WHERE `action` = 'editChipGold' ".
				"           AND created_at BETWEEN ? AND ? ".
				" ) L ".
				$whereSql .
				" GROUP BY L.logDate, L.target, L.actionType, L.logType ".
				" ORDER BY L.logDate DESC, L.target ASC, L.actionType ASC, L.logType ASC";

		return DB::connection('mysql')->select($sql, array($startDate, $endDate));
	}

	public static function getChipGoldLogsByUser($userSeq, $startDate, $endDate, $actionType, $target)
	{
		$whereSql = " WHERE 1 = 1";
		if ($actionType != "all") {
			$whereSql .= " AND L.actionType = '" . $actionType . "' ";
		}
		if ($target != "all") {
			$whereSql .= " AND L.target = '" . $target . "' ";
		}

		$sql = " SELECT L.logDate, L.target, L.changeAmount, L.nickname, L.actionType, L.reason, L.extra, L.admin_name, L.user_no ".
				" FROM ( ".
				"        SELECT AL.created_at AS logDate, AL.log_type AS logType, AL.reason, AL.extra, AL.admin_name, AI.nickname, AI.user_seq AS user_no,".
				"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.target')) AS target,  ".
				"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.actionType')) AS actionType, ".
				"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.changeAmount')) AS changeAmount ".
				"        FROM cms_game.admin_logs AL ".
				"        LEFT JOIN accountdb.account_info AI ".
				"        ON AL.user_seq = AI.user_seq ".
				"        WHERE  AL.action = 'editChipGold' ";

		if (! empty($userSeq))
		{
			$conditions = [$userSeq, $startDate, $endDate];
			$sql .= "   AND AL.user_seq = ? ";
		}
		else
			$conditions = [$startDate, $endDate];

		$sql .= "             AND AL.created_at BETWEEN ? AND ? ";
		$sql .= " ) L ";
		$sql .= ($whereSql.
		" ORDER BY L.logDate DESC");
		return DB::connection('mysql')->select($sql, $conditions);

	}

	public static function getDailyPostsLogs($startDate, $endDate, $actionType)
	{
		$whereSql = " WHERE 1 = 1";
		if ($actionType != "all") {
			$whereSql .= " AND L.actionType = '" . $actionType . "' ";
		}

		$sql = " SELECT L.logDate, L.actionType, L.presentType, SUM(L.changeAmount) AS changeAmount ".

		" FROM ( ".
		"        SELECT DATE_FORMAT(AL.created_at, '%Y-%m-%d') AS logDate, AL.reason, AL.extra,".
		"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.actionType')) AS actionType, ".
		"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.presentType')) AS presentType, ".
		"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.changeAmount')) AS changeAmount ".
		"        FROM cms_game.admin_logs AL ".
		"        LEFT JOIN accountdb.account_info AI ".
		"        ON AL.user_seq = AI.user_seq ".
		"        WHERE  AL.action = 'editPresent' ".
		"             AND AL.created_at BETWEEN ? AND ? ".
		" ) L ".
		$whereSql .
		" GROUP BY L.logDate, L.presentType, L.actionType
		  ORDER BY L.logDate DESC, L.presentType ASC , L.actionType ASC";
		$adminLog = DB::connection('mysql')->select($sql, array($startDate, $endDate));

		// $itemNames = Item::getItemNames();
		// foreach($adminLog as $index => $logs) {
		// 	$adminLog[$index]->presentType = $itemNames[$logs->presentType];
		// }

		return $adminLog;
	}

	public static function getPostsLogsByUser($userSeq, $startDate, $endDate, $actionType, $target = 'all')
	{
		$bindValue = array();

		$whereSql = " WHERE 1 = 1";
		if ($actionType != "all") {
			$whereSql .= " AND L.actionType = '" . $actionType . "' ";
		}
		if ($target != 'all') {
			$whereSql .= " AND L.presentType = '" . $target . "' ";
		}

		$sql = " SELECT L.logDate, L.actionType, L.presentType, L.changeAmount, L.nickname, L.reason, L.extra, L.admin_name, L.user_seq ".
			" FROM ( ".
			"        SELECT AL.created_at AS logDate, AL.log_type AS logType, AL.reason, AL.extra, AL.admin_name, AI.nickname, AI.user_seq, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.actionType')) AS actionType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.presentType')) AS presentType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.changeAmount')) AS changeAmount ".
			"        FROM cms_game.admin_logs AL ".
			"        LEFT JOIN accountdb.account_info AI ".
			"        ON AL.user_seq = AI.user_seq ".
			"        WHERE  AL.action = 'editPresent' ";
			if ($userSeq !== null)
			{
				$sql .= " AND AL.user_seq = ? ";




				$bindValue[] = $userSeq;


			}
				$bindValue[] = $startDate;
				$bindValue[] = $endDate;

			$sql .= "	AND AL.created_at BETWEEN ? AND ? ";
			$sql .= " ) L ";
			$sql .= $whereSql .
			" ORDER BY L.logDate DESC";

		$adminLog = DB::connection('mysql')->select($sql, $bindValue);

		// $itemNames = Item::getItemNames();
		// foreach($adminLog as $index => $logs) {
		// 	$adminLog[$index]->presentName = $itemNames[$logs->presentType];
		// }

		return $adminLog;
	}

	public static function getDailyGemLogs($startDate, $endDate, $actionType)
	{
		$whereSql = " WHERE 1 = 1";
		if ($actionType != "all") {
			$whereSql .= " AND L.actionType = '" . $actionType . "' ";
		}

		$sql = "SELECT L.logDate, L.target, L.actionType, L.logType, SUM(L.changeAmount) AS changeAmount ".
			" FROM ( ".
			"       SELECT `action`, log_type AS logType, reason, extra, user_seq, ".
			"       	    JSON_UNQUOTE(JSON_EXTRACT(params, '$.target')) AS target, ".
			"               JSON_UNQUOTE(JSON_EXTRACT(params, '$.actionType')) AS actionType, ".
			"               JSON_UNQUOTE(JSON_EXTRACT(params, '$.changeAmount')) AS changeAmount, ".
			"               DATE_FORMAT(created_at, '%Y-%m-%d') AS logDate ".
			"       FROM cms_game.admin_logs ".
			"       WHERE `action` = 'editGem' ".
			"           AND created_at BETWEEN ? AND ? ".
			" ) L ".
			$whereSql .
			" GROUP BY L.logDate, L.target, L.actionType, L.logType ".
			" ORDER BY L.logDate DESC, L.target ASC, L.actionType ASC, L.logType ASC";
		return DB::connection('mysql')->select($sql, array($startDate, $endDate));
	}

	public static function getGemLogsByUser($userSeq, $startDate, $endDate, $actionType)
	{
		$whereSql = " WHERE 1 = 1";
		if ($actionType != "all") {
			$whereSql .= " AND L.actionType = '" . $actionType . "' ";
		}

		$sql = " SELECT L.logDate, L.actionType, L.target, L.changeAmount, L.nickname, L.reason, L.extra, L.admin_name ".
			" FROM ( ".
			"        SELECT AL.created_at AS logDate, AL.log_type AS logType, AL.reason, AL.extra, AL.admin_name, AI.nickname, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.actionType')) AS actionType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.target')) AS target, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.changeAmount')) AS changeAmount ".
			"        FROM cms_game.admin_logs AL ".
			"        LEFT JOIN accountdb.account_info AI ".
			"        ON AL.user_seq = AI.user_seq ".
			"        WHERE  AL.action = 'editGem' ".
			"             AND AL.user_seq = ? ".
			"             AND AL.created_at BETWEEN ? AND ? ".
			" ) L ".
			$whereSql .
			" ORDER BY L.logDate DESC";
		$adminLog = DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));

		foreach($adminLog as $index => $logs) {
			$adminLog[$index]->presentName = ($logs->target == "gem")? "유료보석" : "무료보석";
		}

		return $adminLog;
	}

	public static function getEffectLogsByUser($userSeq, $startDate, $endDate)
	{
		$sql = " SELECT L.logDate, L.actionType, L.target, L.itemId, L.membersType, L.beforeEndDate, L.afterEndDate, L.nickname, L.reason, L.extra, L.admin_name ".
			" FROM ( ".
			"        SELECT AL.created_at AS logDate, AL.log_type AS logType, AL.reason, AL.extra, AL.admin_name, AI.nickname, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.actionType')) AS actionType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.target')) AS target, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.itemId')) AS itemId, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.membersType')) AS membersType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.before_value, '$.endDate')) AS beforeEndDate, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.after_value, '$.endDate')) AS afterEndDate ".
			"        FROM cms_game.admin_logs AL ".
			"        LEFT JOIN accountdb.account_info AI ".
			"        ON AL.user_seq = AI.user_seq ".
			"        WHERE  AL.action = 'editEffect' ".
			"             AND AL.user_seq = ? ".
			"             AND AL.created_at BETWEEN ? AND ? ".
			" ) L ".
			" ORDER BY L.logDate DESC";
		$adminLog = DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));

		$membersNames = Member::getMembersNames();
		$itemNames = Item::getItemNames();
		foreach($adminLog as $index => $logs) {
			$adminLog[$index]->productName = ($logs->target == "members")? $membersNames[$logs->membersType] : $itemNames[$logs->itemId];
		}

		return $adminLog;
	}

	public static function getSendLogs($startDate, $endDate)
	{
		$sql = " SELECT L.logDate, L.actionType, L.presentType, L.changeAmount, L.startDateTime, L.endDateTime, L.sendUserCount, L.sendResult, L.reason, L.extra, L.admin_name ".
			" FROM ( ".
			"        SELECT AL.created_at AS logDate, AL.log_type AS logType, AL.reason, AL.extra, AL.admin_name, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.actionType')) AS actionType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.presentType')) AS presentType, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.params, '$.changeAmount')) AS changeAmount, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.before_value, '$.startTime')) AS startDateTime, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.after_value, '$.endTime')) AS endDateTime, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.after_value, '$.sendUserCount')) AS sendUserCount, ".
			"             JSON_UNQUOTE(JSON_EXTRACT(AL.after_value, '$.result')) AS sendResult ".
			"        FROM cms_game.admin_logs AL ".
			"        WHERE  AL.action = 'send' ".
			"             AND AL.created_at BETWEEN ? AND ? ".
			" ) L ".
			" ORDER BY L.logDate DESC ";
		$adminLog = DB::connection('mysql')->select($sql, array($startDate, $endDate));

		$itemNames = Item::getItemNames();
		foreach($adminLog as $index => $logs) {
			$adminLog[$index]->productName = $itemNames[$logs->presentType];
		}

		return $adminLog;
	}

	public static function getEvtJackpotLogs($sdate, $edate, $page, $offset = 20)
	{
		$logs = DB::connection('mysql')->table('sw_assigns.global_events_users AS eu')
										->join('accountdb.account_info AS ai', 'ai.user_seq', '=', 'eu.uid')
										->select('ai.nickname', 'eu.updated', 'eu.created', 'eu.start_time','eu.status', 'eu.reward_rate')
										->whereBetween('created', [$sdate, $edate])
										->orderby('created', 'DESC')
										->paginate($offset, ['*'], 'page', $page);
		return $logs;

	}
}

