<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => []], function () {
    /**
     * Login a user
     * @package User
     */
    Route::post('/users/login', 'Auth\LoginController@login');

    /**
     * Validate user email
     * @package User
     */
    Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');

    /**
     * Resend mail confirmation notification
     * @package User
     */
    Route::post('/email/resend/', 'Auth\VerificationController@resend');

    /**
     * Register a new user
     * @package User
     */
    Route::post('/users/register', 'Auth\RegisterController@register');
    Route::post('/users/password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/users/password/reset', 'Auth\ResetPasswordController@reset');
    /**
     * Stripe Webhook
     */
    Route::post('stripe/webhook/{currency}', 'StripeController@handleWebhook');

    /**
     * Endpoint called by Stripe when a new partner connects its account to Zen Office
     */
    Route::get('stripe/connect/partner/{currency}', 'StripeController@connectStripePartner');
});

Route::group(['middleware' => ['auth:api', 'activated']], function () {

    /**
     * Specifics actions for connected user
     * @package me
     */
    Route::prefix('me')->group(function () {

        /**
         * Get connected user
         * @package User
         */
        Route::middleware('throttle:200,1')->group(function () {
            Route::get('', function (Request $request) {
                return new UserResource($request->user());
            });
        });

        /**
         * Get connected user
         * @package User
         */
        Route::post('/logout', 'Auth\LoginController@logout');

        /**
         * Set connected user state
         * @package User
         */
        Route::post('/state', 'UserController@saveState');

        /**
         * update user
         * @package User
         */
        Route::post('', 'UserController@save');

        Route::get('/locales', 'LocaleController@index');

        Route::prefix('bookings')->group(function () {
            Route::get('', 'BookingController@index');
            Route::post('', 'BookingController@save');
            Route::post('/{booking}', 'BookingController@save');
            Route::get('/{booking}', 'BookingController@get');
        });
    });

    Route::prefix('products')->group(function () {
        /**
         * get products list
         */
        Route::get('', 'ProductController@index');
        /**
         * Get a product
         * @package Product
         */
        Route::get('/{product}', 'ProductController@get');
        /**
         * Toggle product in Favorites
         * @package Product
         */
        Route::post('/{product}/favorites', 'ProductController@addToFavorites');
        /**
         * get products list
         */
        Route::get('', 'ProductController@index');

        Route::post('/{product}/payment', 'ProductController@payment');

        Route::get('/{product}/linked', 'ProductController@linked');

        Route::post('/{product}/rating', 'ProductController@rating');
    });

    Route::prefix('demand')->group(function () {
        Route::post('', 'DemandController@send');
    });

    Route::prefix('fm-services')->group(function () {
        Route::post('', 'FmServicesController@send');
    });

    Route::prefix('services')->group(function () {
        /**
         * get services list
         */
        Route::get('', 'ServiceController@index');
        /**
         * pick a service
         */
        Route::get('/{service}', 'ServiceController@get');
        /**
         * Toggle service in Favorites
         * @package Product
         */
        Route::post('/{service}/favorites', 'ServiceController@addToFavorites');
    });

    Route::prefix('sites')->group(function () {
        /**
         * get sites
         * @package Site
         */
        Route::get('', 'SiteController@index');
    });

    Route::prefix('searches')->group(function () {
        Route::get('', 'SearchLogController@index');
    });
});
