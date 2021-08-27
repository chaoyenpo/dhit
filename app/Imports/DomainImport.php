<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Domain;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DomainImport implements ToModel, WithHeadingRow, WithUpserts, WithBatchInserts, WithChunkReading, ShouldQueue
{
    public function __construct(User $importedBy)
    {
        $this->importedBy = $importedBy;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // 如果是使用 excel 匯入，時間欄位要確保是時間格式不然會出錯

        return new Domain([
            'team_id' => $this->importedBy->currentTeam->id,
            'name' => $row['網域名稱'],
            'domain_expired_at' => Date::excelToDateTimeObject($row['域名到期時間']),
            'certificate_expired_at' => $row['憑證到期時間'] ? Date::excelToDateTimeObject($row['憑證到期時間']) : null,
            'product' => $row['產品'],
            'submit' => $row['提交者'],
            'dns' => $row['DNS'],
            'nameservers' => explode(",", $row['名稱伺服器']),
            'vendor' => $row['域名商'],
            'remark' => $row['備註'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function uniqueBy()
    {
        return ['team_id', 'name'];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
