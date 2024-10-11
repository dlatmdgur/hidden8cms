<?php

namespace App\Model\CMS;

use App\BaseModel;

class RollingNotice extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'cms_game.rolling_notice';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'noti_count', 'noti_counted', 'noti_interval', 'message'
      , 'start_datetime', 'expire_datetime', 'dday', 'created_datetime'
    ];
}
