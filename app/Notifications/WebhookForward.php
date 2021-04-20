<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
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
    public function __construct(Request $request)
    {
        $this->request = $request;
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
                implode(
                    PHP_EOL,
                    [
                        '*Webhook 轉發通知*' . PHP_EOL,

                        '內容：' . PHP_EOL . '*' . $this->praseRequestContent() . '*' . PHP_EOL,

                        '來自：*' . implode('|', $this->request->ips()) . '*' . PHP_EOL,
                    ]
                )
            );
    }

    private function praseRequestContent()
    {
        if ($this->request->all()) {
            return $this->jsonToReadable(json_encode($this->request->all()));
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
