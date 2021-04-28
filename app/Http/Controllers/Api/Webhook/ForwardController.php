<?php

namespace App\Http\Controllers\Api\Webhook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WebhookReceiver;
use App\Notifications\WebhookForward;
use Illuminate\Support\Facades\Notification;

class ForwardController extends Controller
{
    public function receive(Request $request, $token)
    {
        if (! $webhookReceiver = WebhookReceiver::whereToken($token)->first()) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '無效 Token'
            ]);
        }

        try {
            Notification::route('telegram', data_get($webhookReceiver, 'chat.id'))
                ->notify(new WebhookForward($request, $webhookReceiver));

            if ($webhookReceiver->malfunction) {
                $webhookReceiver->malfunction = null;
                $webhookReceiver->save();
            }
        } catch (\Throwable $th) {
            $webhookReceiver->malfunction = $th->getMessage();
            $webhookReceiver->save();
        }

        return response()->json([
            'ok' => true,
            'result' => true,
            'description' => 'OK'
        ]);
    }
}
