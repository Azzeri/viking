<?php

namespace App\Actions\Fortify;

use App\Models\Privilege;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:32', 'alpha_dash'],
            'surname' => ['required', 'string', 'min:3', 'max:32', 'alpha_dash'],
            'nickname' => ['nullable', 'string', 'min:3', 'max:32', 'alpha_dash'],
            'date_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->setAttributeNames([
            'name' => 'imię',
            'surname' => 'nazwisko',
            'nickname' => 'nick',
            'date_birth' => 'data urodzenia',
            'password' => 'hasło',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'nickname' => $input['nickname'],
            'date_birth' => $input['date_birth'],
            'privilege_id' => Privilege::IS_USER,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
