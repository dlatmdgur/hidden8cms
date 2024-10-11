<?php


namespace App\Model\Statistics;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class StatsUsers extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'stats.daily_new_user';
    protected $primaryKey = 'date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'item_seq', '_before', '_after',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'request_date' => 'datetime',
        // 'confirm_date' => 'datetime',
    ];

    public static function getDailyLog($startDate, $endDate)
    {
        $sql = "SELECT N.log_date, N.cnt AS users_new, IFNULL(D.cnt, 0) AS users_del, IFNULL(A.cnt, 0) AS users_active, IFNULL(P.cnt, 0) AS users_play,
                        IFNULL(C.avg, 0) as ccu_avg, IFNULL(C.max, 0) as ccu_max, IFNULL(C.min, 0) as ccu_min
                FROM
                    (
                        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS log_date, cnt FROM stats.daily_new_user WHERE DATE_FORMAT(`date`, '%Y-%m-%d') BETWEEN ? AND ?
                    ) N
                    LEFT JOIN
                    (
                        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS log_date, SUM(cnt) AS cnt FROM stats.daily_del_user WHERE DATE_FORMAT(`date`, '%Y-%m-%d') BETWEEN ? AND ?
                        GROUP BY DATE_FORMAT(`date`, '%Y-%m-%d')
                    ) D ON N.log_date = D.log_date
                    LEFT JOIN
                    (
                        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS log_date, cnt FROM stats.daily_active_user WHERE DATE_FORMAT(`date`, '%Y-%m-%d') BETWEEN ? AND ?
                    ) A ON N.log_date = A.log_date
                    LEFT JOIN
                    (
                        SELECT DATE_FORMAT(`date`, '%Y-%m-%d') AS log_date, cnt FROM stats.daily_activeplay_user WHERE DATE_FORMAT(`date`, '%Y-%m-%d') BETWEEN ? AND ?
                    ) P ON N.log_date = P.log_date
                    LEFT JOIN
                    (
                        SELECT ANY_VALUE(DATE_FORMAT(ccu_date, '%Y-%m-%d')) AS log_date, AVG(ccu) AS `avg`, Max(ccu) as `max`, Min(ccu) as `min`
                        FROM accountdb.ccu
                        WHERE DATE_FORMAT(ccu_date, '%Y-%m-%d') >= ? AND DATE_FORMAT(ccu_date, '%Y-%m-%d') <= ?
                        GROUP BY SUBSTR(DATE_FORMAT(ccu_date, '%Y%m%d'), 1, 10), FLOOR(SUBSTR(DATE_FORMAT(ccu_date, '%Y%m%d'), 11, 2) / (60*24) )
                    ) C ON N.log_date = C.log_date
                ORDER BY log_date DESC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
    }

}
