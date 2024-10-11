<?php

namespace App\Model\CMS;

use App\BaseModel;
class ClientVersion extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.version_info';
    protected $primaryKey = 'version';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['version', 'update_date'];


}
