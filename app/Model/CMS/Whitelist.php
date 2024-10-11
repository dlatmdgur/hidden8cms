<?php

namespace App\Model\CMS;

use App\BaseModel;
class Whitelist extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.whitelists';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip', 'description', 'created_datetime'];
}
