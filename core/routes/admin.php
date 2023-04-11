<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->controller('LoginController')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout');

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');
        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

    });


    Route::controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('general-setting', 'index')->name('setting.index');
        Route::post('general-setting', 'update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');
    });



    // Plugin
    Route::controller('ExtensionController')->group(function () {
        Route::get('extensions', 'index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'update')->name('extensions.update');
        Route::post('extensions/status/{id}', 'status')->name('extensions.status');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
    });


    // SEO

    //===============start the contact route ===========
    Route::prefix('contact')->controller("ContactController")->group(function () {
        Route::get('/index', 'index')->name('contact.index');
        Route::post('/store', 'save')->name('contact.store');
        Route::post('/update/{id}', 'save')->name('contact.update');
        Route::post('/status/{id}', 'status')->name('contact.status');
        Route::post('/import', 'importContact')->name('contact.import');
        Route::post('/export', 'exportContact')->name('contact.export');
        Route::get('contact/search', "contactSearch")->name('contact.search');
    });

    //=============== Start the group route ===========
    Route::name('group.')->prefix('group')->controller("GroupController")->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'saveGroup')->name('store');
        Route::get('banned', 'banned')->name('banned');
        Route::post('update/{id}', 'saveGroup')->name('update');
        Route::post('/status/{id}', 'status')->name('status');
        Route::post('delete/contact/{id}', 'deleteContactFromGroup')->name('delete.contact');
        Route::get('contact/view/{id}', 'viewGroupContact')->name('contact.view');
        Route::post('save/contact/{groupId}', 'contactSaveToGroup')->name('to.contact.save');
        Route::post('import/contact/{groupId}', 'importContactToGroup')->name('import.contact');
    });

    Route::controller('BatchController')->name('batch.')->group(function () {
        Route::get('email', 'emailBatch')->name('email');
        Route::get('sms', 'smsBatch')->name('sms');
    });

    Route::prefix('device')->controller("DeviceController")->name('device.')->group(function () {
        Route::get('index', 'index')->name('index');
    });

    Route::prefix('sms')->controller("SmsController")->name('sms.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('send', 'send')->name('send');
        Route::post('send', 'sendSMS')->name('send');
    });

    Route::controller('DeveloperController')->prefix('developer')->name('developer.')->group(function () {
        Route::get('api/docs', 'apiDocs')->name('api.docs');
        Route::post('regenerate/api/key', 'regenerateApiKey')->name('regenerate.api.key');
    });

    //====start the template route ===========
    Route::prefix('template')->controller("TemplateController")->name('template.')->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('/store', 'save')->name('store');
        Route::post('/update/{id}', 'save')->name('update');
        Route::post('/status/{id}', 'status')->name('status');
    });

    Route::controller('BatchController')->name('batch.')->group(function () {
        Route::get('index', 'smsBatch')->name('index');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global', 'global')->name('global');
        Route::post('global/update', 'globalUpdate')->name('global.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');
    });
});
