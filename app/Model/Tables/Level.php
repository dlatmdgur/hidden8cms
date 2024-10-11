<?php

namespace App\Model\Tables;

use App\BaseModel;

class Level extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tbl_levels';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'level', 'maxExp', 'gamePlayCount',
    ];

}
