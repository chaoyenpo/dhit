<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DomainExport implements FromArray, WithHeadings
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            '網域名稱',
            '標籤',
            '域名到期時間',
            '憑證到期時間',
            '產品',
            '提交者',
            'DNS',
            '域名商',
            '備註'
        ];
    }
}
