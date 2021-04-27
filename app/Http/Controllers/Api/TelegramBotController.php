<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebhookRecevier;
use App\Events\TelegramConnected;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TelegramBotController extends Controller
{
    public function link(Request $request)
    {
        Cache::put($token = Str::random(32), auth()->user()->id . ' ' . auth()->user()->currentTeam->id, 3600);

        return response()->json([
            "url" => 'https://t.me/fishsiribot?startgroup=' . $token,
            "token" => $token,
        ]);
    }

    public function callback(Request $request)
    {
        Log::debug("callback", $request->all());

        try {
            $token = explode(' ', data_get($request, 'message.text'))[1];
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '此操作已失效。'
            ]);
        }

        list($userId, $teamId) = explode(' ', Cache::get($token));

        $user = User::find($userId);

        Cache::forget($token);

        $webhookRecevier = WebhookRecevier::create([
            'team_id'=> $teamId,
            'user_id'=> $user->id,
            'token' => Str::random(32),
            'chat' => data_get($request, 'message.chat'),
        ]);

        TelegramConnected::dispatch($webhookRecevier->id, $token);

        return response()->json([
            'ok' => true,
            'result' => true,
            'description' => '成功建立 Webhook 接收器。'
        ]);
    }
}
