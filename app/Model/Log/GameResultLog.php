<?php


namespace App\Model\Log;

use App\BaseModel;
use App\Helpers\Helper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameResultLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.game_result_log';
    protected $primaryKey = 'game_result_log_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_type', 'channel', 'unique_num', 'user_seq', 'room_id', 'game_result', 'change_money',
        'remain_game_money', 'made', 'card_list', 'made_list', 'pot_money', 'dealer_charge', 'jackpot_charge',
        'pay_dealer_charge', 'made_bonus', 'jackpot_bonus',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'reg_date' => 'datetime',
    ];

    public static function getLatestLog($gameType, $userSeq)
    {

        $sql = "SELECT GL.*
                FROM
                    (
                        SELECT * FROM logdb.game_result_log WHERE game_type = '". $gameType ."' AND user_seq = ". $userSeq ."
                    ) GL
                ORDER BY GL.reg_date DESC LIMIT 1 ";

        $gameLog = DB::connection('mysql')->select($sql);

        $result = null;
        if ( count($gameLog) == 0 ) {
            $result = null;
        } else {
            $result = $gameLog[0];
        }

        return $result;
    }

    /**
     * Undocumented function
     *
     * @param [type] $gameType
     * @param [type] $userSeq
     * @param array $exceptChan 제외할 채널 []
     * @return void
     */
    public static function getGameLog($gameType, $userSeq, array $exceptChan = [])
    {
        $chipChannel = Helper::getChannels($gameType, 'chip');
        $goldChannel = Helper::getChannels($gameType, 'gold');

        if ($exceptChan) {
            foreach ($exceptChan as $chan)
            {
                $extChipKey = array_search($chan, $chipChannel);
                $extGoldKey = array_search($chan, $goldChannel);

                if ($extChipKey !== false)
                    unset($chipChannel[$extChipKey]);

                if ($extGoldKey !== false)
                    unset($goldChannel[$extGoldKey]);
            }
        }

        $chipChannel = implode(',', $chipChannel);
        $goldChannel = implode(',', $goldChannel);

        $sql = "SELECT '".$gameType."' AS game_type, '".$userSeq."' AS user_seq,
                    (CW.chipWinCount + CL.chipLoseCount) AS chipPlayCount, CW.chipWinCount, CW.chipWinMoney, CL.chipLoseCount, CL.chipLoseMoney,
                    (GW.goldWinCount + GL.goldLoseCount) AS goldPlayCount, GW.goldWinCount, GW.goldWinMoney, GL.goldLoseCount, GL.goldLoseMoney,
                    CA.allInCount as chipAllinCount, GA.allInCount as goldAllinCount
                FROM
                    (
                        SELECT SUM(L.chipWinCount) as chipWinCount, SUM(L.chipWinMoney) as chipWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipWinCount, IFNULL(SUM(change_money), 0) AS chipWinMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND game_result IN (1, 4) AND pot_money > 0
                        ) L

                    ) CW,
                    (
                        SELECT SUM(L.chipLoseCount) as chipLoseCount, SUM(L.chipLoseMoney) as chipLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipLoseCount, IFNULL(SUM(change_money), 0) AS chipLoseMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND game_result NOT IN (1, 4) AND pot_money > 0
                        ) L
                    ) CL,
                    (
                        SELECT SUM(L.goldWinCount) as goldWinCount, SUM(L.goldWinMoney) as goldWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldWinCount, IFNULL(SUM(change_money), 0) AS goldWinMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND game_result IN (1, 4) AND pot_money > 0
                        ) L
                    ) GW,
                    (
                        SELECT SUM(L.goldLoseCount) as goldLoseCount, SUM(L.goldLoseMoney) as goldLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldLoseCount, IFNULL(SUM(change_money), 0) AS goldLoseMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND game_result NOT IN (1, 4) AND pot_money > 0
                        ) L
                    ) GL,
                    (
                        SELECT SUM(L.allInCount) as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND change_money < 0 AND remain_game_money = 0 AND pot_money > 0
                        ) L
                    ) CA,
                    (
                        SELECT SUM(L.allInCount) as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND change_money < 0 AND remain_game_money = 0 AND pot_money > 0
                        ) L
                    ) GA
                    ";

        $gameLog = DB::connection('mysql')->select($sql);

        $sql = "SELECT max_seq_win_count ".
            " FROM gamedb.game_info ".
            " WHERE game_type = ? AND user_seq = ?";
        $seqWinLog = DB::connection('mysql')->select($sql, array($gameType, $userSeq));

        foreach ($gameLog as $index => $log) {
            $gameLog[$index]->max_seq_win_count = (isset($seqWinLog[0]))? $seqWinLog[0]->max_seq_win_count : 0;
        }

        return $gameLog;
    }

    public static function getDailyLog($gameType, $userSeq, $today = null, array $exceptChan = [])
    {
        $chipChannel = Helper::getChannels($gameType, 'chip');
        $goldChannel = Helper::getChannels($gameType, 'gold');

        if ($exceptChan) {

            foreach ($exceptChan as $chan)
            {
                $extChipKey = array_search($chan, $chipChannel);
                $extGoldKey = array_search($chan, $goldChannel);

                if ($extChipKey !== false)
                    unset($chipChannel[$extChipKey]);

                if ($extGoldKey !== false)
                    unset($goldChannel[$extGoldKey]);
            }
        }

        $chipChannel = implode(',', $chipChannel);
        $goldChannel = implode(',', $goldChannel);

        $targetDate = is_null($today)? Carbon::today() : $today;

        $sql = "SELECT '".$gameType."' AS game_type, '".$userSeq."' AS user_seq,
                    (CW.chipWinCount + CL.chipLoseCount) AS chipPlayCount, CW.chipWinCount, CW.chipWinMoney, CL.chipLoseCount, CL.chipLoseMoney,
                    (GW.goldWinCount + GL.goldLoseCount) AS goldPlayCount, GW.goldWinCount, GW.goldWinMoney, GL.goldLoseCount, GL.goldLoseMoney,
                    CA.allInCount as chipAllinCount, GA.allInCount as goldAllinCount
                FROM
                    (
                        SELECT SUM(L.chipWinCount) as chipWinCount, SUM(L.chipWinMoney) as chipWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipWinCount, IFNULL(SUM(change_money), 0) AS chipWinMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                                AND game_result IN (1, 4)
                                AND pot_money > 0
                                AND reg_date >= '". $targetDate ."'
                        ) L
                    ) CW,
                    (
                        SELECT SUM(L.chipLoseCount) as chipLoseCount, SUM(L.chipLoseMoney) as chipLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipLoseCount, IFNULL(SUM(change_money), 0) AS chipLoseMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                                AND game_result NOT IN (1, 4)
                                AND pot_money > 0
                                AND reg_date >= '". $targetDate ."'
                        ) L
                    ) CL,
                    (
                        SELECT SUM(L.goldWinCount) as goldWinCount, SUM(L.goldWinMoney) as goldWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldWinCount, IFNULL(SUM(change_money), 0) AS goldWinMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                                AND game_result IN (1, 4)
                                AND pot_money > 0
                                AND reg_date >= '". $targetDate ."'
                        ) L
                    ) GW,
                    (
                        SELECT SUM(L.goldLoseCount) as goldLoseCount, SUM(L.goldLoseMoney) as goldLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldLoseCount, IFNULL(SUM(change_money), 0) AS goldLoseMoney FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                                AND game_result NOT IN (1, 4)
                                AND pot_money > 0
                                AND reg_date >= '". $targetDate ."'
                        ) L
                    ) GL,
                    (
                        SELECT SUM(L.allInCount) as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND change_money < 0 AND remain_game_money = 0 AND pot_money > 0
                            AND reg_date >= '". $targetDate ."'
                        ) L
                    ) CA,
                    (
                        SELECT SUM(L.allInCount) as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.game_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                                AND change_money < 0 AND remain_game_money = 0 AND pot_money > 0
                                AND reg_date >= '". $targetDate ."'
                        ) L
                    ) GA";

        $gameLog = DB::connection('mysql')->select($sql);

        $sql = "SELECT seq_win_count ".
            " FROM gamedb.game_info ".
            " WHERE game_type = ? AND user_seq = ?";
        $seqWinLog = DB::connection('mysql')->select($sql, array($gameType, $userSeq));

        foreach ($gameLog as $index => $log) {
            $gameLog[$index]->seq_win_count = (isset($seqWinLog[0]))? $seqWinLog[0]->seq_win_count : 0;
        }

        return $gameLog;
    }

    /*
    public static function getDetailLog($userSeq, $gameType, $channels, $startDate, $endDate)
    {
        // $sql = "Call logdb.ILog_CMS_FindGameDetailLog_Prev(?, ?, ?, ?, ?)";
        // $detailLogPrev = DB::connection('mysql')->select($sql, array($userSeq, $gameType, implode( ',', $channels), $startDate, $endDate));

        // $sql = "Call logdb.ILog_CMS_FindGameDetailLog(?, ?, ?, ?, ?)";
        // $detailLogNew = DB::connection('mysql')->select($sql, array($userSeq, $gameType, implode( ',', $channels), $startDate, $endDate));

        //$detailLog = array_merge($detailLogPrev, $detailLogNew);

        $sql = self::makeGamePacketQuery($userSeq, $gameType, $channels, $startDate, $endDate);

        $detailLog = DB::connection('mysql')->select($sql);

        $userSeqs = [];
        foreach ($detailLog as $log) {
            array_push($userSeqs, $log->user_seq);
        }
        $userSeqs = array_unique($userSeqs);

        $namesSql = "SELECT user_seq, nickname FROM gamedb.user_info ".
            " WHERE FIND_IN_SET(user_seq, '" . implode( ',', $userSeqs) . "')";
        $users = DB::connection('mysql')->select($namesSql);

        $nicknames = [];
        foreach ($users as $user) {
            $nicknames[$user->user_seq] = $user->nickname;
        }

        foreach ($detailLog as $index => $log) {
            $detailLog[$index]->nickname = isset($nicknames[$log->user_seq])? $nicknames[$log->user_seq] : '탈퇴회원';
        }

        return $detailLog;
    }
    */

    /*
    public static function getResultlLog($userSeq, $gameType, $channels, $startDate, $endDate)
    {
        $startDateNum = str_replace("-", "", substr($startDate, 0, 10));
        $endDateNum = str_replace("-", "", substr($endDate, 0, 10));
        $sql = "SELECT *, reg_date as log_date FROM logdb.game_result_log
                WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."' AND FIND_IN_SET(channel, '".implode(",", $channels)."')
                    AND DATE_FORMAT(reg_date, '%Y%m%d') BETWEEN '".$startDateNum."' AND '".$endDateNum."'";
        echo $sql;
        $detailLog = DB::connection('mysql')->select($sql);

        $userSeqs = [];
        foreach ($detailLog as $log) {
            array_push($userSeqs, $log->user_seq);
        }
        $userSeqs = array_unique($userSeqs);

        $namesSql = "SELECT user_seq, nickname FROM gamedb.user_info ".
            " WHERE FIND_IN_SET(user_seq, '" . implode( ',', $userSeqs) . "')";
        echo $namesSql;

        $users = DB::connection('mysql')->select($namesSql);

        $nicknames = [];
        foreach ($users as $user) {
            $nicknames[$user->user_seq] = $user->nickname;
        }

        foreach ($detailLog as $index => $log) {
            $detailLog[$index]->nickname = isset($nicknames[$log->user_seq])? $nicknames[$log->user_seq] : '탈퇴회원';
        }

        return $detailLog;
    }*/

    public static function getResultlLog($userSeq, $gameType, $channels, $startDate, $endDate)
    {
        //$startDateNum = substr($startDate, 0, 10) . " 00:00:00";
        //$endDateNum = substr($endDate, 0, 10) . " 23:59:59";
//        $sql = "SELECT L.*, L.reg_date AS log_date, IFNULL(U.nickname, '탈퇴회원') as nickname
//                FROM logdb.game_result_log L
//                JOIN gamedb.user_info U ON L.user_seq = U.user_seq
//                WHERE L.unique_num IN (
//                    SELECT DISTINCT unique_num FROM logdb.game_result_log
//                    WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."' AND FIND_IN_SET(channel, '".implode(",", $channels)."')
//                        AND reg_date BETWEEN '".$startDateNum."' AND '".$endDateNum."'
//                    )
//                ORDER BY L.unique_num ASC";
        $sql_baduki = "";
        if($gameType == "4") $sql_baduki = " AND L.pot_money > 0";
        $sql = "SELECT R.game_result_log_seq, R.game_type, R.channel, R.unique_num, R.user_seq, R.room_id,
                        R.game_result, R.change_money, R.remain_game_money, R.made, R.card_list, R.made_list,
                        R.pot_money, R.dealer_charge, R.jackpot_charge, R.rakeback, R.total_rakeback, R.pay_dealer_charge,
                        R.made_bonus, R.jackpot_bonus, R.reg_date, R.log_date, R.nickname
                FROM (
                        (
                            SELECT L.*, L.reg_date AS log_date, IFNULL(U.nickname, '탈퇴회원') AS nickname
                            FROM logdb.game_result_log L
                            JOIN gamedb.user_info U ON L.user_seq = U.user_seq
                            WHERE game_type = '".$gameType."'
                                AND L.unique_num IN (
                                SELECT DISTINCT unique_num FROM logdb.game_result_log
                                WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                    AND FIND_IN_SET(channel, '".implode(",", $channels)."')
                                    AND reg_date BETWEEN '".$startDate."' AND '".$endDate."'
                            )".$sql_baduki."
                        )";
        $sql .= "
                ) R
                ORDER BY R.unique_num ASC";

		error_log($sql);
       //echo $sql;
        $detailLog = DB::connection('mysql')->select($sql);

        return $detailLog;
    }

    public static function getResultLogWithFriends($userSeq, $startDate, $endDate)
    {
//        $sql =
//            "SELECT
//                DATE_FORMAT(L.reg_date, '%Y-%m-%d') AS log_date, L.game_type, L.channel, SUM(change_money) AS change_money,
//                    L.user_seq, IFNULL(U.nickname, '탈퇴회원') AS nickname, (U.gold + U.safe_gold) AS user_gold
//                FROM logdb.game_result_log L
//                JOIN gamedb.user_info U ON L.user_seq = U.user_seq
//                WHERE L.unique_num IN (
//                        SELECT DISTINCT unique_num FROM logdb.game_result_log
//                        WHERE user_seq = '".$userSeq."'
//                            AND reg_date BETWEEN '".$startDate."' AND '".$endDate."'
//                    )
//                    AND L.pot_money > 0
//                GROUP BY log_date, L.game_type, L.channel, L.user_seq, nickname
//                ORDER BY log_date DESC, L.game_type ASC, L.channel ASC, change_money DESC ";

        $sql = "SELECT R.*
                FROM (
                    (
                        SELECT
                                DATE_FORMAT(L.reg_date, '%Y-%m-%d') AS log_date, L.game_type, L.channel, SUM(change_money) AS change_money,
                                    L.user_seq, IFNULL(U.nickname, '탈퇴회원') AS nickname, (U.gold + U.safe_gold) AS user_gold
                                FROM logdb.game_result_log L
                                JOIN gamedb.user_info U ON L.user_seq = U.user_seq
                                WHERE L.unique_num IN (
                                        SELECT DISTINCT unique_num FROM logdb.game_result_log
                                        WHERE user_seq = '".$userSeq."' AND reg_date BETWEEN '".$startDate."' AND '".$endDate."'
                                    )
                                    AND L.pot_money > 0
                                GROUP BY log_date, L.game_type, L.channel, L.user_seq, nickname
                    )
                    ) R
                ORDER BY R.log_date DESC, R.game_type ASC, R.channel ASC, R.change_money DESC
        ";

        $friendsLog = DB::connection('mysql')->select($sql);

        return $friendsLog;
    }

