<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginuser(Request $request){
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $login = Auth::guard('user')->attempt($formFields);
        
        if(!$login){
            return $this->failure([
                'error' => 'invalid email or password'
            ], 'login unsuccessful', self::UNAUTHORIZED);
        }
        
        $token = $request->user()->createToken('auth_token')->plainTextToken;
        $user = Auth::guard('user')->user();

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'user logged in successfully', self::SUCCESS);

    }       
    
}
