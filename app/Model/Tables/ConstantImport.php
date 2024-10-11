<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ConstantImport implements ToModel, WithHeadingRow
{

    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!isset($row['l_value'])) {
            return null;
        }

        if ($row['s_type'] === '타입') {
            return null;
        }

        return new Constant([
            'type' => $row['s_type'],
            'name' => $row['s_name'],
            'value' => intval($row['l_value']),
            'desc' => $row['n_desc'],
        ]);
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }

}
