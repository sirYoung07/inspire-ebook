<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // login
    // not yet fixed with different guards 
    
    public function loginuser(Request $request){
        $user = $this->login($request, 'api');

        $token = $user->createToken('auth-token')->plainTextToken;
        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'user logged in successfully', self::SUCCESS);
        
    }

    public function loginadmin(Request $request){
        $admin = $this->login($request, 'admin');

        $token = $admin->createToken('auth-token')->plainTextToken;
        return $this->success([
            'user' => $admin,
            'token' => $token,
        ], 'admin logged in successfully', self::SUCCESS);

    }

    public function loginsuperadmin(Request $request){
        $superadmin = $this->login($request, 'superadmin');

        $token = $superadmin->createToken('auth-token')->plainTextToken;
        return $this->success([
            'user' => $superadmin,
            'token' => $token,
        ], 'superadmin logged in successfully', self::SUCCESS);

    }


    public function login(Request $request, string $guard){
       $formFields = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
       ]); 

       if(!(Auth::guard($guard)->attempt($formFields))){
        return $this->failure([
            'error' => 'invalid email or password'
        ], 'login unsuccessful', self::UNAUTHORIZED);

       }
        
        $user = Auth::guard($guard)->user();
        return $user;
       
    }
    // authenticate user for practise
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
           ]); 
    
        if(Auth::attempt($formFields)){
            $user = Auth::guard()->user();
            return $user;
          //  return 'yes';
        }
        

        return $this->failure([
            'error' => 'invalid email or password'
        ], 'login unsuccessful', self::UNAUTHORIZED);
    
    }
}