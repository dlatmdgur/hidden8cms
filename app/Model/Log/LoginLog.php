<?php


namespace App\Model\Log;

use App\BaseModel;

class LoginLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.login_log';
    protected $primaryKey = 'log_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'result', 'login_type', 'os_type', 'ip',
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
