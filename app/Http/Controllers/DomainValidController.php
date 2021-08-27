<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use Inertia\Inertia;
use App\Excel\Import;
use ReflectionMethod;
use App\Models\Domain;
use App\Models\BotNotify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\DomainImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use NotificationChannels\Telegram\Telegram;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Domain as DomainResource;

class DomainValidController extends Controller
{
    public function show(Request $request)
    {
        $domains = Domain::whereTeamId($request->user()->currentTeam->id)->paginate(100);
        $bot = BotNotify::whereTeamId($request->user()->currentTeam->id)->first();

        return Inertia::render('Domain/Show', [
            'domains' => DomainResource::collection($domains),
            'bot' => $bot,
        ]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'domains' => ['required', 'file'],
        ])->validateWithBag('uploadDomain');

        Import::import($request->file('domains'));

        return back();
    }

    public function destroy(Request $request)
    {
        Validator::make($request->all(), [
            'selected' => ['required'],
        ])->validateWithBag('deleteDomain');

        Domain::destroy($request->selected);

        return redirect()->intended(route('domains'));
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
}
