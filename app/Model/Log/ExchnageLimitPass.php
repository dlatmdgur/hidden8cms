<?php

namespace App\Model\Log;

use App\BaseModel;
use App\Model\Tables\Item;
use App\Model\Tables\Member;
use App\Model\Tables\ProductName;
use Illuminate\Support\Facades\DB;

class ExchnageLimitPass extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'cms_game.exchange_limit_pass';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'admin_id', 'admin_name', 'created_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_date' => 'datetime:Y-m-d H:i:s'
    ];

    public static function getExchangeLimitPassUsers()
    {
        $sql = "SELECT P.*, A.platform_id, A.google_email, A.nickname
                FROM cms_game.exchange_limit_pass P
                JOIN accountdb.account_info A ON P.user_seq = A.user_seq";
        return DB::connection('mysql')->select($sql, array());
    }

}
