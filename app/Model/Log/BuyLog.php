<?php


namespace App\Model\Log;

use App\BaseModel;
use App\Model\Tables\ProductName;
use Illuminate\Support\Facades\DB;

class BuyLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.buy_log';
    protected $primaryKey = 'buy_log_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'product_id', 'price', 'reg_date', 'gem_type', 'before_gem', 'before_event_gem', 'after_gem', 'after_event_gem'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'log_date' => 'datetime',
    ];

    public static function getBuyLogs($userSeq, $startDate, $endDate)
    {
        $sql = " SELECT BL.buy_log_seq, BL.user_seq, BL.product_id, P.memo, BL.price, BL.buy_count, BL.reg_date,
                        BL.gem_type, BL.before_gem, BL.before_event_gem, BL.after_gem, BL.after_event_gem
                 FROM logdb.buy_log BL
                 LEFT JOIN cms_game.tbl_products P ON BL.product_id = P.id
                 WHERE user_seq = ?
                    AND BL.reg_date BETWEEN ? AND ?
                 ORDER BY BL.reg_date DESC";

        $buyLogs = DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));

        $productNames = ProductName::getProductNames();

        foreach($buyLogs as $index => $logs) {
            $buyLogs[$index]->memo = $productNames[$logs->product_id];
        }

        return $buyLogs;
    }
}
