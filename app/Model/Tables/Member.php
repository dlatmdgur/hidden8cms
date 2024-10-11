<?php

namespace App\Model\Tables;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class Member extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tbl_members';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'memo', 'sale', 'price', 'gameChip', 'dayGift', 'dayGiftCount', 'gameChipLimit', 'gameChipSafeLimit',
        'goldLimit', 'goldSafeLimit', 'gameDiscount', 'gameChipRefill', 'dayUseCount', 'avatarCardCount', 'term',
        'name', 'goldFreeCharge', 'timeBonusCount', 'ticketId', 'ticektCount',
    ];

    public static function getMembersNames()
    {
        $membersSql = "SELECT id, memo FROM cms_game.tbl_members";
        $membersList = DB::connection('mysql')->select($membersSql);
        $membersNames = [];
        foreach($membersList as $members) {
            $membersNames[$members->id] = $members->memo;
        }

        return $membersNames;
    }
}
