<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Laravel\Jetstream\RedirectsActions;
use App\Http\Resources\Team as ResourcesTeam;
use Laravel\Jetstream\Contracts\CreatesTeams;

class TeamController extends Controller
{
    use RedirectsActions;

    public function index(Request $request)
    {
        $teams = Team::with('users')->where('personal_team', false)->paginate(100);

        return Inertia::render('Teams/Index', [
            'teams' => ResourcesTeam::collection($teams),
        ]);
    }

    public function store(Request $request)
    {
        $creator = app(CreatesTeams::class);

        $creator->create($request->user(), $request->all());

        return $this->redirectPath($creator);
    }
}
