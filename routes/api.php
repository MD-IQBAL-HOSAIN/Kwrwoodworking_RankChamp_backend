<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ApiCMSController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\SocialLoginController;
use App\Http\Controllers\Api\StripePaymentController;
use App\Http\Controllers\Web\Backend\OrderController;
use App\Http\Controllers\Web\Backend\DashboardController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


//without api middleware
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    // Route::get('all/users', 'index');
    Route::post('/password/forgot', 'forgotPassword');
    Route::post('/password/reset', 'resetPassword');
    Route::post('/password/verify-otp', 'verifyOtp');
    Route::post('/password/resend-otp', 'resendOtp');
});

//All service
Route::controller(ServiceController::class)->group(function () {
    Route::get('services', 'index');
    Route::get('latest/services', 'lastFourService');
    Route::get('services/{id}/details', 'serviceDetails');

    //FAQ
    Route::get('/cms/api/faq', 'faq')->name('faq.faq');
    //Review
    Route::get('/cms/review', 'review');
});

//contact for Admin (Contact Us)
Route::controller(DashboardController::class)->group(function () {
    Route::post('contact/store', 'message')->name('contact.store');
});


// All Product
Route::controller(ApiProductController::class)->group(function () {
    Route::get('products', 'index');
    Route::get('product/show/{id}', 'show');
    Route::get('products/recent-product', 'recentproduct');
});


// All CMS
Route::controller(ApiCMSController::class)->group(function () {

    // it's for home page header
    Route::get('cms/home/api/header', 'headerBanner')->name('cms.home.header');

    // it's for home page about
    Route::get('cms/home/api/about', 'about');


    // it's for home page contact
    Route::get('cms/home/api/contact', 'contact')->name('cms.home.contact');

    // it's for system-setting, profile-setting, social-media, privacy-policy, terms and condition
    Route::get('cms/system/api/setting', 'systemSetting')->name('system.setting');
    Route::get('cms/system/api/profileindex', 'profileindex')->name('system.profileindex');
    Route::get('cms/system/api/social-media', 'social')->name('system.social');
    Route::get('cms/system/api/{id}/privacy', 'privacy')->name('system.privacy');


    //Product , service, cart Banner
    Route::get('cms/product/{id}/banner', 'allBanner');
});

// In routes/api.php
Route::post('/cart/add', [CartController::class, 'addToCart']);




//with middlware api
Route::middleware('auth:api')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::delete('/delete-account', 'deleteAccount');
        Route::post('/profile/update/user', 'ProfileUpdate');
        Route::post('/profile/update/password', 'ChangePassword');
    });


    Route::controller(CartController::class)->group(function () {
        Route::get('/cart/all-product', 'allCartIteam');
        Route::post('/cart/add', 'addToCart');
        Route::post('/cart/update/qty/plus', 'quantityUpdate');
        Route::post('/cart/update/qty/minus', 'quantityMinus');
        Route::post('/checkout/order', 'checkout');

        //Products Remove from cart
        Route::post('/cart/remove', 'removeFromCart');
    });
});







// // this is for social login working
Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
// Route::get('/social-login/{provider}/callback', [SocialLoginController::class, 'HandleProviderCallback']);



// after session error
// Route::middleware('web')->get('/social-login/{provider}', [SocialLoginController::class, 'RedirectToProvider']);
// Route::middleware('web')->get('/social-login/{provider}/callback', [SocialLoginController::class, 'HandleProviderCallback']);



Route::get('/composer-update', function () {
    // Ensure the user is authenticated (optional)
    if (!auth()->check()) {
        return response('Unauthorized', 403);
    }

    // Execute composer update
    $output = shell_exec('composer update 2>&1');

    // Return the output as JSON or a plain text response
    return response()->json([
        'message' => 'Composer update completed!',
        'output' => $output
    ]);
});
