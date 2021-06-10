<?php

namespace App\Notifications;

use App\Models\BotNotify;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class DomainExpired extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fileName, BotNotify $botNotify)
    {
        $this->fileName = $fileName;
        $this->botNotify = $botNotify;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $file = Storage::disk('public')->get($this->fileName);
        return TelegramFile::create()
            ->content(implode(PHP_EOL,[
                '*網域到期通知*' . PHP_EOL,
                // '有 n 個網域即將到期' . PHP_EOL,
                // '有 n 個網域已過期',
            ]))
            ->document($file, '網域到期通知.xlsx')
            ->token($this->botNotify->bot->token);
    }

    public function failed(\Exception $e)
    {
        $this->botNotify->malfunction = $e->getMessage();
        $this->botNotify->save();
    }
}
