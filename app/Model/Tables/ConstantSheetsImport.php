<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Model\Tables\ConstantImport;

class ConstantSheetsImport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            0 => new ConstantImport()
        ];
    }
}
