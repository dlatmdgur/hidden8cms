<?php

namespace App\Model\Tables;

use App\BaseModel;

class Product extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'tbl_products';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'memo', 'itemType', 'itemId', 'itemCount', 'term', 'packageTableId', 'membersTableId', 'sale',
        'goodsType', 'price', 'googleInAppCode', 'saleStartDate', 'saleEndDate', 'imageName', 'event',
        'productNameTableId', 'shopType',
    ];

}
