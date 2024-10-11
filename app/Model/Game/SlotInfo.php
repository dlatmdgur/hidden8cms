<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;


class SlotInfo extends BaseModel
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



	public static function getSlotAll()
	{
		$query = 'SELECT
			slot_id, slot_name, slot_type, slot_group, group_sorted, opened, sorted, open_level AS `level`, badge_new AS `is_new`, badge_jackpot AS `is_jackpot`
		FROM
			sw_slot_list
		WHERE
			opened = 1
		UNION
		SELECT
			slot_id, slot_name, slot_type, slot_group, group_sorted, opened, sorted, level, is_new, is_jackpot
		FROM
			sw_refer_slots
		WHERE
			opened = 1
		ORDER BY
			 sorted ASC,
			 group_sorted ASC;
		';

		return collect(DB::connection('slot_system')->select($query));
	}


	public static function getSlots()
	{
		$query = 'SELECT
			a.*, b.ver
		FROM
			sw_slot_list AS a
			LEFT OUTER JOIN sw_slot_game_data AS b ON a.slot_id = b.slot_id AND active = 1
		ORDER BY
			 sorted ASC,
			 group_sorted ASC
		';

		return collect(DB::connection('slot_system')->select($query));
	}


	public static function setSlot($data)
	{
		$qb = self::buildInsertQuery('sw_slot_list', $data);

		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}


	public static function dropSlot($slot_id)
	{
		$query = 'DELETE FROM sw_slot_list WHERE slot_id=? ';
		$params = array ($slot_id);

		return collect(DB::connection('slot_system')->delete($query, $params));
	}


	public static function setSlotActive($slot_id, $ver)
	{
		$query = 'UPDATE sw_slot_game_data SET active=0 WHERE slot_id=? AND active=1 ';
		$params = array ($slot_id);

		collect(DB::connection('slot_system')->update($query, $params));

		$query = 'UPDATE sw_slot_game_data SET active=1 WHERE slot_id=? AND ver=? ';
		array_push($params, $ver);

		return collect(DB::connection('slot_system')->update($query, $params))[0];
	}


	public static function getGameData($slot_id, $ver='')
	{
		$query = 'SELECT * FROM sw_slot_game_data WHERE slot_id=? ';
		$params = array ($slot_id);

		if ($ver)
		{
			$query .= 'AND ver=? ';
			array_push($params, $ver);
		}

		$query .= 'ORDER BY ver ';

		return collect(DB::connection('slot_system')->select($query, $params));
	}


	public static function setGameData($data)
	{
		$qb = self::buildInsertQuery('sw_slot_game_data', $data);

		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}


	public static function dropGameData($slot_id, $ver, $env)
	{
		$query = 'DELETE FROM sw_slot_game_data WHERE slot_id=? AND ver=? AND env=? ';
		$params = array ($slot_id, $ver, $env);

		return collect(DB::connection('slot_system')->delete($query, $params));
	}





	public static function getBetInfo($slot_id)
	{
		$query = 'SELECT
			*
		FROM
			sw_bet_info_by_room
		WHERE
			slot_id=?
		';
		$params = array ($slot_id);

		return collect(DB::connection('slot_system')->select($query, $params));
	}


	public static function setBetInfo($data)
	{
		$qb = self::buildInsertQuery('sw_bet_info_by_room', $data);

		return collect(DB::connection('slot_system')->insert($qb['query'], $qb['params']));
	}


	public static function dropBetInfo($slot_id, $room)
	{
		$query = 'DELETE FROM sw_bet_info_by_room WHERE slot_id=? AND room=? ';
		$params = array ($slot_id, $room);

		return collect(DB::connection('slot_system')->delete($query, $params));
	}






}
