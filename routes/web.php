<?php

use App\Http\Controllers\Api\SocialLoginController;
use App\Http\Controllers\Api\StripePaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Web\Backend\DashboardController;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
})->name('home'); */

Route::get('/', [UserDashboardController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// social login route is working
Route::get('/social-login/{provider}', [SocialLoginController::class, 'RedirectToProvider']);
Route::get('/social-login/{provider}/callback', [SocialLoginController::class, 'HandleProviderCallback']);




// this route will run the 'migrate:fresh --seed' command in live server
// It will have to remove after deveopment

Route::get('/run-migrations', function () {
    // Run the 'migrate:fresh --seed' command
    Artisan::call('migrate:fresh --seed');

    // Return a response
    return response()->json([
        'message' => 'Migrations and seed completed successfully!',
        'status' => 'success'
    ]);
});

Route::get('/run-migrations-refresh', function () {
    // Run the 'migrate:fresh --seed' command
    Artisan::call('migrate:refresh --seed');

    // Return a response
    return response()->json([
        'message' => 'Migrations refresh and seed completed successfully!',
        'status' => 'success'
    ]);
});


Route::get('/migrate-database', function () {
    Artisan::call('migrate');

    // Return a response
    return response()->json([
        'message' => 'Migrations completed successfully!',
        'status' => 'success'
    ]);


});



// Run the 'storage:link' command
Route::get('/storage-link', function () {
    Artisan::call('storage:link');

    // return 'Storage link created successfully!';
    return response()->json([
        'message' => 'Storage link created successfully!',
        'status' => 'success'
    ]);
});




 //stripe payment Payment
 Route::controller(StripePaymentController::class)->group(function () {

    Route::get('/payment/success', 'checkoutSuccess')->name('order.success');
    Route::get('/payment/cancle', 'checkoutCancel')->name('order.cancel');
});


require __DIR__.'/auth.php';
require __DIR__.'/backend.php';
require __DIR__.'/masum_backend.php';
