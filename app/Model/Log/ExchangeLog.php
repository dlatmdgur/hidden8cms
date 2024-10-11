<?php


namespace App\Model\Log;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class ExchangeLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.exchange_confirm_log';
    protected $primaryKey = 'log_idx';

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

    public static function getDailyExchangeLog($startDate, $endDate)
    {
        $sql = "SELECT log_date, MAX(IF(`type` = 'coin_to_gold', total_amount, 0)) AS gold_amount, MAX(IF(`type` = 'coin_to_gold', total_count, 0)) AS gold_count,
                     MAX(IF(`type` = 'gold_to_coin', total_amount, 0)) AS coin_amount, MAX(IF(`type` = 'gold_to_coin', total_count, 0)) AS coin_count
                FROM (
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d') AS log_date, `type`, SUM(amount) AS total_amount, COUNT(*) AS total_count
                    FROM logdb.exchange_confirm_log L
                    LEFT JOIN gamedb.evt E ON L.user_seq = E.user_seq
                    LEFT JOIN gamedb.evt1 E1 ON L.user_seq = E1.user_seq
                    WHERE `type` = 'gold_to_coin' AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                    AND E.user_seq IS NULL
                    AND E1.user_seq IS NULL
                    GROUP BY DATE_FORMAT(confirm_date, '%Y-%m-%d')
                    UNION ALL
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d') AS log_date, `type`, SUM(amount) AS total_amount, COUNT(*) AS total_count
                    FROM logdb.exchange_confirm_log L
                    LEFT JOIN gamedb.evt E ON L.user_seq = E.user_seq
                    LEFT JOIN gamedb.evt1 E1 ON L.user_seq = E1.user_seq
                    LEFT JOIN gamedb.evtc EC ON L.user_seq = EC.user_seq
                    WHERE `type` = 'coin_to_gold' AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                        AND E.user_seq IS NULL AND E1.user_seq IS NULL AND EC.user_seq IS NULL
                    GROUP BY DATE_FORMAT(confirm_date, '%Y-%m-%d')
                ) Z
                GROUP BY log_date
                ORDER BY log_date DESC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate, $startDate, $endDate));
    }

    public static function getDailyExchangeLogByUser($userSeq, $startDate, $endDate)
    {
        $sql = "SELECT log_date, user_seq, nickname, MAX(IF(`type` = 'coin_to_gold', total_amount, 0)) AS gold_amount, MAX(IF(`type` = 'coin_to_gold', total_count, 0)) AS gold_count,
                                                     MAX(IF(`type` = 'gold_to_coin', total_amount, 0)) AS coin_amount, MAX(IF(`type` = 'gold_to_coin', total_count, 0)) AS coin_count
                FROM (
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d') AS log_date, L.user_seq, A.nickname, `type`, SUM(amount) AS total_amount, COUNT(*) AS total_count
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE `type` = 'gold_to_coin' AND L.user_seq = ?
                    AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(confirm_date, '%Y-%m-%d'), L.user_seq, A.nickname
                    UNION ALL
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d') AS log_date, L.user_seq, A.nickname, `type`, SUM(amount) AS total_amount, COUNT(*) AS total_count
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE `type` = 'coin_to_gold' AND L.user_seq = ?
                    AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(confirm_date, '%Y-%m-%d'), L.user_seq, A.nickname
                ) Z
                GROUP BY log_date, user_seq, nickname
                ORDER BY log_date DESC";

        return DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate, $userSeq, $startDate, $endDate));
    }

    public static function getExchangeLogOverLimit($limit, $startDate, $endDate)
    {
        $sql = "SELECT log_date, user_seq, nickname, `type`, IF(`type` = 'coin_to_gold', total_amount, 0) AS gold_amount, IF(`type` = 'gold_to_coin', total_amount, 0) AS coin_amount
                FROM (
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d %H:%i:%s') AS log_date, L.user_seq, A.nickname, `type`, amount AS total_amount
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE `type` = 'gold_to_coin' AND amount >= ?
                    AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                    UNION ALL
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d %H:%i:%s') AS log_date, L.user_seq, A.nickname, `type`, amount AS total_amount
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE `type` = 'coin_to_gold' AND amount >= ?
                    AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                ) Z
                ORDER BY log_date DESC";

        return DB::connection('mysql')->select($sql, array($limit, $startDate, $endDate, $limit, $startDate, $endDate));
    }

    public static function getExchangeLogByUser($userSeq, $startDate, $endDate)
    {
        $sql = "SELECT log_date, user_seq, nickname, IF(`type` = 'coin_to_gold', amount, 0) AS gold_amount, IF(`type` = 'gold_to_coin', amount, 0) AS coin_amount
                FROM (
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d %H:%i:%s') AS log_date, L.user_seq, A.nickname, `type`, amount
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE `type` = 'gold_to_coin' AND L.user_seq = ?
                    AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?

                    UNION ALL
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d %H:%i:%s') AS log_date, L.user_seq, A.nickname, `type`, amount
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE `type` = 'coin_to_gold' AND L.user_seq = ?
                    AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?

                ) Z
                ORDER BY log_date DESC";

        return DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate, $userSeq, $startDate, $endDate));
    }

    public static function getTodayTopGoldLog($limit)
    {
        $sql = "SELECT * FROM (
                    SELECT DATE_FORMAT(confirm_date, '%Y-%m-%d') AS log_date, `type`, L.user_seq, A.nickname, SUM(amount) AS total_amount, COUNT(*) AS total_count
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    LEFT JOIN gamedb.evt E ON L.user_seq = E.user_seq
                    LEFT JOIN gamedb.evt1 E1 ON L.user_seq = E1.user_seq
                    LEFT JOIN gamedb.evtc EC ON L.user_seq = EC.user_seq
                    WHERE `type` = 'coin_to_gold' AND DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -3 day), '%Y-%m-%d') AND DATE_FORMAT(NOW(), '%Y-%m-%d')
                    AND E.user_seq IS NULL AND E1.user_seq IS NULL AND EC.user_seq IS NULL
                    GROUP BY DATE_FORMAT(confirm_date, '%Y-%m-%d'), `type`, L.user_seq, A.nickname
                    ORDER BY total_amount DESC
                ) EX
                WHERE EX.total_amount >= ?
                ORDER BY log_date DESC, total_amount DESC";
                //LIMIT 3";

        return DB::connection('mysql')->select($sql, array($limit));
    }

    public static function getExchangeLogByDate( $startDate, $endDate)
    {
        $sql = "SELECT log_date, user_seq, nickname, `type`, IF(`type` = 'coin_to_gold', total_amount, 0) AS gold_amount, IF(`type` = 'gold_to_coin', total_amount, 0) AS coin_amount
                FROM (
                    SELECT confirm_date AS log_date, L.user_seq, A.nickname, `type`, amount AS total_amount
                    FROM logdb.exchange_confirm_log L
                    JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                    WHERE DATE_FORMAT(confirm_date, '%Y-%m-%d') BETWEEN ? AND ?
                ) Z
                ORDER BY log_date DESC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }
}
