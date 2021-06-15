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
            'name' => $row['domain'],
        ],[
            'tag' => $row['tag'],
            'domain_expired_at' => Date::excelToDateTimeObject($row['domain_expired_at']),
            'certificate_expired_at' => $row['certificate_expired_at'] ? Date::excelToDateTimeObject($row['certificate_expired_at']) : null,
            'remark' => $row['remark'],
        ]);
    }
}
