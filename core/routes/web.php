<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


Route::controller('CronController')->prefix('cron')->name('cron.')->group(function () {
    Route::get('sms', 'sendSMS')->name('send.sms');
    Route::get('resend/sms', 'resendSMS')->name('resend.sms');
});

Route::controller('SiteController')->group(function () {
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
});

Route::get('app/to/web', 'Admin\Auth\LoginController@appToWeb')->name('app.to.web');


Route::get('/', function () {
    return to_route('admin.dashboard');
})->name('home');
