<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'nik' => ['required', 'digits:16', 'unique:peserta,nik'],
            'foto_ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $user = User::create(['name' => $input['name'], 'username' => $input['nik'], 'password' => $input['password'], 'role' => 'peserta']);
            $user->peserta()->create(['nama' => $input['name'], 'no_hp' => $input['no_hp'], 'nik' => $input['nik'], 'foto_ktp' => $input['foto_ktp']->store('dokumen/peserta', 'local')]);

            return $user;
        });
    }
}
