<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Bot;
use Inertia\Inertia;
use ReflectionMethod;
use App\Models\Domain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use NotificationChannels\Telegram\Telegram;
use Illuminate\Validation\ValidationException;

class DomainValidController extends Controller
{
    public function show(Request $request)
    {
        $domains = Domain::whereTeamId($request->user()->currentTeam->id)->get();

        return Inertia::render('Domain/Show', [
            'domains' => $domains,
        ]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'domains' => ['required', 'file'],
        ])->validateWithBag('uploadDomain');

        $data = $this->csv_to_array($request->file('domains'));

        foreach ($data as $domain) {
            Domain::updateOrCreate([
                'name' => $domain['name']
            ], [
                'team_id' => auth()->user()->currentTeam->id,
                'tag' => $domain['tag'],
                'expired_at' => $domain['expired_at'],
                'remark' => $domain['remark'],
            ]);
        }

        return back();
    }

    public function link(Request $request)
    {
        try {
            $botToken = config('services.telegram-bot-api.token');
            $r = new ReflectionMethod(Telegram::class, 'sendRequest');
            $r->setAccessible(true);
            $response = $r->invoke(new Telegram($botToken), "getMe", []);

            $result = (json_decode($response->getBody(), true) ?? [])['result'];

            $r->invoke(new Telegram($botToken), "setWebhook", [
                'url' => config('receiver.host') . '/api/webhook/notify/telegram',
            ]);

            $bot = Bot::updateOrCreate([
                'token' => $botToken,
                'type' => Bot::TYPE_NOTIFY,
                'team_id' => auth()->user()->currentTeam->id,
            ], [
                'name' => $result['first_name'],
                'username' => $result['username'],
                'meta' => $result,
            ]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['bot_token' => 'Telegram 發生異常，請通知系統管理員。' . $th->getMessage()])->errorBag('botLink');
        }

        $token = Str::random(32);
        Cache::put(
            $token,
            auth()->user()->id . ' ' . auth()->user()->currentTeam->id . ' ' . $bot->id,
            3600
        );

        return back()->with([
            'url' => 'https://t.me/' . $result['username'] . '?startgroup=' . $token,
            'token' => $token,
        ]);
    }

    private function csv_to_array($filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        if ($delimiter == ',') {
            $csv = array_map('str_getcsv', file($filename));
        } else {
            $lines = file($filename);
            $line_num = count($lines);
            $dm = []; // $delimiter

            $csv = array_map('str_getcsv', $lines, array_pad($dm, $line_num, $delimiter));
        }

        array_walk($csv, function (&$row) use ($csv) {
            $row = array_combine($csv[0], $row);
        });

        array_shift($csv); // 移掉第一行的標題陣列

        return $csv;
    }
}
