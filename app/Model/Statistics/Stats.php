<?php


namespace App\Model\Statistics;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class Stats extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'stats.daily_billing';
    protected $primaryKey = 'billing_seq';

    public static function gatherBilling($targetDate)
    {
        $sql = "CALL stats.Statistics_Gather_Daily_Billing(?)";
        return DB::connection('mysql')->statement($sql, array($targetDate));
    }

    // todo :: change to query from procedure
    public static function gatherBuyItem($targetDate)
    {
        $sql = "CALL stats.Statistics_Gather_Daily_Buy_Item(?)";
        return DB::connection('mysql')->statement($sql, array($targetDate));
    }

    // todo :: change to query from procedure
    public static function gatherGoods()
    {
        $sql = "CALL stats.Statistics_Gather_Daily_Goods()";
        return DB::connection('mysql')->statement($sql, array());
    }

    public static function getDailyBillingLog($startDate, $endDate)
    {
        $sql = "SELECT * FROM stats.daily_billing WHERE log_date BETWEEN ? AND ? ORDER BY log_date DESC";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getDailyBuyItemLog($startDate, $endDate)
    {
        $sql = "SELECT * FROM stats.daily_buy_item WHERE log_date BETWEEN ? AND ? ORDER BY log_date DESC";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getDailyMoneyLog($startDate, $endDate)
    {
        $sql = "SELECT * FROM stats.daily_money WHERE date BETWEEN ? AND ? ORDER BY date DESC";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getDailyStatsTotalLog($startDate, $endDate)
    {
        $sql = "SELECT `date`, gametype, subtype, sum_inc, sum_dec, income FROM stats.daily_stats_card WHERE gametype not in (4, 5, 6) AND DATE BETWEEN ? AND ?
                UNION
                SELECT `date`, gametype, 2 AS subtype, sum_user AS sum_inc, sum_dealer AS sum_dec, income FROM stats.daily_stats_casino WHERE DATE BETWEEN ? AND ?
                ORDER BY date DESC, gametype ASC, subtype ASC";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate, $startDate, $endDate));
    }

    public static function getDailyStatsCardLog($startDate, $endDate)
    {
        $sql = "SELECT * FROM stats.daily_stats_card WHERE date BETWEEN ? AND ? ";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getDailyStatsCardLogByType($startDate, $endDate, $gameType, $subType)
    {
        $sql = "SELECT * FROM stats.daily_stats_card WHERE gametype = ? AND subtype = ? date BETWEEN ? AND ? ";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate, $gameType, $subType));
    }

    public static function getDailyStatsCasinoLog($startDate, $endDate)
    {
        $sql = "SELECT * FROM stats.daily_stats_casino WHERE date BETWEEN ? AND ? ";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getDailyStatsCasinoLogByType($startDate, $endDate, $gameType)
    {
        $sql = "SELECT * FROM stats.daily_stats_casino WHERE gametype = ? AND date BETWEEN ? AND ? ";
        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }
}
