<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Password\PasswordController;

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

// user routes

Route::group(['prefix' => 'user'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registeradmin']);
    });
});

//admin routes

Route::group(['prefix' => 'admin'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registeradmin']);
    });
});

//superadmin routes


Route::group(['prefix' => 'superadmin'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registersuperadmin']);
    });
});



//password reset routes

Route::group(['prefix'=> 'password', 'middleware' => 'guest:sanctum'], function() {

    Route::post('sendtoken', [PasswordController::class, 'sendcode']);
    Route::post('resendtoken', [PasswordController::class, 'sendcode']);
    Route::put('reset', [PasswordController::class, 'reset']);

});


