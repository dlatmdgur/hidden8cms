<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductNameImport implements ToModel, WithHeadingRow
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

        return new ProductName([
            'id' => $row['u_id'],
            'memo' => $row['n_memo'],
            'itemCount' => $row['s_itemcount'],
            'title' => $row['s_title'],
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
