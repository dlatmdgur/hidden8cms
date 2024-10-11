<?php

namespace App\Model\Tables;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class ProductName extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tbl_productNames';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'memo', 'itemCount', 'title',
    ];

    public static function getProductNames()
    {
        $productSql = "SELECT id, memo FROM cms_game.tbl_products";
        $products = DB::connection('mysql')->select($productSql);
        $productNames = [];
        foreach($products as $product) {
            $productNames[$product->id] = $product->memo;
        }

        return $productNames;
    }
}
