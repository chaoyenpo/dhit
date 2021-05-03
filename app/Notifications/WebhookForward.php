<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Models\WebhookReceiver;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class WebhookForward extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request, WebhookReceiver $webhookReceiver)
    {
        $this->request = $request;
        $this->webhookReceiver = $webhookReceiver;
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
        return TelegramMessage::create()
            ->content(
                // Telegram 只能發送 4096 bytes 的資料，扣掉 Str:limit end 結尾的三個點，只剩下 4093 bytes。
                // 未來加入分批發送功能（可透過 Content-Length 取得字串長度再去 Chunk）。
                '*' . Str::limit($this->praseRequestContent(), 4093) . '*'
            )->token($this->webhookReceiver->bot->token);
    }

    public function failed(\Exception $e)
    {
        $this->webhookReceiver->malfunction = $e->getMessage();
        $this->webhookReceiver->save();
    }

    private function praseRequestContent()
    {
        if ($this->request->all()) {
            return json_encode($this->request->all(), JSON_PRETTY_PRINT);
        }
        return file_get_contents('php://input');
    }

    private function jsonToReadable($json)
    {
        $tc = 0; //tab count
        $r = ''; //result
        $q = false; //quotes
        $t = "\t"; //tab
        $nl = "\n"; //new line

        for ($i = 0; $i < strlen($json); $i++) {
            $c = $json[$i];
            if ($c == '"' && $json[$i - 1] != '\\') {
                $q = !$q;
            }

            if ($q) {
                $r .= $c;
                continue;
            }
            switch ($c) {
                case '{':
                case '[':
                    $r .= $c . $nl . str_repeat($t, ++$tc);
                    break;
                case '}':
                case ']':
                    $r .= $nl . str_repeat($t, --$tc) . $c;
                    break;
                case ',':
                    $r .= $c;
                    if ($json[$i + 1] != '{' && $json[$i + 1] != '[') {
                        $r .= $nl . str_repeat($t, $tc);
                    }

                    break;
                case ':':
                    $r .= $c . ' ';
                    break;
                default:
                    $r .= $c;
            }
        }
        return $r;
    }
}
