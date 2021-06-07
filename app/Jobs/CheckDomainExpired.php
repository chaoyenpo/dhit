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
        $bot = BotNotify::with('bot')->first();

        $expired = Carbon::today()->addMonth();
        Domain::chunk(200, function ($domains) use ($bot, $expired) {
            foreach ($domains as $domain) {
                if ($domain->expired_at->lessThanOrEqualTo($expired)) {
                    try {
                        Notification::route('telegram', data_get($bot, 'chat.id'))
                            ->notify(new DomainExpired("這個網域將在 30 天內到期:" . $domain->name, $bot));
                        if ($bot->malfunction) {
                            $bot->malfunction = null;
                            $bot->save();
                        }
                    } catch (\Throwable $th) {
                        $bot->malfunction = $th->getMessage();
                        $bot->save();
                    }
                };  
            }
        });
    }
}

