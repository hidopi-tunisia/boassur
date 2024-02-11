<?php

namespace App\Actions\Fortify;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'password' => $this->passwordRules(),
        ];

        if (request()->isAdmin()) {
            $rules['email'][] = Rule::unique(Admin::class);
        } else {
            $rules['email'][] = Rule::unique(User::class);
        }

        Validator::make($input, $rules)->validate();

        $inputs = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ];

        if (request()->isAdmin()) {
            return Admin::create($inputs);
        }

        return User::create($inputs);
    }
}
