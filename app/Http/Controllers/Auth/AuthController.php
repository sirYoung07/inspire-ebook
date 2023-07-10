<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Auth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    
    // authenticate user for practise


    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
           ]); 

           $check = $request->only('email', 'password');
            
        if(Auth::guard('web')->attempt($check)){
            $user = Auth::guard('web')->user();
           return $this->success(['user' => $user, 'token'=> $user->createToken('rentee_token')->plainTextToken]);
        
        }
        

        return $this->failure([
            'error' => 'invalid email or password'
        ], 'login unsuccessful', self::UNAUTHORIZED);
    
    }


    // TO DO
    public function loginuser(Request $request){
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
           ]); 

           $check = $request->only('email', 'password');
            
        if(Auth::guard('rentee')->attempt($check)){
            $user = Auth::guard('rentee')->user();
           return $this->success(['user' => $user, 'token'=> $user->createToken('rentee_token')->plainTextToken]);
        
        }
        

        return $this->failure([
            'error' => 'invalid email or password'
        ], 'login unsuccessful', self::UNAUTHORIZED);
    
    }


    public function loginadmin(Request $request){
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
           ]); 

           $check = $request->only('email', 'password');
            
        if(Auth::guard('admin')->attempt($check)){
           $user = Auth::guard('admin')->user();
           return $this->success(['admin' => $user, 'token'=> $user->createToken('admin_token')->plainTextToken]);
        
        }
        

        return $this->failure([
            'error' => 'invalid email or password'
        ], 'login unsuccessful', self::UNAUTHORIZED);
    
    }
}