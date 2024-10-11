<?php


namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class BillingInfo extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'gamedb.billing_info';
    protected $primaryKey = 'billing_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'market_type', 'package_seq', 'product_id', 'price', 'hashcode',
        'update_date', 'reg_date', 'status', 'purchase_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public static function getBillingLog($userSeq, $startDate, $endDate)
    {
        $sql = "SELECT B.billing_seq, B.user_seq, U.nickname, A.account, B.market_type, B.package_seq,
                    P.memo AS product_name, P.itemCount AS item_count, IFNULL(B.purchase_token, '-') AS order_id,
                    B.price, B.update_date, B.reg_date, B.status
                FROM gamedb.billing_info B
                JOIN accountdb.account_info A ON B.user_seq = A.user_seq
                JOIN gamedb.user_info U ON B.user_seq = U.user_seq
                LEFT JOIN cms_game.tbl_products P ON B.package_seq = P.id
                WHERE B.user_seq = ? AND B.status IN (3, 99)
                    AND B.update_date BETWEEN ? AND ?
                ORDER BY B.update_date DESC";

        return DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));
    }

    public static function getBillingLogAll($startDate, $endDate)
    {
        $sql = "SELECT B.billing_seq, B.user_seq, U.nickname, A.account, B.market_type, B.package_seq,
                    P.memo AS product_name, P.itemCount AS item_count, IFNULL(B.purchase_token, '-') AS order_id,
                    B.price, B.update_date, B.reg_date, B.status
                FROM gamedb.billing_info B
                JOIN accountdb.account_info A ON B.user_seq = A.user_seq
                JOIN gamedb.user_info U ON B.user_seq = U.user_seq
                LEFT JOIN cms_game.tbl_products P ON B.package_seq = P.id
                WHERE B.status IN (3, 99)
                    AND B.update_date BETWEEN ? AND ?
                ORDER BY B.update_date DESC";

        return DB::connection('mysql')->select($sql, array($startDate, $endDate));
    }

    public static function getMonthlyBillingAmount($userSeq)
    {
        $sql = "SELECT BA.amount
                FROM accountdb.billing_amount BA
                JOIN accountdb.certification C ON BA.di = C.di
                WHERE C.user_seq = ? ";

        $result = collect(DB::connection('mysql')->select($sql, array($userSeq)))->first();

        return is_null($result)? 0 : intval($result->amount);
    }

}
