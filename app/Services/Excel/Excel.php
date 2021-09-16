<?php

namespace App\Services\Excel;

use App\Models\Domain;
use Illuminate\Support\Carbon;
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

    public function import($file, $teamId)
    {
        FastExcel::import($file, function ($line) use ($teamId) {
            $this->add([
                'team_id' => $teamId,
                'name' => $line['網域名稱[必填]'],
                'domain_expired_at' => Carbon::parse($line['域名到期時間[必填]']),
                'certificate_expired_at' =>
                $line['憑證到期時間'] ? Carbon::parse($line['憑證到期時間']) : null,
                'product' => $line['產品'],
                'submit' => $line['提交者'],
                'dns' => $line['DNS'],
                'nameservers' => json_encode(explode(",", $line['名稱伺服器'])),
                'vendor' => $line['域名商'],
                'remark' => $line['備註'],
            ]);

            if (count($this->rows) === $this->chunkSize()) {
                Domain::upsert(
                    $this->rows,
                    $this->uniqueBy(),
                );

                $this->rows = [];
            }
        });

        if (!empty($this->rows)) {
            Domain::upsert(
                $this->rows,
                $this->uniqueBy(),
            );
        }
    }
}
