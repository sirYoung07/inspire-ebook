<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// welcome Route

Route::get('/', fn()=>response()->json(['status' => true, 'message' => 'Api is up and running']));

// users route

Route::group(['prefix' => 'user'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'loginuser']);
        Route::post('register',[RegisterController::class, 'registeruser']);
        
        
        Route::group(['middleware' => 'auth:user'], function() {
     
              Route::post('logout', [AuthController::class, 'logoutuser']);
              Route::post('verification/send',[VerificationController::class, 'sendMailVerificationCode']);
              Route::post('verification/verify', [VerificationController::class, 'verifyEmail']);
              Route::post('verification/resend', [Controller::class, 'resendcode']);
     
        });

    });
});

