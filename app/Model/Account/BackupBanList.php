<?php


namespace App\Model\Account;

use App\BaseModel;

class BackupBanList extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.backup_ban_list';
    protected $primaryKey = 'user_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'account', 'nickname', 'chip', 'safe_chip', 'gold', 'safe_gold', 'gem', 'event_gem', 'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'date' => 'datetime',
    ];

}
