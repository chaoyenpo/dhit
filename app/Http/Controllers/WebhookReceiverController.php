<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebhookReceiver as ResourcesWebhookReceiver;
use App\Models\Bot;
use App\Models\WebhookReceiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Jetstream\RedirectsActions;
use NotificationChannels\Telegram\Telegram;
use ReflectionMethod;

class WebhookReceiverController extends Controller
{
    use RedirectsActions;

    public function show(Request $request)
    {
        $webhookReceivers = WebhookReceiver::with(['bot', 'user'])->whereTeamId($request->user()->currentTeam->id)
            ->whereUserId($request->user()->id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('Webhook/Show', [
            'webhookReceivers' => ResourcesWebhookReceiver::collection($webhookReceivers),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Webhook/Create');
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'bot_token' => ['required', 'string'],
        ])->validateWithBag('botLink');

        try {
            $r = new ReflectionMethod(Telegram::class, 'sendRequest');
            $r->setAccessible(true);
            $response = $r->invoke(new Telegram($request->bot_token), "getMe", []);

            $result = (json_decode($response->getBody(), true) ?? [])['result'];

            $r->invoke(new Telegram($request->bot_token), "setWebhook", [
                'url' => config('receiver.host') . '/api/webhook/telegram',
            ]);

            $bot = Bot::updateOrCreate([
                'token' => $request->bot_token,
            ], [
                'name' => $result['first_name'],
                'username' => $result['username'],
                'meta' => $result,
            ]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['bot_token' => 'Token 無效。' . $th->getMessage()])->errorBag('botLink');
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

    public function relink(Request $request)
    {
        Validator::make($request->all(), [
            'id' => ['required'],
        ])->validateWithBag('botLink');

        $webhookReceiver = WebhookReceiver::with('bot')->find($request->id);

        Cache::put(
            $webhookReceiver->token,
            auth()->user()->id . ' ' . auth()->user()->currentTeam->id . ' ' . $webhookReceiver->bot->id,
            3600
        );

        return back()->with([
            "url" => 'https://t.me/' . $webhookReceiver->bot->username . '?startgroup=' . $webhookReceiver->token,
            'token' => $webhookReceiver->token,
        ]);
    }

    public function edit(Request $request)
    {
        if (!$webhookReceiver = WebhookReceiver::find($request->id)) {
            return redirect()->intended(config('fortify.home'));
        }

        return Inertia::render('Webhook/Edit', [
            'webhookReceiver' => new ResourcesWebhookReceiver($webhookReceiver),
        ]);
    }

    public function update(Request $request, $webhookReceiverId)
    {
        $webhookReceiver = WebhookReceiver::findOrFail($webhookReceiverId);

        Validator::make($request->all(), [
            'dql' => ['required', 'string'],
        ])->validateWithBag('updateWebhookReceiver');

        try {
            $webhookReceiver->forceFill([
                'dql' => json_decode($request->dql),
            ])->save();
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['dql' => 'JSON 格式解析失敗，請確認格式是否正確。'])->errorBag('updateWebhookReceiver');
        }

        return back(303);
    }

    public function destroy(Request $request, $webhookReceiverId)
    {
        $webhookReceiver = WebhookReceiver::findOrFail($webhookReceiverId);

        $webhookReceiver->delete();

        return redirect()->intended(route('webhooks'));
    }
}
