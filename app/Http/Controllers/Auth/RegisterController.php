<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    public function registeruser(Request $request, User $user)
    {
        $rentee = $this->register($request,  $user, 0);
        if (!$rentee) {
            return $this->failure([], 'Registration fail', self::SERVER_ERROR);
        } 
        return $this->success([
            'user'=> $rentee,
            'token' => $rentee->createToken('auth_token')->plainTextToken,
            'token type' => 'Bearer',
        ],  'Rentee Registration successful.', self::CREATED);
    }


    public function registeradmin(Request $request, User $user)
    {
        $renter = $this->register($request, $user, 0);
        if (!$renter) {
            return $this->failure([], 'Registration fail', self::SERVER_ERROR);
        } 
        return $this->success([
            'user'=> $renter,
            'token' => $renter->createToken('auth_token')->plainTextToken,
            'token type' => 'Bearer',
        ],  'Renter Registration successful.', self::CREATED);
    }


    public function registersuperadmin(Request $request, User $user)
    {
        $superadmin = $this->register($request, $user, 2 );
        if (!$superadmin) {
            return $this->failure([], 'Registration fail', self::SERVER_ERROR);
        } 
        return $this->success([
            'user'=> $superadmin,
            'token' => $superadmin->createToken('auth_token')->plainTextToken,
            'token type' => 'Bearer',
        ],  'Superadmin Registration successful.', self::CREATED);
    }

    public function register(Request $request, User $user, int $role)
    {

        $formFields = $request->validate([
            'first_name' => ['required', 'string','min:3','max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()],
        ]);

        $password = $formFields['password'] = Hash::make($formFields['password']);
        $user = User::create([
            'first_name' => $formFields['first_name'],
            'last_name' => $formFields['last_name'],
            'email' => $formFields['email'],
            'password' => $password,
            'role' => $role
        ]);
        return $user;
    }

}
    