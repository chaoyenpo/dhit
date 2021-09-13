<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\Company;
use App\Models\InvitationCode;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company_name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'exists:invitation_codes'],
        ])->validate();

        $invitationCode = InvitationCode::whereCode($input['code'])->first();

        return DB::transaction(function () use ($input, $invitationCode) {
            $company = Company::create([
                'name' => $input['company_name'],
            ]);

            return tap($company->users()->create([
                'name' => $input['name'],
                'email' => $input['email'],
                'root_user' => true,
            ]), function (User $user) use ($invitationCode) {
                $user->assignRole('super_admin');
                $this->createTeam($user);
                $invitationCode->delete();
            });
        });
    }

    public function createMember(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(auth()->user()->company->users()->create([
                'name' => $input['name'],
                'email' => $input['email'],
            ]), function (User $user) use ($input) {
                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => "無專案",
            'personal_team' => true,
        ]));
    }
}
