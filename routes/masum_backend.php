<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\CMS\HomePage\HomePageController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\ProductController;
use App\Http\Controllers\Web\Backend\RatingController;

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admindashboard', [DashboardController::class, 'index'])->name('dashboard');

    //!Route for ProductController
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/index', 'index')->name('product.index');
        Route::get('products/create', 'create')->name('product.create');
        Route::post('products/store', 'store')->name('product.store');
        Route::get('products/edit/{id}', 'edit')->name('product.edit');
        Route::put('products/update/{id}', 'update')->name('product.update');
        Route::get('/products/status/{id}',  'status')->name('product.status');
        Route::delete('products/delete/{id}', 'destroy')->name('product.destroy');

        Route::delete('/variant-image/{imageId}', [ProductController::class, 'deleteVariantImage'])->name('delete.variant.image');







    });



    // this route for home page CMS
    Route::controller(HomePageController::class)->group(function () {
        Route::get('/cms/home/header', 'headerBanner')->name('cms.home.header');
        Route::put('/cms/home/header/update', 'headerBannerupdate')->name('cms.home.header.update');
        // Route::delete('/cms/home', 'deleteContentImage')->name('cms.home.delete.image');

        Route::get('/cms/home/about', 'about')->name('cms.home.about');
        Route::patch('/cms/home/about', 'aboutupdate')->name('cms.home.about.update');

        Route::get('/cms/home/contact', 'contact')->name('cms.home.contact');
        Route::patch('/cms/home/contact', 'contactupdate')->name('cms.home.contact.update');


        // Route to view and edit the service banner
        Route::get('/cms/service/banner', 'serviceBanner')->name('cms.service.banner');
        Route::put('/cms/service/update/banner', 'serviceBannerUpdate')->name('service.banner.update');

        // Route to view and edit the product banner
        Route::get('/cms/product/banner', 'productBanner')->name('cms.product.banner');
        Route::put('/cms/product/update/banner', 'productBannerUpdate')->name('product.banner.update');

        // Route to view and edit the cart banner
        Route::get('/cms/cart/banner', 'cartBanner')->name('cms.cart.banner');
        Route::put('/cms/cart/update/banner', 'cartBannerUpdate')->name('cart.banner.update');

    });
});
