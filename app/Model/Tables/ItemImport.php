<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
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

        return new Item([
            'id' => $row['u_id'],
            'memo' => $row['n_memo'],
            'group' => $row['i_group'],
            'itemType' => $row['itemtype_itemtype'],
            'rewardAmount' => $row['l_rewardamount'],
            'imageName' => $row['s_imagename'],
            'itemName' => $row['s_itemname'],
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
