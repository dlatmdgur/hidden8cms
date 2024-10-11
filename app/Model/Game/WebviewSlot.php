<?php

namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebviewSlot extends BaseModel
{
    protected $connection = 'slot_common';
    protected $table = 'external_slotlist';
    protected $guarded = [];


    public static function upsert(string $table, $data)
    {
        $cols   = [];
        $params = [];
        $vals   = [];
        $upds   = [];

        foreach ($data as $key => $val)
        {
            $cols[]     = $key;
            $vals[]     = '?';
            $params[]   = $val;
            $upds[]     = $key.' = ?';
        }

        $query = '  INSERT INTO `'.$table.'`
                    ('.implode(', ',$cols).')
                    VALUES ('.implode(', ', $vals).')
                    ON DUPLICATE KEY UPDATE '.implode(',', $upds);

        $params = array_merge($params, $params);

        return DB::connection('slot_common')->update($query, $params);

    }

    public static function updateAuthSlots(array $data, array $pks)
	{
		$cols = [];
		$binds = [];
		$where = [];
		$whereBinds = [];

		foreach ($data as $key => $val)
		{
			if (in_array($key ,$pks)) {
				$where[] = $key . ' = ?';
				$whereBinds[] = $val;
			}

			$cols[] = $key.' = ?';
			$binds[] = $val;
		}
		$query 	=	'UPDATE `auth_slotlist`
						SET '.implode(', ', $cols).'
						WHERE '.implode(' AND ', $where);


		$binds = array_merge($binds, $whereBinds);

		return DB::connection('slot_common')->update($query, $binds);
	}

}
