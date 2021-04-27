<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebhookRecevier as ResourcesWebhookRecevier;
use App\Models\WebhookRecevier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\RedirectsActions;

class WebhookRecevierController extends Controller
{
    use RedirectsActions;

    public function show(Request $request)
    {
        $items = WebhookRecevier::whereTeamId($request->user()->currentTeam->id)
        ->whereUserId($request->user()->id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('Webhook/Show', [
            'webhookReceviers' => ResourcesWebhookRecevier::collection($items),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Webhook/Create');
    }
}
