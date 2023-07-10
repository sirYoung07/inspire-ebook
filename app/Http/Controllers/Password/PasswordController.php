<?php

namespace App\Http\Controllers\Password;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Password;
use App\Notifications\Notification\PasswordResetNotification;

class PasswordController extends Controller
{
    public function sendcode(Request $request , User $user){
        $request->validate([
            'email' => 'required'
        ]);

        $email = User::where('email', $request->input('email'))->value('email');
        if(!$email){

            return $this->failure(  
                ['error'=> 'the provided email does not exist'], '', self::BAD_REQUEST);
        }
        
        $token = $this->generatecode();

        $user = User::where('email', $request->input('email'))->first();

        $user->notify(new PasswordResetNotification($token));
    
        $email_exist = DB::table('password_reset_tokens')->where('email', $email);
        if($email_exist){
             $email_exist->delete();
        }
        
        DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' =>$token,
                'created_at' => Carbon::now()
            ]);

        return $this->success([
            'info' => 'use the code to reset your password'],'a six-digit verification code has been sent to your mail'
            , self::SUCCESS);
    }


    public function reset(Request $request, User $user){

        $request->validate([
            'token' => 'required',
            'email' => 'required',
            'new_password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()]
        ]);

        $data = DB::table('password_reset_tokens')->where('email', $request->input('email'))->first();

        if(!$data){
            return $this->failure(['error' => 'provided email is not valid']);
        }

        if($data->token != $request->input('token')){
            return $this->failure(['error' => 'provided token is invalid']);

        }

        $token_expired_at = Carbon::now()->addSeconds(300);
        $time =  $token_expired_at->diffInMinutes($data->created_at);
        
        if($time > 5){
            return $this->failure(['error' => 'the submited token has expired'], 'request a new token to reset your password');
        }

        $user = User::where('email', $request->input('email'))->first();

        $user->update([
            'password' => bcrypt($request->input('new_password'))
        ]);
        return $this->success(['message' => 'your password has been resset successfully']);

    }

}
