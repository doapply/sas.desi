<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->name('api.')->prefix('v1')->group(function () {

    Route::namespace('Admin')->group(function () {

        Route::namespace('Auth')->group(function () {
            Route::post('login/with/qr/code', "LoginController@loginWithQrCode");
        });

        Route::middleware(["auth.api:sanctum"])->group(function () {

            Route::post('logout', "Auth\LoginController@logout");

            ///===========start the sms api route============
            Route::prefix('message')->controller("SmsController")->group(function () {
                Route::post('/received', "received");
                Route::post('/update/{id}', "update");
            });

        });

        ///=============API implementation route ================
        Route::controller('SmsController')->prefix('sms')->middleware('apikey')->group(function () {
            Route::post('/send', 'send')->name('sms.send');
            Route::get('/send/get', 'sendViaGet')->name('sms.send.get');
        });

        //======== start the pusher  route ===============
        Route::post('pusher/auth', "PusherController@authenticationApp")->middleware('auth.api:sanctum');
        Route::post('pusher/auth/{socketId}/{channelName}', "PusherController@authentication");
        Route::get('un/authenticate', "HomeController@unAuthenticated")->name('un.authenticate');
    });
});
