<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LevelImport implements ToModel, WithHeadingRow
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

        return new Level([
            'id' => $row['u_id'],
            'level' => $row['i_level'],
            'maxExp' => $row['i_maxexp'],
            'gamePlayCount' => $row['i_gameplaycount'],
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
