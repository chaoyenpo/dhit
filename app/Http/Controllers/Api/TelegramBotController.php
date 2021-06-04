<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WebhookReceiver;
use App\Events\TelegramConnected;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BotNotify;
use Illuminate\Support\Facades\Cache;
use App\Notifications\TelegramBotConnected;
use Illuminate\Support\Facades\Notification;

class TelegramBotController extends Controller
{
    public function callback(Request $request)
    {
        Log::debug("tg bot callback", $request->all());

        try {
            $token = explode(' ', data_get($request, 'message.text'))[1];
            list($userId, $teamId, $botId) = explode(' ', Cache::get($token));
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '此操作已失效。'
            ]);
        }

        Cache::forget($token);

        $webhookReceiver = WebhookReceiver::updateOrCreate([
            'token' => $token,
        ], [
            'jmte' => '',
            'team_id' => $teamId,
            'user_id' => $userId,
            'bot_id' => $botId,
            'chat' => data_get($request, 'message.chat'),
        ]);

        Notification::route('telegram', data_get($request, 'message.chat.id'))
            ->notify(new TelegramBotConnected($webhookReceiver->bot->token));

        TelegramConnected::dispatch($webhookReceiver->id, $token);

        return response()->json([
            'ok' => true,
            'result' => true,
            'description' => '成功建立 Webhook 接收器。'
        ]);
    }

    public function notifyCallback(Request $request)
    {
        Log::debug("tg bot notifyCallback", $request->all());

        try {
            $token = explode(' ', data_get($request, 'message.text'))[1];
            list($userId, $teamId, $botId) = explode(' ', Cache::get($token));
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '此操作已失效。'
            ]);
        }

        Cache::forget($token);

        $botNotify = BotNotify::updateOrCreate([
            'team_id' => $teamId,
            'bot_id' => $botId,
        ], [
            'user_id' => $userId,
            'chat' => data_get($request, 'message.chat'),
        ]);

        Notification::route('telegram', data_get($request, 'message.chat.id'))
            ->notify(new TelegramBotConnected($botNotify->bot->token));

        TelegramConnected::dispatch($botNotify->id, $token);

        return response()->json([
            'ok' => true,
            'result' => true,
            'description' => '成功將 TG 機器人連接至 TG 群組。'
        ]);
    }
}
