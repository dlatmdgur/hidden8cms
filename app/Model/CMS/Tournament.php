<?php

namespace App\Model\CMS;

use App\BaseModel;

class Tournament extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tournament.tournament_list';
    protected $primaryKey = 'tid';
    public $timestamps = false;

    protected $guarded = [];

}
