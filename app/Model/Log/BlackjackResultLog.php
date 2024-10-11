<?php


namespace App\Model\Log;

use App\BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BlackjackResultLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.blackjack_result_log';
    protected $primaryKey = 'log_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel', 'unique_num', 'user_seq',
        'total_bet', 'pair_bet', 'insurance_bet',
        'change_money', 'before_money', 'after_money', 'rakeback', 'total_rakeback',
        'game0', 'game1', 'game2', 'game3', 'log_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'log_date' => 'datetime',
    ];

    public static function getGameLog($gameType, $userSeq)
    {
        $sql = "SELECT '".$gameType."' AS game_type, '".$userSeq."' AS user_seq,
                    0 AS chipPlayCount, 0 AS chipWinCount,  0 AS chipWinMoney, 0 AS chipLoseCount, 0 AS chipLoseMoney,
                    (GW.goldWinCount + GL.goldLoseCount) AS goldPlayCount, GW.goldWinCount, GW.goldWinMoney, GL.goldLoseCount, GL.goldLoseMoney,
                    0 as chipAllinCount, A.allInCount as goldAllinCount
                FROM
                    (
                        SELECT L.goldWinCount as goldWinCount, L.goldWinMoney as goldWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldWinCount, IFNULL(SUM(change_money), 0) AS goldWinMoney FROM logdb.blackjack_result_log
                            WHERE user_seq = '".$userSeq."'
                                AND change_money > 0
                        ) L
                     ) GW,
                    (
                        SELECT L.goldLoseCount as goldLoseCount, L.goldLoseMoney as goldLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldLoseCount, IFNULL(SUM(change_money), 0) AS goldLoseMoney FROM logdb.blackjack_result_log
                            WHERE user_seq = '".$userSeq."'
                                AND change_money <= 0
                        ) L
                    ) GL,
                    (
                        SELECT L.allInCount as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.blackjack_result_log
                            WHERE user_seq = '".$userSeq."' AND before_money > 0 AND after_money = 0
                        ) L
                    ) A
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
        $targetDate = is_null($today)? Carbon::today() : $today;
        $sql = "SELECT '".$gameType."' AS game_type, '".$userSeq."' AS user_seq,
                    0 AS chipPlayCount, 0 AS chipWinCount,  0 AS chipWinMoney, 0 AS chipLoseCount, 0 AS chipLoseMoney,
                    (GW.goldWinCount + GL.goldLoseCount) AS goldPlayCount, GW.goldWinCount, GW.goldWinMoney, GL.goldLoseCount, GL.goldLoseMoney,
                    0 as chipAllinCount, A.allInCount as goldAllinCount
                FROM
                    (
                        SELECT L.goldWinCount as goldWinCount, L.goldWinMoney as goldWinMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldWinCount, IFNULL(SUM(change_money), 0) AS goldWinMoney FROM logdb.blackjack_result_log
                            WHERE user_seq = '".$userSeq."' AND change_money > 0 AND log_date >= '". $targetDate ."'
                        ) L
                    ) GW,
                    (
                        SELECT L.goldLoseCount as goldLoseCount, L.goldLoseMoney as goldLoseMoney
                        FROM
                        (
                            SELECT COUNT(*) AS goldLoseCount, IFNULL(SUM(change_money), 0) AS goldLoseMoney FROM logdb.blackjack_result_log
                            WHERE user_seq = '".$userSeq."' AND change_money <= 0 AND log_date >= '". $targetDate ."'
                        ) L
                    ) GL,
                    (
                        SELECT L.allInCount as allInCount
                        FROM
                        (
                            SELECT COUNT(*) AS allInCount FROM logdb.blackjack_result_log
                            WHERE user_seq = '".$userSeq."' AND before_money > 0 AND after_money = 0 AND log_date >= '". $targetDate ."'
                        ) L
                    ) A
                ;";
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
        $sql = "Call logdb.ILog_CMS_FindBlackjackDetailLog_Prev(?, ?, ?, ?, ?)";
        $detailLogPrev = DB::connection('mysql')->select($sql, array($userSeq, $gameType, implode( ',', $channels), $startDate, $endDate));

        $sql = "Call logdb.ILog_CMS_FindBlackjackDetailLog(?, ?, ?, ?, ?)";
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
            $detailLog[$index]->nickname = isset($nicknames[$log->user_seq])? $nicknames[$log->user_seq] : '탈퇴회원';
        }

        return $detailLog;
    }
    */

    public static function getResultlLog($userSeq, $gameType, $channels, $startDate, $endDate)
    {
        //$startDateNum = str_replace("-", "", substr($startDate, 0, 10));
        //$endDateNum = str_replace("-", "", substr($endDate, 0, 10));
        $sql = "SELECT B.*, 2 as game_type, D.game as dealer_game FROM logdb.blackjack_result_log B" .
                " LEFT JOIN logdb.blackjack_dealer_log D ON B.unique_num = D.unique_num " .
                " WHERE B.user_seq = '".$userSeq."' AND FIND_IN_SET(B.channel, '".implode(",", $channels)."') " .
                "    AND B.log_date BETWEEN '".$startDate."' AND '".$endDate."'";
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
