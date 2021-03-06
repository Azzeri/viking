<?php

namespace App\Actions\Fortify;

use App\Models\Privilege;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:32'],
            'surname' => ['required', 'string', 'min:3', 'max:32'],
            'nickname' => ['nullable', 'string', 'min:3', 'max:32'],
            'role' => ['required', 'string', 'min:3', 'max:64',],
            'date_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email:filter', 'max:64', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (Auth::user()->privilege_id != Privilege::IS_ADMIN && $input['role'] != Auth::user()->role)
            return (403);

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'surname' => $input['surname'],
                'nickname' => $input['nickname'],
                'role' => $input['role'],
                'date_birth' => $input['date_birth'],
                'description' => $input['description'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'nickname' => $input['nickname'],
            'role' => $input['role'],
            'date_birth' => $input['date_birth'],
            'description' => $input['description'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
