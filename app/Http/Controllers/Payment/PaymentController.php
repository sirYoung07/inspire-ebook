<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Traits\userTrait;
use Illuminate\Support\Js;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Support\Facades\Redirect;
use PHPUnit\Util\ThrowableToStringMapper;

class PaymentController extends Controller

{
    use userTrait;

    //Topping users wallet via paystack

    public function make_payment(Request $request){


        $input = $request->validate([
            'amount' => 'required',
            'email' => 'required'
        ]);
        
        $user = $this->getauth();
        $input_email = $request->input('email');

        if($user->email !== $input_email){
            return $this->failure(['error' => 'email does not match'], 'wrong email input');
        }

        $amount = $input['amount'] * 100;
        $callback_url = route('pay.callback');

        $formField = ['email' => $input['email'], 'amount' => $amount, 'callback_url' => $callback_url];

        $pay = json_decode($this->initiate_payment($formField));

        if(!$pay){
            return $this->failure(['error' => 'something went wrong'], 'An error occurred',);
        }   

        return $this->success(['details'=> $pay], 'Use the authorization_url provided below to make payment');

    }



    public function payment_callback(){  

        $details = json_decode($this->verify_payment(request('reference')));

        if(!$details){
            return $this->failure([], 'something went wrong');
        }
        
        if($details->data->status === "success"){
            
            $amount =  ($details->data->amount / 100);
            $customer_email = $details->data->customer->email;
            $user = User::where('email', $customer_email)->first();
            $inital_balance = intval($user->wallet_balance);
            $user->wallet_balance = $amount + $inital_balance;

            $user->save();

            return $this->success(['details' => $details], $amount.' naira has been succcessfully added to your wallet');
            
        }

    }


   




    public function initiate_payment($formField){
    
        $url = "https://api.paystack.co/transaction/initialize";

 
        $fields_string = http_build_query($formField);

        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
            "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        return $result;
        
    }





    public function verify_payment($reference){
        
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
            "Cache-Control: no-cache",
            ),
        ));
    
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        if ($err) {
            return $err;
        } else {
            return $response;
        }

    }

}