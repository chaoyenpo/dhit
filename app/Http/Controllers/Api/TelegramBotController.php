<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TelegramBotController extends Controller
{
    public function link(Request $request)
    {
        Cache::put($token = Str::random(32), auth()->user()->id, 3600);

        return response()->json([
            "url" => 'https://t.me/fishsiribot?startgroup=' . $token,
        ]);
    }
}
