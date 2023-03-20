<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

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

// user

Route::group(['prefix' => 'user'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registeradmin']);
    });
});

//admin

Route::group(['prefix' => 'admin'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registeradmin']);
    });
});

//superadmin


Route::group(['prefix' => 'superadmin'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register',[RegisterController::class, 'registersuperadmin']);
    });
});

