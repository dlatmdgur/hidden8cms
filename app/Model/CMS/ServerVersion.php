<?php

namespace App\Model\CMS;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServerVersion extends BaseModel
{
	protected $connection = 'mysql';
	protected $table = 'accountdb.version_url';
	protected $primaryKey = 'idx';
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'version', 'desc', 'cdn', 'web_world'
		, 'slot_server', 'slot_lobby', 'server_status', 'server_notice', 'tournament_lobby', 'http_lobby', 'api',
	];


	public static function upsert(string $table, array $data)
	{
		$cols 	= [];
		$params = [];
		$vals 	= [];
		$upds 	= [];

		foreach ($data as $key => $val)
		{
			$cols[] 		= $key;
			$vals[]			= '?';
			$params[]		= $val;
			$upds[] = $key.' = ?';
		}

		$query = '	INSERT INTO '.$table.'
				 	('.implode(', ',$cols).')
					VALUES ('.implode(', ', $vals).')
					ON DUPLICATE KEY UPDATE '.implode(', ', $upds);

		$params = array_merge($params, $params);

		return DB::connection('mysql')->update($query, $params);
	}
}
