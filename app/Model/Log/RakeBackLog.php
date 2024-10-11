<?php

namespace App\Model\Log;

use Illuminate\Support\Facades\DB;

class RakeBackLog
{
    protected $connection = 'mysql';
    protected $table = 'logdb.rakeback_gold_log';
    protected $primaryKey = 'log_seq';

    public static function inLogs($userSeq, $startDate, $endDate)
    {
        $sql = " SELECT RB.user_seq, RB.game_type, SUM(RB.rakeback) rakeback, RB.log_date ".
                "FROM ( ".
                "       SELECT user_seq, 3 AS game_type, DATE_FORMAT(log_date, '%Y-%m-%d') AS log_date, rakeback ".
                "       FROM logdb.baccarat_result_log  ".
                "       WHERE user_seq = :user_seq  ".
                "           AND log_date BETWEEN :sdate AND :edate ".
                "       UNION ".
                "       SELECT user_seq, 2 AS game_type, DATE_FORMAT(log_date, '%Y-%m-%d') AS log_date, rakeback ".
                "       FROM logdb.blackjack_result_log  ".
                "       WHERE user_seq = :user_seq  ".
                "           AND log_date BETWEEN :sdate AND :edate ".
                "       UNION ".
                // "       SELECT user_seq, game_type, DATE_FORMAT(reg_date, '%Y-%m-%d') AS log_date, rakeback ".
                // "       FROM logdb.game_result_log_20210323 ".
                // "       WHERE user_seq = :user_seq  ".
                // "           AND reg_date BETWEEN :sdate AND :edate ".
                // "       UNION ".
                "       SELECT user_seq, game_type, DATE_FORMAT(reg_date, '%Y-%m-%d') AS log_date, rakeback ".
                "       FROM logdb.game_result_log  ".
                "       WHERE user_seq = :user_seq  ".
                "           AND reg_date BETWEEN :sdate AND :edate ".
                //"       UNION ".
                //"       SELECT user_seq, game_type, DATE_FORMAT(reg_date, '%Y-%m-%d') AS log_date, rakeback ".
                //"       FROM logdb.highlow_result_log  ".
                //"       WHERE user_seq = :user_seq  ".
                //"           AND reg_date BETWEEN :sdate AND :edate ".
                "   )RB ".
                "GROUP BY RB.user_seq, RB.game_type, RB.log_date ".
                "ORDER BY RB.log_date DESC, RB.game_type ASC ";

        return DB::connection('mysql')->select($sql, ['user_seq' => $userSeq, 'sdate' => $startDate, 'edate' => $endDate]);
    }

    public static function outLogs($userSeq, $startDate, $endDate)
    {
        $sql = " SELECT log_seq, amount, user_seq, before_rakeback, before_gold, after_rakeback, after_gold, log_date " .
            "     FROM  logdb.rakeback_gold_log " .
            " WHERE user_seq = ? AND log_date BETWEEN ? AND ? " .
            " ORDER BY log_date DESC;";

        return DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));
    }
}

