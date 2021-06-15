<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\BotNotify;
use App\Exports\DomainExport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\DomainExpired;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckDomainExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $expired = Carbon::today()->addMonth();

        $data = [];

        Domain::with('team')->chunk(200, function ($domains) use ($expired, &$data) {
            foreach ($domains as $domain) {
                if ($domain->domain_expired_at->lessThanOrEqualTo($expired)) {
                    $data[$domain->team_id][] = [
                        $domain->name,
                        $domain->tag,
                        $domain->domain_expired_at->toDateString(),
                        $domain->certificate_expired_at->toDateString(),
                        $domain->remark,
                    ];
                    continue;
                };  

                if ($domain->certificate_expired_at &&  $domain->certificate_expired_at->lessThanOrEqualTo($expired)) {
                    $data[$domain->team_id][] = [
                        $domain->name,
                        $domain->tag,
                        $domain->domain_expired_at->toDateString(),
                        $domain->certificate_expired_at->toDateString(),
                        $domain->remark,
                    ];                    
                }
            }
        });

        // 每個 team 一個檔案
        foreach ($data as $teamId => $willExpiredDomain) {
            Excel::store(new DomainExport($willExpiredDomain),  ($fileName = Carbon::today()->toDateString() . '-'. $teamId.'.xlsx'), 'public');
            // $fileUrl = Storage::disk('public')->url($fileName);            
            Log::debug("域名過期彙整資料", $data);

            try {
                $botNotify = BotNotify::with('bot')->whereTeamId($teamId)->firstOrFail();
                Notification::route('telegram', data_get($botNotify, 'chat.id'))
                    ->notify(new DomainExpired($fileName, $botNotify));
                if ($botNotify->malfunction) {
                    $botNotify->malfunction = null;
                    $botNotify->save();
                }
            } catch (\Throwable $th) {
                $botNotify->malfunction = $th->getMessage();
                $botNotify->save();                
            }
        }        
    }
}

