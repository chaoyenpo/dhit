<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Webhook/Show');
    }

    public function create(Request $request)
    {
        return Inertia::render('Webhook/Create');
    }

    public function store(Request $request)
    {
        Cache::put($token = Str::random(32), auth()->user()->id, 3600);

        // 產生一個連結到前端的

        // $creator = app(CreatesTeams::class);

        // $creator->create($request->user(), $request->all());

        // return Inertia::render('Event/Show', [
        //     'event' => $event->only('id', 'title', 'start_date', 'description'),
        // ]);

        return $this->redirectPath((object) [
            "url" => 'https://t.me/fishsiribot?startgroup=' . $token,
        ]);
    }
}
