<?php


namespace App\Model\Log;

use App\BaseModel;
use App\Helpers\Helper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HighlowResultLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.highlow_result_log';
    protected $primaryKey = 'highlow_result_log_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_type', 'channel', 'unique_num', 'user_seq', 'room_id', 'game_result', 'change_money',
        'swing_bonus', 'remain_game_money', 'card_list', 'high_made', 'high_made_list', 'low_made', 'low_made_list',
        'pot_money', 'dealer_charge', 'jackpot_charge', 'pay_dealer_charge', 'made_bonus', 'jackpot_bonus',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'reg_date' => 'datetime',
    ];

    public static function getGameLog($gameType, $userSeq)
    {
        $chipChannel = implode( ',', Helper::getChannels($gameType, 'chip'));
        $goldChannel = implode( ',', Helper::getChannels($gameType, 'gold'));

        $sql = "SELECT '".$gameType."' AS game_type, '".$userSeq."' AS user_seq,
                    (CW.chipWinCount + CL.chipLoseCount) AS chipPlayCount, CW.chipWinCount, CW.chipWinMoney, CL.chipLoseCount, CL.chipLoseMoney,
                    (GW.goldWinCount + GL.goldLoseCount) AS goldPlayCount, GW.goldWinCount, GW.goldWinMoney, GL.goldLoseCount, GL.goldLoseMoney,
                    CA.allInCount as chipAllinCount, GA.allInCount as goldAllinCount
                FROM
                    (
                        SELECT L.chipWinCount as chipWinCount, L.chipWinMoney as chipWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipWinCount, IFNULL(SUM(change_money), 0) AS chipWinMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND pot_money > 0
                            AND game_result IN (1, 4)
                        ) L
                    ) CW,
                    (
                        SELECT L.chipLoseCount as chipLoseCount, L.chipLoseMoney as chipLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipLoseCount, IFNULL(SUM(change_money), 0) AS chipLoseMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND pot_money > 0
                            AND game_result NOT IN (1, 4)
                        ) L
                    ) CL,
                    (
                        SELECT L.goldWinCount as goldWinCount, L.goldWinMoney as goldWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldWinCount, IFNULL(SUM(change_money), 0) AS goldWinMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND pot_money > 0
                            AND game_result IN (1, 4)
                        ) L
                    ) GW,
                    (
                        SELECT L.goldLoseCount as goldLoseCount,  L.goldLoseMoney as goldLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldLoseCount, IFNULL(SUM(change_money), 0) AS goldLoseMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND pot_money > 0
                            AND game_result NOT IN (1, 4)
                        ) L
                    ) GL,
                    (
                        SELECT L.allInCount as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND pot_money > 0
                            AND change_money < 0 AND remain_game_money = 0
                        ) L
                    ) CA,
                    (
                        SELECT L.allInCount as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.highlow_result_log
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

    public static function getDailyLog($gameType, $userSeq, $today = null)
    {
        $chipChannel = implode( ',', Helper::getChannels($gameType, 'chip'));
        $goldChannel = implode( ',', Helper::getChannels($gameType, 'gold'));
        $targetDate = is_null($today)? Carbon::today() : $today;

        $sql = "SELECT '".$gameType."' AS game_type, '".$userSeq."' AS user_seq,
                    (CW.chipWinCount + CL.chipLoseCount) AS chipPlayCount, CW.chipWinCount, CW.chipWinMoney, CL.chipLoseCount, CL.chipLoseMoney,
                    (GW.goldWinCount + GL.goldLoseCount) AS goldPlayCount, GW.goldWinCount, GW.goldWinMoney, GL.goldLoseCount, GL.goldLoseMoney,
                    CA.allInCount as chipAllinCount, GA.allInCount as goldAllinCount
                FROM
                    (
                        SELECT L.chipWinCount as chipWinCount, L.chipWinMoney as chipWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipWinCount, IFNULL(SUM(change_money), 0) AS chipWinMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                                AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                                AND pot_money > 0
                                AND game_result IN (1, 4)
                                AND reg_date >= '". $targetDate ."'
                        ) L

                    ) CW,
                    (
                        SELECT L.chipLoseCount as chipLoseCount, L.chipLoseMoney as chipLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS chipLoseCount, IFNULL(SUM(change_money), 0) AS chipLoseMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND pot_money > 0
                            AND game_result NOT IN (1, 4)
                            AND reg_date >= '". $targetDate ."'
                        ) L
                    ) CL,
                    (
                        SELECT L.goldWinCount as goldWinCount, L.goldWinMoney as goldWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldWinCount, IFNULL(SUM(change_money), 0) AS goldWinMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND pot_money > 0
                            AND game_result IN (1, 4)
                            AND reg_date >= '". $targetDate ."'
                        ) L
                    ) GW,
                    (
                        SELECT L.goldLoseCount as goldLoseCount, L.goldLoseMoney as goldLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldLoseCount, IFNULL(SUM(change_money), 0) AS goldLoseMoney FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $goldChannel . "')
                            AND pot_money > 0
                            AND game_result NOT IN (1, 4)
                            AND reg_date >= '". $targetDate ."'
                        ) L
                    ) GL,
                    (
                        SELECT L.allInCount as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.highlow_result_log
                            WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."'
                            AND FIND_IN_SET(`channel`, '" . $chipChannel . "')
                            AND change_money < 0 AND remain_game_money = 0 AND pot_money > 0
                            AND reg_date >= '". $targetDate ."'
                        ) L
                    ) CA,
                    (
                        SELECT L.allInCount as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.highlow_result_log
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
        $sql = "Call logdb.ILog_CMS_FindHighLowDetailLog_Prev(?, ?, ?, ?, ?)";
        $detailLogPrev = DB::connection('mysql')->select($sql, array($userSeq, $gameType, implode( ',', $channels), $startDate, $endDate));

        $sql = "Call logdb.ILog_CMS_FindHighLowDetailLog(?, ?, ?, ?, ?)";
        $detailLogNew = DB::connection('mysql')->select($sql, array($userSeq, $gameType, implode( ',', $channels), $startDate, $endDate));

        $detailLog = array_merge($detailLogPrev, $detailLogNew);

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
            $detailLog[$index]->nickname = $nicknames[$log->user_seq];
        }

        return $detailLog;
    }
    */

    public static function getResultlLog($userSeq, $gameType, $channels, $startDate, $endDate)
    {
        //$startDateNum = str_replace("-", "", substr($startDate, 0, 10));
        //$endDateNum = str_replace("-", "", substr($endDate, 0, 10));
        $sql = "SELECT *, reg_date as log_date FROM logdb.highlow_result_log
                WHERE game_type = '".$gameType."' AND user_seq = '".$userSeq."' AND FIND_IN_SET(channel, '".implode(",", $channels)."')
                    AND pot_money > 0 AND reg_date BETWEEN '".$startDate."' AND '".$endDate."'";
        // echo $sql;
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

}
