<?php

namespace App\Actions\Fortify;

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
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Normal registration (always citizen role)
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        /**
         * 🔹 Atribui automaticamente o role "Cidadão"
         *   IMPORTANTE: certifica-te que o ID 2 corresponde ao role "Cidadão"
         */
        try {
            $user->roles()->sync([2]); // ID do papel "Cidadão"
        } catch (\Exception $e) {
            // Evita crash se o role ainda não existir após fresh migrate
            logger("Falha ao atribuir role cidadão: ".$e->getMessage());
        }

        return $user;
    }
}