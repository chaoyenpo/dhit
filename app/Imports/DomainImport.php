<?php

namespace App\Imports;

use App\Models\Domain;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DomainImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return Domain::updateOrCreate([
            'team_id' => auth()->user()->currentTeam->id,
            'name' => $row['網域名稱'],
        ], [
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
}
