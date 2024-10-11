<?php


namespace App\Model\Game;

use App\BaseModel;

class FreeChargeInfoAdmob extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'gamedb.free_charge_info_admob';
    protected $primaryKey = 'user_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'count',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
    ];

}
