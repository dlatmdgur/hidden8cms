<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ExchangeLogExport implements FromArray
{
    protected $exchangeLog;

    public function __construct(array $exchangeLog)
    {
        $this->exchangeLog = $exchangeLog;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->exchangeLog;
    }
}
