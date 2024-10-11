<?php


namespace App\Model\Log;

use App\BaseModel;

class NickLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.nick_log';
    protected $primaryKey = 'log_idx';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'item_seq', '_before', '_after',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'log_date' => 'datetime',
    ];

}
