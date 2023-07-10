<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Password\PasswordController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Verification\EmailVerificationController;
use App\Models\User;

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



// to do , using guard to authenticate

Route::get('/', fn()=>response()->json(['status' => true, 'message' => 'Api is up and running']));

// user (readers routes )

Route::group(['prefix' => 'user'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registeruser']);
        Route::post('login',[AuthController::class, 'loginuser']);

        // for practiser
        Route::post('login',[AuthController::class, 'authenticate']);
    });

    Route::group(['middleware' => 'auth:sanctum'], function(){
        
        Route::get('/authenticated', [UserController::class, 'getauth']);
        Route::put('/manageprofile', [UserController::class, 'manage_profile']);
        Route::post('/rent_book/{id}', [UserController::class, 'rent_book']);
        Route::get('/available_books', [UserController::class, 'available_books']);
        Route::get('/rented_books', [UserController::class, 'get_rented_books']);
        Route::get('/rented_book/{id}', [UserController::class, 'get_rented_book_detail']);
        Route::post('/extend_book_rent/{id}', [UserController::class, 'extend_rent_duration']);
    });


    Route::group(['prefix' => 'verification'], function(){
        Route::post('sendcode',[EmailVerificationController::class, 'sendcode']);
        Route::post('verify',[EmailVerificationController::class, 'verify']);
    });

    
});



//admin routes

Route::group(['prefix' => 'admin'], function(){
    
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registeradmin']);
        Route::post('login',[AuthController::class, 'loginadmin']);
    });
    
    
    Route::group(['prefix' => 'bookmangement', 'middleware' => 'auth:sanctum' ], function(){
        Route::post('create', [AdminController::class, 'createbook']);
        Route::get('view', [AdminController::class, 'viewbook']);
        Route::get('view/{id}', [AdminController::class, 'single_book']);
        Route::post('update/{id}', [AdminController::class, 'update']); 
        Route::post('ban/{id}'      , [AdminController::class, 'ban']); //ban
        Route::post('restore/{id}', [AdminController::class, 'restore']); //unban
        Route::post('delete/{id}', [AdminController::class, 'delete']); 
        Route::post('change_book_status/{id}', [AdminController::class, 'change_book_status']);
    });
    
});

//superadmin routes


Route::group(['prefix' => 'superadmin'], function(){
    
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registersuperadmin']);
        Route::post('login',[AuthController::class, 'loginsuperadmin']);
    });
});



//password reset routes

Route::group(['prefix'=> 'password', 'middleware' => 'guest:sanctum'], function() {
    Route::post('sendtoken', [PasswordController::class, 'sendcode']);
    Route::post('resendtoken', [PasswordController::class, 'sendcode']);
    Route::put('reset', [PasswordController::class, 'reset']);

});

//payment 

Route::group(['prefix' => 'payment'], function(){

    Route::group(['middleware' => 'auth:sanctum'], function(){
    
        Route::post('/initiate_payment', [PaymentController::class, 'make_payment']);
        
    });
    Route::get('/pay/callback', [PaymentController::class, 'payment_callback'])->name('pay.callback');


});    









