<?php


namespace App\Model\Statistics;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class StatsCcu extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.ccu';
    protected $primaryKey = 'idx';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ccu_date', 'ccu'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'log_date' => 'datetime',
    ];

    public static function getDailyCcu($targetDate)
    {
        $sql = " SELECT idx, ccu_date, ccu
                 FROM accountdb.ccu
                 WHERE DATE_FORMAT(ccu_date, '%Y-%m-%d') = ?
                 ORDER BY ccu_date ASC";

        return DB::connection('mysql')->select($sql, array($targetDate));
    }

    public static function getCcu($startDate, $endDate)
    {
        $sql = " SELECT idx, ccu_date, ccu
                 FROM accountdb.ccu
                 WHERE DATE_FORMAT(ccu_date, '%Y-%m-%d') >= ? AND DATE_FORMAT(ccu_date, '%Y-%m-%d') <= ?
                 ORDER BY ccu_date ASC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getCcuByMinute($startDate, $endDate, $minute)
    {
        $sql = " SELECT ANY_VALUE(DATE_FORMAT(ccu_date, '%Y-%m-%d %H:%i')) as log_date, COUNT(*) AS cnt, AVG(ccu) as avg, MAX(ccu) as max, MIN(ccu) as min
                 FROM accountdb.ccu
                 WHERE DATE_FORMAT(ccu_date, '%Y-%m-%d') >= ? AND DATE_FORMAT(ccu_date, '%Y-%m-%d') <= ?
                 GROUP BY SUBSTR(DATE_FORMAT(ccu_date, '%Y%m%d%H%i%S'), 1, 10), FLOOR(SUBSTR(DATE_FORMAT(ccu_date, '%Y%m%d%H%i%S'), 11, 2) / ?)
                 ORDER BY log_date ASC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate, $minute));
    }

    public static function getCcuByDaily($startDate, $endDate)
    {
        $sql = " SELECT ANY_VALUE(DATE_FORMAT(ccu_date, '%Y-%m-%d')) as log_date, COUNT(*) AS cnt, AVG(ccu) as avg, MAX(ccu) as max, MIN(ccu) as min
                 FROM accountdb.ccu
                 WHERE DATE_FORMAT(ccu_date, '%Y-%m-%d') >= ? AND DATE_FORMAT(ccu_date, '%Y-%m-%d') <= ?
                 GROUP BY SUBSTR(DATE_FORMAT(ccu_date, '%Y%m%d%H%i%S'), 1, 8)
                 ORDER BY log_date ASC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

}
