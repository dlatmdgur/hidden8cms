<?php

namespace App\Model\Account;

use App\BaseModel;

class Member extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'auth_platform.member';
    protected $primaryKey = 'id';
    public $incrementing = false;

}
