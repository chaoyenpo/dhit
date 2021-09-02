<?php

namespace App\Services\Excel;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class Excel
{
    /**
     * @var array
     */
    private $rows = [];

    /**
     * @param int   $row
     * @param array $attributes
     */
    public function add(array $attributes)
    {
        $this->rows[] = $attributes;
    }

    public function uniqueBy()
    {
        return ['team_id', 'name'];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function import($file)
    {
        return '成功';
        // FastExcel::import($file, function ($line) {
        //     self::add([
        //         'team_id' => auth()->user()->currentTeam->id,
        //         'name' => $line['網域名稱'],
        //         'domain_expired_at' => $line['域名到期時間'],
        //         'certificate_expired_at' => $line['憑證到期時間'] ?: null,
        //         'product' => $line['產品'],
        //         'submit' => $line['提交者'],
        //         'dns' => $line['DNS'],
        //         'nameservers' => explode(",", $line['名稱伺服器']),
        //         'vendor' => $line['域名商'],
        //         'remark' => $line['備註'],
        //     ]);

        //     if (count(self::$rows) === self::chunkSize()) {
        //         Domain::upsert(
        //             self::$rows,
        //             ['team_id', 'name'],
        //         );

        //         self::$rows = [];
        //     }
        // });

        // if (!empty(self::$rows)) {
        //     Domain::upsert(
        //         self::$rows,
        //         self::uniqueBy(),
        //     );
        // }
    }
}
