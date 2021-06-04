<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DomainValidController extends Controller
{
    public function show(Request $request)
    {
        $domains = Domain::whereTeamId($request->user()->currentTeam->id)->get();

        return Inertia::render('Domain/Show', [
            'domains' => $domains,
        ]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'domains' => ['required', 'file'],
        ])->validateWithBag('uploadDomain');

        $data = $this->csv_to_array($request->file('domains'));

        foreach ($data as $domain) {
            Domain::updateOrCreate([
                'name' => $domain['name']
            ], [
                'team_id' => auth()->user()->currentTeam->id,
                'tag' => $domain['tag'],
                'expired_at' => $domain['expired_at'],
                'remark' => $domain['remark'],
            ]);
        }

        return back();
    }

    private function csv_to_array($filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        if ($delimiter == ',') {
            $csv = array_map('str_getcsv', file($filename));
        } else {
            $lines = file($filename);
            $line_num = count($lines);
            $dm = []; // $delimiter

            $csv = array_map('str_getcsv', $lines, array_pad($dm, $line_num, $delimiter));
        }

        array_walk($csv, function (&$row) use ($csv) {
            $row = array_combine($csv[0], $row);
        });

        array_shift($csv); // 移掉第一行的標題陣列

        return $csv;
    }
}
