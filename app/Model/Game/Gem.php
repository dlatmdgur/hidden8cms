<?php

namespace App\Model\Game;

use App\Helpers\Helper;
use App\Model\Tables\ProductName;
use Illuminate\Support\Facades\DB;

class Gem
{
    protected $connection = 'mysql';

    public static function getGemLogs($userSeq, $logType, $startDate, $endDate)
    {
        $buySql = " SELECT BI.update_date, P.itemCount AS change_gem, 0 AS change_event_gem, '-1' AS after_gem, ".
                  "         '획득' as log_type, BI.market_type, CONCAT(P.memo, ' 구매') AS reason".
                  " FROM gamedb.billing_info BI ".
                  " JOIN cms_game.tbl_products P ON BI.package_seq = P.id ".
                  " WHERE BI.status IN (3, 99) AND P.itemId = '2017' ".
                  "     AND BI.user_seq = '".$userSeq."' ".
                  "     AND BI.update_date BETWEEN ? AND ? ".
                  " ORDER BY BI.update_date DESC;";

        $presentSql = " SELECT PL.update_date, IF(PL.item_id = 2017, PL.item_ea, 0) AS change_gem, IF(PL.item_id = 2025, PL.item_ea, 0) AS change_event_gem, ".
                      "       -1 AS after_gem, '획득' AS log_type, 0 AS market_type, PL.sender_seq AS reason, PL.item_id AS product_id ".
                      " FROM gamedb.present PL ".
                      " WHERE PL.is_read = 1 ".
                      "       AND PL.user_seq =  '".$userSeq."' ".
                      "       AND item_id IN (2017, 2025) ".
                      "       AND PL.update_date BETWEEN ? AND ? ".
                      " ORDER BY PL.update_date DESC;";

//        $useSql = " SELECT BL.reg_date as update_date, ".
//                    "       IF( (BL.gem_type IN (1, 3)), (-1 * BL.price), 0) AS change_gem, ".
//                    "       IF( (BL.gem_type = 2), (-1 * BL.price), 0) AS change_event_gem, ".
//                    "       0 as after_gem, '소비' as log_type, '-1' as market_type,".
//                    "       '' AS reason, BL.buy_count, BL.product_id ".
//                    " FROM logdb.buy_log BL ".
//                    " WHERE user_seq = '".$userSeq."'".
//                    "     AND BL.reg_date BETWEEN ? AND ? ".
//                    " ORDER BY BL.reg_date DESC;";
        $useSql = " SELECT BL.reg_date as update_date, ".
                    "       (after_gem - before_gem) AS change_gem, ".
                    "       (after_event_gem - before_event_gem) AS change_event_gem, ".
                    "       (after_gem + after_event_gem) as after_gem, '소비' as log_type, '-1' as market_type,".
                    "       '' AS reason, BL.buy_count, BL.product_id ".
                    " FROM logdb.buy_log BL ".
                    " WHERE user_seq = '".$userSeq."'".
                    "     AND BL.reg_date BETWEEN ? AND ? ".
                    " ORDER BY BL.reg_date DESC;";

        $adminGiveSql = " SELECT AL.update_date, IF(AL.target = 'gem', AL.changeAmount, 0) as change_gem, ".
                        "       IF(AL.target = 'event_gem', AL.changeAmount, 0) AS change_event_gem, ".
                        "       (AL.after_gem + AL.after_event_gem) as after_gem, ".
                        "       '관리자 지급' as log_type, '-1' as market_type, ".
                        "       CONCAT(AL.reason, ' ', AL.extra) as reason, 0 as buy_count, ".
                        "       IF(AL.target = 'gem', 2017, 2025) as product_id ".
                        " FROM ( ".
                        "       SELECT updated_at as update_date, ".
                        "           JSON_UNQUOTE(JSON_EXTRACT(params, '$.actionType')) AS actionType, ".
                        "           JSON_UNQUOTE(JSON_EXTRACT(params, '$.target')) AS target, ".
                        "           JSON_UNQUOTE(JSON_EXTRACT(params, '$.changeAmount')) AS changeAmount, ".
                        "           JSON_UNQUOTE(JSON_EXTRACT(after_value, '$.gem')) AS after_gem, ".
                        "           JSON_UNQUOTE(JSON_EXTRACT(after_value, '$.event_gem')) AS after_event_gem, ".
                        "           reason, extra ".
                        "       FROM cms_game.admin_logs where `action` = 'editGem' AND user_seq = '".$userSeq."'".
                        "           AND updated_at BETWEEN ? AND ? ".
                        " ) AL ".
                        " WHERE AL.actionType = 'give' ".
                        " ORDER BY AL.update_date DESC";

        $adminRevokeSql = " SELECT AL.update_date, IF(AL.target = 'gem', AL.changeAmount, 0) as change_gem, ".
                            "       IF(AL.target = 'event_gem', AL.changeAmount, 0) AS change_event_gem, ".
                            "       (AL.after_gem + AL.after_event_gem) as after_gem, ".
                            "       '관리자 회수' as log_type, '-1' as market_type, ".
                            "       CONCAT(AL.reason, ' ', AL.extra) as reason, 0 as buy_count, ".
                            "       IF(AL.target = 'gem', 2017, 2025) as product_id ".
                            " FROM ( ".
                            "       SELECT updated_at as update_date, ".
                            "           JSON_UNQUOTE(JSON_EXTRACT(params, '$.actionType')) AS actionType, ".
                            "           JSON_UNQUOTE(JSON_EXTRACT(params, '$.target')) AS target, ".
                            "           JSON_UNQUOTE(JSON_EXTRACT(params, '$.changeAmount')) AS changeAmount, ".
                            "           JSON_UNQUOTE(JSON_EXTRACT(after_value, '$.gem')) AS after_gem, ".
                            "           JSON_UNQUOTE(JSON_EXTRACT(after_value, '$.event_gem')) AS after_event_gem, ".
                            "           reason, extra ".
                            "       FROM cms_game.admin_logs where `action` = 'editGem' AND user_seq = '".$userSeq."'".
                            "           AND updated_at BETWEEN ? AND ? ".
                            " ) AL ".
                            " WHERE AL.actionType = 'revoke' ".
                            " ORDER BY AL.update_date DESC";

        $gemLogs = [];
        if ($logType == "all") {
            $buyLog = DB::connection('mysql')->select($buySql, array($startDate, $endDate));
            $presentLog = DB::connection('mysql')->select($presentSql, array($startDate, $endDate));
            if (count($presentLog) > 0) {
                foreach ($presentLog as $key => $value) {
                    $presentLog[$key]->reason = Helper::reasonByKey($value->reason);
                }
            }
            $adminGiveLog = DB::connection('mysql')->select($adminGiveSql, array($startDate, $endDate));

            $useLog = DB::connection('mysql')->select($useSql, array($startDate, $endDate));
            $adminRevokeLog = DB::connection('mysql')->select($adminRevokeSql, array($startDate, $endDate));

            $gemLogs = array_merge($buyLog, $presentLog, $adminGiveLog, $useLog, $adminRevokeLog);
        } else if ($logType == "buy") {
            $buyLog = DB::connection('mysql')->select($buySql, array($startDate, $endDate));
            $presentLog = DB::connection('mysql')->select($presentSql, array($startDate, $endDate));
            if (count($presentLog) > 0) {
                foreach ($presentLog as $key => $value) {
                    $presentLog[$key]->reason = Helper::reasonByKey($value->reason);
                }
            }

            $gemLogs = array_merge($buyLog, $presentLog);
        } else if ($logType == "use") {
            $useLog = DB::connection('mysql')->select($useSql, array($startDate, $endDate));

            $gemLogs = $useLog;
        } else if ($logType == "admin_give") {
            $adminGiveLog = DB::connection('mysql')->select($adminGiveSql, array($startDate, $endDate));

            $gemLogs = $adminGiveLog;
        } else if ($logType == "admin_revoke") {
            $adminRevokeLog = DB::connection('mysql')->select($adminRevokeSql, array($startDate, $endDate));

            $gemLogs = $adminRevokeLog;
        }

        $productNames = ProductName::getProductNames();

        // sort by update_date
        if (count($gemLogs) > 0) {
            foreach ($gemLogs as $key => $value) {
                $sort[$key] = $value->update_date;
                if ($value->log_type == '소비') {
                    $product = isset($productNames[$value->product_id])? $productNames[$value->product_id] : "판매 중지 상품";
                    $gemLogs[$key]->reason = $product . ' 구매' . $value->reason;
                }
            }
            array_multisort($sort, SORT_DESC, $gemLogs);
        }

        return $gemLogs;
    }

}

