<?php

namespace App\Http\Controllers\Api\Webhook;

use SimpleXMLElement;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebhookReceiver;
use App\Http\Controllers\Controller;
use App\Notifications\WebhookForward;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Notification;

class ForwardController extends Controller
{
    private function array_to_xml($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; //dealing with <0/>..<n/> issues
                }
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public function receive(Request $request, $token)
    {
        if (!$webhookReceiver = WebhookReceiver::whereToken($token)->first()) {
            return response()->json([
                'ok' => true,
                'result' => false,
                'description' => '無效 Token'
            ]);
        }

        $properties = tmpfile();
        fwrite($properties, yaml_emit($request->all()));

        $template = tmpfile();
        fwrite($template, $webhookReceiver->jmte);

        $process = new Process([
            'java',
            '-jar',
            storage_path('jmte.jar'),
            stream_get_meta_data($template)['uri'],
            stream_get_meta_data($properties)['uri'],
        ]);
        $process->run();
        $result = $process->getOutput();

        fclose($template);
        fclose($properties);

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
