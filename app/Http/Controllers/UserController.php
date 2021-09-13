<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as ResourcesUser;
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


    public function show(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        return Inertia::render('User/Show', [
            'selectedUser' => $user,
        ]);
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
            'code' => $request->code,
        ])));

        return back()->with('flash', [
            'banner' => '使用者 ' . $request->name . ' 新增成功！'
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        Validator::make($request->all(), [
            'permissions' => ['array'],
        ])->validateWithBag('updateTeamName');

        $user->syncRoles($request['permissions']);

        return back(303);
    }
}
