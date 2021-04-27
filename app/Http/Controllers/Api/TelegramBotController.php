<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebhookRecevier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TelegramBotController extends Controller
{
    public function link(Request $request)
    {
        Cache::put($token = Str::random(32), auth()->user()->id . ' ' . auth()->user()->currentTeam->id, 3600);

        return response()->json([
            "url" => 'https://t.me/fishsiribot?startgroup=' . $token,
        ]);
    }

    public function callback(Request $request)
    {
        $token = explode(' ', data_get($request, 'message.text'))[1];

        list($userId, $teamId) = explode(' ', Cache::get($token));

        $user = User::find($userId);

        Cache::forget($token);

        WebhookRecevier::create([
            'team_id'=> $teamId,
            'user_id'=> $user->id,
            'token' => Str::random(32),
            'chat' => data_get($request, 'message.chat'),
        ]);

        return response()->json([
            'ok' => true,
            'result' => true,
            'description' => '成功建立 Webhook 接收器。'
        ]);
    }
}
