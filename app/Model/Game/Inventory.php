<?php


namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class Inventory extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'gamedb.inventory';
    protected $primaryKey = 'inven_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'item_type', 'item_ea', 'item_id', 'period_time', 'update_date', 'is_delete', 'is_use', 'is_use_period',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
    ];

    public static function getInventory($userSeq, $startDate, $endDate)
    {
        $sql = " SELECT IV.inven_seq, IV.user_seq, IV.item_type, IV.item_id, IV.item_ea, I.memo,
                        IV.period_time, IV.update_date, IV.is_use, IV.is_use_period
                 FROM gamedb.inventory IV
                 LEFT JOIN cms_game.tbl_items I ON IV.item_id = I.id
                 WHERE user_seq = ? AND IV.is_delete = 0
                    AND IV.update_date BETWEEN ? AND ?
                    ORDER BY IV.update_date DESC";

        return DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));
    }
}
