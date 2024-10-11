<?php

namespace App\Model\Tables;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class Item extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tbl_items';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'memo', 'group', 'itemType', 'rewardAmount', 'imageName', 'itemName',
    ];

    public static function getItemNames()
    {
        $itemSql = "SELECT id, memo FROM cms_game.tbl_items";
        $items = DB::connection('mysql')->select($itemSql);
        $itemNames = [];
        foreach($items as $item) {
            $itemNames[$item->id] = $item->memo;
        }
        // Chip = 2016, Gold = 2018, Gem = 2017, GemEvent = 2025, GoldTicket = 2019
        $itemNames['2016'] = '칩';
        $itemNames['2018'] = '골드';
        $itemNames['2017'] = '보석';
        $itemNames['2019'] = '골드티켓';
        $itemNames['2025'] = '무료보석';

        return $itemNames;
    }

}
