<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use Inertia\Inertia;
use ReflectionMethod;
use App\Models\Domain;
use App\Jobs\ImportExcel;
use App\Models\BotNotify;
use App\Rules\FileNotEmpty;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use NotificationChannels\Telegram\Telegram;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Domain as DomainResource;
use Facades\App\Services\Rayquaza\Rayquaza;

class DomainValidController extends Controller
{
    public function index(Request $request)
    {
        $domains = Domain::query()
            ->whereTeamId($request->user()->currentTeam->id)
            ->search($request['search'])
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->appends([
                'search' => $request['search'],
            ]);

        $bot = BotNotify::whereTeamId($request->user()->currentTeam->id)->first();

        return Inertia::render('Domain/Index', [
            'domains' => DomainResource::collection($domains),
            'bot' => $bot,
        ]);
    }

    public function show(Request $request, $domainId)
    {
        $domain = Domain::findOrFail($domainId);

        return Inertia::render('Domain/Show', [
            'domain' => $domain
        ]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'file' => ['required', 'file', new FileNotEmpty()],
        ])->validateWithBag('uploadDomain');

        $filePath = $request->file('file')->path();
        $newFilePath =  $filePath . '.' . $request->file('file')->getClientOriginalExtension();
        move_uploaded_file($filePath, $newFilePath);

        Rayquaza::import($newFilePath, auth()->user());

        return back()->with('flash', [
            'banner' => '已成功大量上傳網域資訊。'
        ]);
    }

    public function update(Request $request, $domainId)
    {
        $domain = Domain::findOrFail($domainId);

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('domains')->ignore($domainId)],
        ])->validateWithBag('updateDomain');

        $domain->forceFill([
            'name' => $request['name'],
            'domain_expired_at' => $request['domain_expired_at'],
            'certificate_expired_at' => $request['certificate_expired_at'],
            'product' => $request['product'],
            'submit' => $request['_submit'],
            'dns' => $request['dns'],
            'nameservers' => $request['nameservers'],
            'vendor' => $request['vendor'],
            'remark' => $request['remark'],
        ])->save();

        return back(303);
    }

    public function destroy(Request $request)
    {
        Validator::make($request->all(), [
            'selected' => ['required'],
        ])->validateWithBag('deleteDomain');

        Domain::destroy($request->selected);

        return redirect()->intended(route('domains.index'));
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
