<?php

namespace App\Model\Account;

use App\BaseModel;

class MemberRecommend extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'auth_platform.member_recommend';
    protected $primaryKey = 'mid';
    public $incrementing = false;
    
}
