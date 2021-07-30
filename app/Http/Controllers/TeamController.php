<?php

namespace App\Http\Controllers;

use App\Http\Resources\Team as ResourcesTeam;
use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::where('personal_team', false)->paginate(100);

        return Inertia::render('Teams/Index', [
            'teams' => ResourcesTeam::collection($teams),
        ]);
    }
}
