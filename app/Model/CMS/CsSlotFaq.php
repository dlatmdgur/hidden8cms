<?php

namespace App\Model\CMS;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class CsSlotFaq extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'cms_game.cs_slot_faq';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order', 'status', 'game_type', 'category', 'title', 'content', 'os', 'admin_id', 'admin_name',
        'reserve_start_date', 'reserve_end_date', 'create_date', 'update_date', 'is_delete'
    ];

    public static function getList($params)
    {
        $whereSql = "";
        if ($params['status'] != '-1') {
            $whereSql .= "   AND `status` = '" . $params['status'] . "' ";
        }
        if ($params['game_type'] != '-1') {
            $whereSql .= "   AND game_type = '" . $params['game_type'] . "' ";
        }
        if ($params['category'] != '-1') {
            $whereSql .= "   AND category = '" . $params['category'] . "' ";
        }
        if ($params['os'] != '-1') {
            $whereSql .= "   AND os = '" . $params['os'] . "' ";
        }
        if ($params['startDate'] != '-1' && $params['endDate'] != '-1') {
            $whereSql .= "   AND update_date BETWEEN ? AND ? ";
        }
        if ($params['admin_name'] != '') {
            $whereSql .= "   AND admin_name LIKE '%" . $params['admin_name'] . "%' ";
        }
        $orderBySql = "ORDER BY update_date DESC";
        if ($params['ordered'] == 'true') {
            $orderBySql = "ORDER BY `order` DESC ";
        }

        $sql = " SELECT `id`, `order`, `status`, `game_type`, `category`, `title`, `os`, `admin_id`, `admin_name`, `create_date`, `update_date`, `is_delete` ".
            "FROM cms_game.cs_slot_faq ".
            "WHERE is_delete = 0 ".
            $whereSql.
            $orderBySql;

        return DB::connection('mysql')->select($sql, [$params['startDate'], $params['endDate']]);
    }

    public static function getSiblingOne($params)
    {
        $whereSql = "";
//        if ($params['status'] != 'all') {
//            $whereSql .= "   AND `status` = '" . $params['status'] . "' ";
//        }
//        if ($params['game_type'] != 'all') {
//            $whereSql .= "   AND game_type = '" . $params['game_type'] . "' ";
//        }
//        if ($params['category'] != 'all' && $params['category'] != '0') {
//            $whereSql .= "   AND category = '" . $params['category'] . "' ";
//        }
//        if ($params['os'] != 'all') {
//            $whereSql .= "   AND os = '" . $params['os'] . "' ";
//        }
        $orderBySql = "";
        if ($params['direction'] == "up") {
            $whereSql .= "   AND id <> ? AND `order` <= ? ";
            $orderBySql = " ORDER BY `order` DESC, update_date DESC LIMIT 1";
        } else if ($params['direction'] == "down") {
            $whereSql .= "   AND id <> ? AND `order` >= ? ";
            $orderBySql = " ORDER BY `order` ASC, update_date DESC LIMIT 1";
        }

        $sql = " SELECT `id`, `order`, `status`, `game_type`, `category`, `title`, `os`, `admin_id`, `admin_name`, `create_date`, `update_date`, `is_delete` ".
            " FROM cms_game.cs_slot_faq ".
            " WHERE is_delete = 0 ".
            $whereSql.
            $orderBySql;

        return collect(DB::connection('mysql')->select($sql, [ $params['id'], $params['order'], ]))->first();
    }

    public static function getNewOrder()
    {
        $sql = "SELECT max(`order`) as maxOrder from cms_game.cs_slot_faq;";
        return collect(DB::connection('mysql')->select($sql))->first();
    }

    public static function updateOrder($article)
    {
        $sql = " UPDATE cms_game.cs_slot_faq SET `order` = ? WHERE id = ? ";
        return DB::connection('mysql')->update($sql, [ $article->order, $article->id, ]);
    }

    public static function changeOrder($params)
    {
        if ($params['direction'] == "up") {
            $newOrder = intval($params['order']) + 1;
        } else {
            $newOrder = ((intval($params['order']) - 1) >= 0) ? intval($params['order']) - 1 : 0;
        }

        // find target id and change order
        $sibling = self::getSiblingOne($params);

        if ($sibling != null) {
            $sibling->order = $params['order'];
            self::updateOrder($sibling);

            // change
            $target = self::where('id', $params['id'])->first();
            $target->order = $newOrder;
            self::updateOrder($target);
        }

    }

    public static function updateArticles($ids, $colName, $colValue)
    {
        $idSet = implode(',', $ids);
        $sql = "UPDATE cms_game.cs_slot_faq SET ".
            " `".$colName."` = '". $colValue . "' ".
            " WHERE FIND_IN_SET(`id`, '" . $idSet . "') ";

        return DB::connection('mysql')->update($sql);
    }

    public static function getPreviewList()
    {
        $sql = "SELECT title, content AS contents, game_type, category, os, `status`, reserve_start_date, reserve_end_date, update_date AS `date` " .
            "FROM cms_game.cs_slot_faq ".
            "WHERE status > 0 AND is_delete = 0 ".
            "ORDER BY `order` DESC";

        return DB::connection('mysql')->select($sql);
    }
}

