<?php


namespace App\Model\Account;

use App\BaseModel;

class Friend extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.friend';
    protected $primaryKey = 'sequence_no';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'friend_seq', 'friend_nickname'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
//        'delete_date' => 'datetime',
    ];

}
