<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use App\Models\BotNotify;
use Illuminate\Http\Request;
use App\Http\Resources\Domain as ResourcesDomain;

class AlertController extends Controller
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

        return Inertia::render('Alert/Index', [
            'domains' => ResourcesDomain::collection($domains),
            'bot' => $bot,
        ]);
    }
}
