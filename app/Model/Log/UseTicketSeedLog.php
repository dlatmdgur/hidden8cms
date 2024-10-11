<?php

namespace App\Model\Log;

use App\BaseModel;

class UseTicketSeedLog extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'logdb.use_seedticket_log';
    protected $primaryKey = 'log_seq';

    protected $guarded = [];
}
