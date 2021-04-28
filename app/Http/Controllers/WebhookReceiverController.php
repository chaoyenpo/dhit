<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebhookReceiver as ResourcesWebhookReceiver;
use App\Models\WebhookReceiver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Jetstream\RedirectsActions;

class WebhookReceiverController extends Controller
{
    use RedirectsActions;

    public function show(Request $request)
    {
        $items = WebhookReceiver::whereTeamId($request->user()->currentTeam->id)
        ->whereUserId($request->user()->id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('Webhook/Show', [
            'webhookReceivers' => ResourcesWebhookReceiver::collection($items),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Webhook/Create');
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

    public function destroy(Request $request, $webhookReceiverId)
    {
        $webhookReceiver = WebhookReceiver::findOrFail($webhookReceiverId);

        // app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        $webhookReceiver->delete();

        return redirect()->intended(route('webhooks'));
    }
}
