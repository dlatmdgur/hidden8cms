<?php


namespace App\Model\Account;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class Monitor extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.monitor';
    protected $primaryKey = 'user_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'group',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public static function getGroup()
    {
        $sql = "SELECT DISTINCT `group` FROM accountdb.monitor";
        return collect(DB::connection('mysql')->select($sql, array()));
    }

    public static function groupLoginLog($group, $startDate, $endDate, $orderBy="", $sort="")
    {
        $orderBy = ($orderBy == "")? 'log_date' : $orderBy;
        $sort = ($sort == "")? 'DESC' : $sort;

        $sql = "SELECT L.user_seq, L.ip, L.log_date, IFNULL(L.gold, 0) AS gold, IFNULL(L.safe_gold, 0) AS safe_gold, A.nickname, A.google_email, A.platform_id, M.group
                FROM logdb.login_log L
                JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                JOIN accountdb.monitor M ON M.user_seq = A.user_seq
                WHERE L.log_date BETWEEN '". $startDate ."' AND '". $endDate ."'
                    AND M.group = '".$group."'
                ORDER BY L.". $orderBy ." ". $sort;

        return collect(DB::connection('mysql')->select($sql, array()));
    }

    public static function groupHoldemLog($group, $startDate, $endDate)
    {
        $sql = "SELECT L.user_seq, SUM(L.change_money) AS change_money, COUNT(*) AS game_count, A.nickname, A.google_email, A.platform_id
                FROM logdb.game_result_log L
                JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                WHERE L.game_type= 7
                    AND L.pot_money > 0
                    AND L.user_seq IN ( SELECT user_seq FROM accountdb.monitor WHERE `group` = '". $group ."' )
                AND L.reg_date BETWEEN '". $startDate ."' AND '". $endDate ."'
                GROUP BY L.user_seq";
        return collect(DB::connection('mysql')->select($sql, array()));
    }

    public static function groupBadugiLog($group, $startDate, $endDate)
    {
        $sql = "SELECT L.user_seq, SUM(L.change_money) AS change_money, COUNT(*) AS game_count, A.nickname, A.google_email, A.platform_id
                FROM logdb.game_result_log L
                JOIN accountdb.account_info A ON L.user_seq = A.user_seq
                WHERE L.game_type= 4
                    AND L.channel in (10020,10021,10022,10023,10024,10025,10026,10027)
                    AND L.user_seq IN (
                    SELECT user_seq FROM accountdb.monitor WHERE `group` = '". $group ."'
                    )
                AND L.reg_date BETWEEN '". $startDate ."' AND '". $endDate ."'
                GROUP BY L.user_seq";
        return collect(DB::connection('mysql')->select($sql, array()));
    }

}
