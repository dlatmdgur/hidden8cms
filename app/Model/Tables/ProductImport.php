<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{

    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if (!isset($row['u_id'])) {
            return null;
        }

        if (gettype($row['u_id']) !== "integer") {
            return null;
        }

        return new Product([
            'id' => $row['u_id'],
            'memo' => $row['n_memo'],
            'itemType' => $row['itemtype_itemtype'],
            'itemId' => $row['u_itemid'],
            'itemCount' => $row['i_itemcount'],
            'term' => $row['i_term'],
            'packageTableId' => $row['u_packagetableid'],
            'membersTableId' => $row['u_memberstableid'],
            'sale' => $row['c_sale'],
            'goodsType' => $row['goodstype_sellgoodstype'],
            'price' => $row['i_price'],
            'googleInAppCode' => $row['s_googleinappcode'],
            'saleStartDate' => ($row['s_salesstartdate'] == 0)? NULL : $row['s_salesstartdate'],
            'saleEndDate' => ($row['s_salesenddate'] == 0)? NULL : $row['s_salesenddate'],
            'imageName' => $row['s_imagename'],
            'event' => $row['c_event'],
            'productNameTableId' => $row['i_productnametableid'],
            'shopType' => $row['i_shoptype'],
        ]);
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 2;
    }

}
