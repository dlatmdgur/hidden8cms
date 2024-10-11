<?php

namespace App\Model\Log;

use Illuminate\Support\Facades\DB;

class SafeLog
{
    protected $connection = 'mysql';

    public static function getSafeLogs($userSeq, $logType, $startDate, $endDate)
    {
        $chipSql = "         SELECT log_date, 'chip' AS money_type, safe_type, amount, before_chip AS before_money, after_chip AS after_money ".
                   "         , before_safe_chip AS before_safe_money, after_safe_chip AS after_safe_money".
                   "         FROM logdb.safe_chip_log ".
                   "         WHERE user_seq = '" . $userSeq . "' ";
        $goldSql = "         SELECT log_date, 'gold' AS money_type, safe_type, amount, before_gold AS before_money, after_gold AS after_money ".
                   "         , before_safe_gold AS before_safe_money, after_safe_gold AS after_safe_money".
                   "         FROM logdb.safe_gold_log ".
                   "         WHERE user_seq = '" . $userSeq . "' ";
        $innerSql = "";
        if ($logType == "all") {
            $innerSql = $chipSql . "         UNION ". $goldSql;
        } else if ($logType == "chip") {
            $innerSql = $chipSql;
        } else if ($logType == "gold") {
            $innerSql = $goldSql;
        }

        if ($innerSql !== "") {
            $sql = " SELECT V.log_date, V.money_type, V.safe_type, V.amount, V.before_money, V.before_safe_money, V.after_money, V.after_safe_money " .
                "     FROM  ( " .
                $innerSql.
                "         ) V " .
                " WHERE V.log_date BETWEEN ? AND ? " .
                " ORDER BY V.log_date DESC;";

            return DB::connection('mysql')->select($sql, array($startDate, $endDate));

        } else {
            return [];
        }
    }

}

