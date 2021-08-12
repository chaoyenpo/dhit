<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as ResourcesUser;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::paginate(100);

        return Inertia::render('User/Index', [
            'users' => ResourcesUser::collection($users),
        ]);
    }


    public function show(Request $request, $teamId)
    {
        // $team = Jetstream::newTeamModel()->findOrFail($teamId);

        // Gate::authorize('view', $team);

        // return Jetstream::inertia()->render($request, 'Teams/Show', [
        //     'team' => $team->load('owner', 'users', 'teamInvitations'),
        //     'availableRoles' => array_values(Jetstream::$roles),
        //     'availablePermissions' => Jetstream::$permissions,
        //     'defaultPermissions' => Jetstream::$defaultPermissions,
        //     'permissions' => [
        //         'canAddTeamMembers' => Gate::check('addTeamMember', $team),
        //         'canDeleteTeam' => Gate::check('delete', $team),
        //         'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
        //         'canUpdateTeam' => Gate::check('update', $team),
        //     ],
        // ]);
    }

    public function create(Request $request)
    {
        Gate::authorize('create', Jetstream::newTeamModel());

        return Inertia::render('User/Create');
    }

    public function store(Request $request, CreatesNewUsers $creator)
    {
        event(new Registered($creator->createMember([
            'email' => $request->email,
            'name' => $request->name,
            'organization' => $request->organization,
        ])));

        return back()->with('flash', [
            'banner' => '使用者 ' . $request->name . ' 新增成功！'
        ]);
    }
}
