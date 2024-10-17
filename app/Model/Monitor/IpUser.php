<?php

namespace App\Model\Monitor;

use Illuminate\Support\Facades\DB;

class ipUser
{
    protected $connection = 'mysql';

    public static function getIps($startDate, $endDate)
    {
        $sql = " SELECT L.ip, L.user_count ".
                " FROM ( ".
                "	SELECT ip, COUNT(DISTINCT user_seq) AS user_count ".
                "	FROM logdb.login_log ".
                "	WHERE log_date BETWEEN ? AND ? ".
                "          AND ip != '180.65.14.208' ".
                "          AND ip != '125.137.21.201' ".
                "          AND ip != '212.181.172.5'  ".
                "          AND ip != '124.63.145.89' ".
                "          AND ip != '1.251.176.75' ".
                "          AND ip != '118.41.46.201' ".
                "          AND ip != '59.6.114.135' ".
                "          AND ip != '59.6.114.173' ".
                "          AND ip != '118.32.144.23' ".
                "          AND ip NOT LIKE '203.226.%' ".
                "          AND ip NOT LIKE '211.234.%' ".
                "          AND ip NOT LIKE '223.32.%' ".
                "          AND ip NOT LIKE '223.33.%' ".
                "          AND ip NOT LIKE '223.34.%' ".
                "          AND ip NOT LIKE '223.35.%' ".
                "          AND ip NOT LIKE '223.36.%' ".
                "          AND ip NOT LIKE '223.37.%' ".
                "          AND ip NOT LIKE '223.38.%' ".
                "          AND ip NOT LIKE '223.39.%' ".
                "          AND ip NOT LIKE '223.40.%' ".
                "          AND ip NOT LIKE '223.41.%' ".
                "          AND ip NOT LIKE '223.42.%' ".
                "          AND ip NOT LIKE '223.43.%' ".
                "          AND ip NOT LIKE '223.44.%' ".
                "          AND ip NOT LIKE '223.45.%' ".
                "          AND ip NOT LIKE '223.46.%' ".
                "          AND ip NOT LIKE '223.47.%' ".
                "          AND ip NOT LIKE '223.48.%' ".
                "          AND ip NOT LIKE '223.49.%' ".
                "          AND ip NOT LIKE '223.50.%' ".
                "          AND ip NOT LIKE '223.51.%' ".
                "          AND ip NOT LIKE '223.52.%' ".
                "          AND ip NOT LIKE '223.53.%' ".
                "          AND ip NOT LIKE '223.54.%' ".
                "          AND ip NOT LIKE '223.55.%' ".
                "          AND ip NOT LIKE '223.56.%' ".
                "          AND ip NOT LIKE '223.57.%' ".
                "          AND ip NOT LIKE '223.58.%' ".
                "          AND ip NOT LIKE '223.59.%' ".
                "          AND ip NOT LIKE '223.60.%' ".
                "          AND ip NOT LIKE '223.61.%' ".
                "          AND ip NOT LIKE '223.62.%' ".
                "          AND ip NOT LIKE '223.63.%' ".
                "          AND ip NOT LIKE '115.161.%' ".
                "          AND ip NOT LIKE '122.202.%' ".
                "          AND ip NOT LIKE '122.32.%' ".
                "          AND ip NOT LIKE '121.190.%' ".
                "          AND ip NOT LIKE '175.202.%' ".
                "          AND ip NOT LIKE '39.7.%' ".
                "          AND ip NOT LIKE '110.70.%' ".
                "          AND ip NOT LIKE '175.223.%' ".
                "          AND ip NOT LIKE '211.246.%' ".
                "          AND ip NOT LIKE '118.235.8.%' ".
                "          AND ip NOT LIKE '175.252.%' ".
                "          AND ip NOT LIKE '210.125.%' ".
                "          AND ip NOT LIKE '61.43.%' ".
                "          AND ip NOT LIKE '211.234.%' ".
                "          AND ip NOT LIKE '117.111.%' ".
                "          AND ip NOT LIKE '211.36.%' ".
                "          AND ip NOT LIKE '106.102.%' ".
                "          AND ip NOT LIKE '106.101.%' ".
                "          AND ip NOT LIKE '114.200.%' ".
                "          AND ip NOT LIKE '125.188.%' ".
                "          AND ip NOT LIKE '127.0.0.1' ".
                "          AND ip NOT LIKE '172.16.%' ".
                "          AND ip NOT LIKE '172.17.%' ".
                "          AND ip NOT LIKE '172.18.%' ".
                "          AND ip NOT LIKE '172.19.%' ".
                "          AND ip NOT LIKE '172.20.%' ".
                "          AND ip NOT LIKE '172.21.%' ".
                "          AND ip NOT LIKE '172.22.%' ".
                "          AND ip NOT LIKE '172.23.%' ".
                "          AND ip NOT LIKE '172.24.%' ".
                "          AND ip NOT LIKE '172.25.%' ".
                "          AND ip NOT LIKE '172.26.%' ".
                "          AND ip NOT LIKE '172.27.%' ".
                "          AND ip NOT LIKE '172.28.%' ".
                "          AND ip NOT LIKE '172.29.%' ".
                "          AND ip NOT LIKE '172.30.%' ".
                "          AND ip NOT LIKE '172.31.%' ".
                "          AND ip NOT LIKE '192.168.%' ".
                "          AND ip NOT LIKE '10.%' ".
                "	GROUP BY ip ".
                " ) L ".
                " WHERE L.user_count >= 1 ".
                " ORDER BY L.user_count DESC;";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getIpUsers($ip, $startDate, $endDate)
    {
        $sql = " SELECT L.user_seq, A.nickname, A.account, A.user_state, L.ip, L.log_date, ".
                "       U.gem, U.gem_event, U.chip, U.gold, U.safe_chip, U.safe_gold ".
                " FROM logdb.login_log L ".
                " LEFT JOIN gamedb.user_info U ON L.user_seq = U.user_seq ".
                " LEFT JOIN accountdb.account_info A ON L.user_seq = A.user_seq ".
                " WHERE L.ip = ? AND L.log_date BETWEEN ? AND ? ".
                "       AND log_seq IN ( SELECT MAX(log_seq) FROM logdb.login_log GROUP BY user_seq ) ".
                " ORDER BY L.log_date, L.user_seq ";

        return DB::connection('mysql')->select($sql, array($ip, $startDate, $endDate));
    }

}

