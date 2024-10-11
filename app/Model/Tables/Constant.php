<?php

namespace App\Model\Tables;

use App\BaseModel;

class Constant extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tbl_constants';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'value', 'desc',
    ];

}
