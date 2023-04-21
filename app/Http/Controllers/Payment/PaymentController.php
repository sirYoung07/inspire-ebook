<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function initiatepayment(Request $request){
        $request->validate([
            'email' => 'required',
            'amount' => 'required'
        ]);
        $paystack = new Paystack;
        try{
            $paystack->makePaymentRequest([
                
                'email' => $request->email,
                'amount' => $request->amount
            ]);
    
            return $this->success(
            ['authorization_url' => $paystack->getAuthorizationUrl()], 'Use the authorization url below to make payment');
        

        }catch(\Exception $e){
            return $this->failure(['error' => $e->getMessage()]);
        }
    }

    public function handlePaymentCallback(Request $request)
{
    $paystack = new Paystack();
    $paymentDetails = $paystack->getPaymentData();
    if($paymentDetails){

        return $paymentDetails;
    }
    // if ($paymentDetails['status'] === 'success') {
    //     return $paymentDetails;
        
    // }
    return $this->failure(['error'=> 'payment failed'], 'An error occured');
}

        
    
}
