<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Web\Api\ServiceController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\OrderController;
use App\Http\Controllers\Web\Backend\ReviewController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\AllServiceController;
use App\Http\Controllers\Web\Backend\SocialMediaController;
use App\Http\Controllers\Web\Backend\PrivacyPolicyController;
use App\Http\Controllers\Web\Backend\SystemSettingController;

//  Route::middleware(['auth', 'admin'])->group(function () {
//         Route::get('/dashboard', function () {
//                 return view('dashboard');
//             })->name('dashboard');
//         }); 




Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admindashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(AllServiceController::class)->group(function () {
        Route::get('/service/all', 'allService')->name('service.all');
        Route::get('services/create', 'create')->name('service.create');
        Route::post('services/store', 'store')->name('service.store');
        Route::get('services/edit/{id}', 'edit')->name('service.edit');
        Route::put('services/update/{id}', 'update')->name('service.update');
        Route::post('/services/update-status/{id}',  'updateStatus')->name('service.update-status');
        Route::delete('services/delete/{id}', 'destroy')->name('service.destroy');
    });

    //!Route for SystemSettingController
    Route::controller(SystemSettingController::class)->group(function () {
        Route::get('/system-setting', 'index')->name('admin.system.setting');
        Route::post('/system/settings', 'update')->name('admin.system.update');


        Route::get('/system/profile', 'profileindex')->name('profilesetting');
        Route::post('/userprofile', 'profileupdate')->name('userprofile.update');
        Route::post('/password', 'passwordupdate')->name('user.password.update');

        Route::get('/system/mail', 'mailSetting')->name('system.mail.index');
        Route::post('/system/mail', 'mailSettingUpdate')->name('system.mail.update');

        Route::get('/system/stripe', 'stripeindex')->name('stripe.index');
        Route::post('/system/stripe', 'stripestore')->name('stripe.store');


        // Route::get('/system/paypal', 'paypalindex')->name('paypal.index');
        // Route::post('/system/paypal', 'paypalstore')->name('paypal.store');
        //  Route::post('/pro', 'paypalstore')->name('paypal.store');
    });

    // Social Media Module
    Route::controller(SocialMediaController::class)->group(function () {
        Route::get('social-media', 'index')->name('social.media');
        Route::get('social-media/create', 'create')->name('social.create');
        Route::post('social-media/store', 'store')->name('social.media.store');        
        Route::put('social-media/{id}', 'update')->name('social.media.update');
        Route::delete('social-media/{id}', 'destroy')->name('social.media.destroy');
    });

    //!Route for PrivacyPolicyController
    Route::controller(PrivacyPolicyController::class)->group(function () {
        Route::get('/privacy/{slug}/edit', 'editprivacy')->name('privacy.edit');
        Route::put('/privacy/{slug}', 'privacyupdate')->name('privacy.update');

        Route::get('/terms/{slug}/edit', 'editterms')->name('terms.edit');
        Route::put('/terms/{slug}', 'termsupdate')->name('terms.update');
    });



    // Route for FaqController
    Route::controller(FaqController::class)->group(function () {
        Route::get('/cms/faq', 'index')->name('faq.index');
        Route::get('/cms/faq/create', 'create')->name('faq.create');
        Route::post('/cms/faq', 'store')->name('faq.store');
        Route::get('/cms/faq/{slug}/edit', 'edit')->name('faq.edit');
        Route::put('/cms/faq/{slug}', 'update')->name('faq.update');
        Route::post('/faq/toggle-status/{id}', 'toggleStatus')->name('faq.toggleStatus');
        Route::delete('/cms/faq/{slug}', 'destroy')->name('faq.destroy');
    });

    // Route for REview
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/cms/review', 'index')->name('review.index');
        Route::get('/cms/review/create', 'create')->name('review.create');
        Route::post('/cms/review', 'store')->name('review.store');
        Route::get('/cms/review/{slug}/edit', 'edit')->name('review.edit');
        Route::put('/cms/review/{slug}', 'update')->name('review.update');
        Route::post('/review/toggle-status/{id}', 'toggleStatus')->name('review.toggleStatus');
        Route::delete('/cms/review/{slug}', 'destroy')->name('review.destroy');
    });
    // Route for order
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order/all', 'index')->name('order.index');
        Route::put('/order/update-status/{id}', 'updateStatus')->name('order.update-status');


    });
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/user/all', 'userManagement')->name('alluser.user');


    });


});
