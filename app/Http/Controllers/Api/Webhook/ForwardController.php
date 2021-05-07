<?php

namespace App\Http\Controllers\Api\Webhook;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebhookReceiver;
use App\Http\Controllers\Controller;
use App\Notifications\WebhookForward;
use Illuminate\Support\Facades\Notification;

class ForwardController extends Controller
{
    public function receive(Request $request, $token)
    {
        if (!$webhookReceiver = WebhookReceiver::whereToken($token)->first()) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '無效 Token'
            ]);
        }

        $dql = Arr::dot(json_decode(json_encode($webhookReceiver->dql), true));

        if ($dql) {
            $result = [];
            foreach ($dql as $key => $value) {
                // 判斷抓到第一個 .0. 代表後面是陣列，所以要抓取
                if (Str::contains($key, '.0.')) {
                    $dataKey = Str::before($key, '.0.');
                    $needDataKey = Str::after($key, '.0.');
                    $arrayData = data_get($request->all(), $dataKey);

                    foreach ($arrayData as $arrayDataKey => $arrayDataValue) {
                        Arr::set($result, $dataKey . '.' . $arrayDataKey . '.' . $needDataKey, data_get($arrayDataValue, $needDataKey));
                    }
                } else {
                    Arr::set($result, $key, data_get($request->all(), $key));
                }
            }
        } else {
            $result = $request->all();
        }

        try {
            Notification::route('telegram', data_get($webhookReceiver, 'chat.id'))
                ->notify(new WebhookForward($result, $webhookReceiver));

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
