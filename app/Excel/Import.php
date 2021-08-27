<?php

namespace App\Excel;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class Import
{
    public function import($file)
    {
        FastExcel::import($file, function ($line) {
            return Domain::create([
                'team_id' => auth()->user()->currentTeam->id,
                'name' => $line['網域名稱'],
                'domain_expired_at' => $line['域名到期時間'],
                'certificate_expired_at' => $line['憑證到期時間'] ?: null,
                'product' => $line['產品'],
                'submit' => $line['提交者'],
                'dns' => $line['DNS'],
                'nameservers' => explode(",", $line['名稱伺服器']),
                'vendor' => $line['域名商'],
                'remark' => $line['備註'],
            ]);
        });
    }
}
