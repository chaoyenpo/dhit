<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\BotNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Notifications\DomainExpired;
use Illuminate\Queue\SerializesModels;
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
        //TODO 目前只支援一台機器人
        $expired = Carbon::today()->addMonth();
        Domain::with('team')->chunk(200, function ($domains) use ($expired) {
            foreach ($domains as $domain) {
                if ($domain->expired_at->lessThanOrEqualTo($expired)) {
                    try {
                        $botNotify = BotNotify::with('bot')->whereTeamId($domain->team->id)->first();
                        Notification::route('telegram', data_get($bot, 'chat.id'))
                            ->notify(new DomainExpired("這個網域將在 30 天內到期:" . $domain->name, $botNotify));
                        if ($botNotify->malfunction) {
                            $botNotify->malfunction = null;
                            $botNotify->save();
                        }
                    } catch (\Throwable $th) {
                        $botNotify->malfunction = $th->getMessage();
                        $botNotify->save();
                    }
                };  
            }
        });
    }
}

