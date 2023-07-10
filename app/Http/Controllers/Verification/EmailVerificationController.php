<?php

namespace App\Http\Controllers\Verification;

use Carbon\Carbon;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Notification\EmailVerificationNotification;

class EmailVerificationController extends Controller
{
    public function verify(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email']
        ]);

        $data = Code::where('token', $request->token)->where('email', $request->email)->first();

        if(!$data){
            return $this->failure(['error' => 'the submitted is token invalid'], '', self::VALIDATION_ERROR);
      
        }
        
        $expires_at = Carbon::parse($data->expires_at);
        if(now() > $expires_at){
            return $this->failure(['error' => 'the submitted token has expired'], 'please request a new verification code', self::VALIDATION_ERROR );
        }

         $user = User::where('email', $request->email)->first();
    
        if($user->hasVerifiedEmail()){
            return $this->success(['messaage' => 'eamil has already been verified']);;
        }
        
        $user->markEmailAsVerified();

        return $this->success(['message' => 'Email verification successful'], '', self::SUCCESS);
    
    }



    public function sendcode (Request $request){
        $token = $this->generatecode();
        $request->validate([
            'email' => ['required', 'email']
        ]);
        

        $user = User::where('email', $request->email)->first();
        
        if(!$user){
            return $this->failure(['message' => 'please input a correct email']);;
        }
        $token_exists = Code::where('email', $request->email)->first();
        if($token_exists){
            $token_exists->delete();
        }
        
        $user->codes()->create([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(10)
        ]);
        $user->notify(new EmailVerificationNotification($token));

        return $this->success([
            'info' => 'use the code to verify your email'],
            'a six-digit verification code has been sent to your mail'
            , self::SUCCESS
        );


    }

    
}