//    private static function makeGamePacketQuery($userSeq, $gameType, $channels, $startDate, $endDate)
//    {
//        // extract date
//        $dates = [];
//        $startDateNum = str_replace("-", "", substr($startDate, 0, 10));
//        $endDateNum = str_replace("-", "", substr($endDate, 0, 10));
//
//        for($d = $startDateNum; $d <= $endDateNum; $d++) {
//            $dates[] = (string)$d;
//        }
//
//        $dates[] = "packet_test";
//
//        $tableNames = [];
//        foreach($dates as $date) {
//            // check table exists
//            $existsQuery = "SELECT * FROM information_schema.tables WHERE table_schema = 'logdb' AND table_name = 'packet_data_".$date."' LIMIT 1;";
//            $exists = DB::connection('mysql')->select($existsQuery);
//
//            if (count($exists) > 0) {
//                $tableNames[] = "logdb.packet_data_".$date;
//            }
//        }
//
//        // make union query
//        $selectQuery ="
//            SELECT P.game_type, L.channel, P.user_seq, P.room_id as unique_num, P.packet_category, P.packet_index, P.packet_struct, P.reg_date as log_date
//            FROM {tableName} P
//            LEFT JOIN logdb.game_result_log L ON P.room_id = L.unique_num AND P.user_seq = L.user_seq
//            WHERE P.user_seq > 0 AND P.room_id IN (
//                SELECT unique_num FROM logdb.game_result_log
//                WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."' AND FIND_IN_SET(channel, '".implode(",", $channels)."') AND DATE_FORMAT(reg_date, '%Y%m%d') = '{searchDate}'
//            )";
//        $unionQuery = "";
//        foreach($tableNames as $tableName) {
//            $packetQuery = str_replace("{tableName}",$tableName, $selectQuery);
//            $packetQuery = str_replace("{searchDate}",substr($tableName,18), $packetQuery);
//            if ($unionQuery !== "") {
//                $unionQuery .= " UNION " . $packetQuery;
//            } else {
//                $unionQuery = $packetQuery;
//            }
//        }
//
//        return $unionQuery;
//    }
}
