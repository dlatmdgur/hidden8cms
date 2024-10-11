<?php


namespace App\Model\Account;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class TodayLoseChip extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.today_losechip';
    protected $primaryKey = 'di';

    public static function getTodayLoseChip($userSeq)
    {
        $sql = "SELECT C.user_seq, C.di, TL.lose_chip, TL.losechip_exceed_date, TL.losechip_date, TL.losechip_type
                     , TL.losechip_changecnt, TL.changecnt_date, TL.change_type_date
                FROM accountdb.today_losechip TL
                JOIN accountdb.certification C ON TL.di = C.di
                WHERE C.user_seq = ?;";

        return collect(DB::connection('mysql')->select($sql, array($userSeq)));
    }

}
