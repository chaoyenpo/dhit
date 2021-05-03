<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebhookReceiver;
use App\Events\TelegramConnected;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Notifications\TelegramBotConnected;
use Illuminate\Support\Facades\Notification;

class TelegramBotController extends Controller
{
    public function link(Request $request)
    {
        $token = $request->token ?: Str::random(32);
        Cache::put($token, auth()->user()->id . ' ' . auth()->user()->currentTeam->id, 3600);

        return response()->json([
            "url" => 'https://t.me/fishsiribot?startgroup=' . $token,
            "token" => $token,
        ]);
    }

    public function callback(Request $request)
    {
        Log::debug("tg bot callback", $request->all());

        try {
            $token = explode(' ', data_get($request, 'message.text'))[1];
            list($userId, $teamId) = explode(' ', Cache::get($token));
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '此操作已失效。'
            ]);
        }

        $user = User::find($userId);

        Cache::forget($token);

        $webhookReceiver = WebhookReceiver::firstOrCreate([
            'token' => $token,
        ], [
            'team_id'=> $teamId,
            'user_id'=> $user->id,
            'chat' => data_get($request, 'message.chat'),
        ]);

        Notification::route('telegram', data_get($request, 'message.chat.id'))
            ->notify(new TelegramBotConnected());

        TelegramConnected::dispatch($webhookReceiver->id, $token);

        return response()->json([
            'ok' => true,
            'result' => true,
            'description' => '成功建立 Webhook 接收器。'
        ]);
    }
}
