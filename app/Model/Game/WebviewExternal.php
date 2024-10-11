<?php

namespace App\Model\Game;

use App\BaseModel;

class WebviewExternal extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'sw_common.external_slotlist';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
