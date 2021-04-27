<?php

namespace App\Http\Controllers\Api\Webhook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\WebhookForward;
use Illuminate\Support\Facades\Notification;

class ForwardController extends Controller
{
    public function receive(Request $request)
    {
        Notification::route('telegram', env('TELEGRAM_CHAT_ID'))
            ->notify(new WebhookForward($request));

        return response()->json([
            'message' => 'OK',
        ]);
    }
}
