<?php

namespace App\Services\Rayquaza;

use App\Jobs\ImportFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Validation\ValidationException;

class Rayquaza
{
    /**
     * @var array
     */
    public $columnNames = [
        '網域名稱[必填]',
        '域名到期時間[必填]',
        '憑證到期時間',
        '產品',
        '提交者',
        'DNS',
        '名稱伺服器',
        '域名商',
        '備註',
    ];

    public $requiredColumnNames = [
        '網域名稱[必填]',
        '域名到期時間[必填]',
    ];

    public $errors;

    public $isValid = false;

    public $rowCursor = 1;

    public function __construct()
    {
        $this->errors = collect();
        $this->requiredColumnNames = collect($this->requiredColumnNames);
    }

    /**
     * @param int   $row
     * @param array $attributes
     */
    public function import($path, $executor)
    {
        FastExcel::import($path, function ($line) {
            if (!$this->isValid) {
                $this->isValid = Arr::has($line, $this->columnNames);

                if (!$this->isValid) {
                    throw ValidationException::withMessages([
                        'file' => '不可更改資料欄名稱，檔案中的資料欄名稱必須與提供的範本檔案相同。'
                    ])->errorBag('uploadDomain');
                }
            }

            $this->requiredColumnNames->each(function ($requiredColumnName) use ($line) {
                if (empty($line[$requiredColumnName])) {
                    $this->errors->push('第 ' . $this->rowCursor . ' 列中的「' . $requiredColumnName . '」欄沒有任何內容。');
                }
            });

            try {
                Carbon::parse($line['域名到期時間[必填]']);
            } catch (InvalidFormatException $e) {
                $this->errors->push('第 ' . $this->rowCursor . ' 列中的「域名到期時間[必填]」欄時間格式有誤。請使用正確時間格式，例如：2022-02-22。');
            }

            if (!empty($line['憑證到期時間'])) {
                try {
                    Carbon::parse($line['憑證到期時間']);
                } catch (InvalidFormatException $e) {
                    $this->errors->push('第 ' . $this->rowCursor . ' 列中的「憑證到期時間」欄時間格式有誤。請使用正確時間格式，例如：2022-02-22。');
                }
            }

            $this->rowCursor += 1;
        });

        if ($this->errors->count() > 0) {
            throw ValidationException::withMessages([
                'file' => $this->errors
            ])->errorBag('uploadDomain');
        }

        ImportFile::dispatch($path, $executor->id, $executor->currentTeam->id);
    }
}
