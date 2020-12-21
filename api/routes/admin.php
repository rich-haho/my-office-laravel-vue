<?php

use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Guest routes
 */
Route::group(['middleware' => []], function () {
    /**
     * Login a public user
     * @package User
     */
    Route::post('/users/login', 'Admin\Auth\LoginController@login');

    /**
     * Register a new user
     * @package User
     */
    Route::post('/users/password', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/users/password/reset', 'Admin\Auth\ResetPasswordController@reset');
});


/**
 * Private routes
 */
Route::group(['middleware' => ['auth:admin']], function () {
    /**
     * Get connected user
     * @package User
     */
    Route::get('/me', function (Request $request) {
        return new AdminResource($request->user());
    });

    /**
     * Get connected user
     * @package User
     */
    Route::post('/me/logout', 'Admin\Auth\LoginController@logout');

    Route::prefix('currencies')->group(function () {
        /**
         * get currency list
         */
        Route::get('', 'CurrencyController@index');
    });

    Route::group(['middleware' => ['permission:manage clients']], function () {
        Route::prefix('clients')->group(function () {
            /**
             * get client list
             */
            Route::get('', 'ClientController@index');
            /**
             * Update client
             */
            Route::post('/{client}', 'ClientController@save');

            /**
             * Create client
             */
            Route::post('', 'ClientController@save');

            /**
             * Delete client
             */
            Route::delete('/{client}', 'ClientController@delete');

            /**
             * pick a client
             */
            Route::get('/{client}', 'ClientController@get');


            Route::prefix('/{client}/domains')->group(function () {
                /**
                 * Update email domain
                 */
                Route::post('/{domain}', 'EmailDomainController@save');

                /**
                 * Create email domain
                 */
                Route::post('', 'EmailDomainController@save');

                /**
                 * Delete email domain
                 */
                Route::delete('/{domain}', 'EmailDomainController@delete');
            });
        });
    });
    Route::group(['middleware' => ['permission:manage sites']], function () {
        Route::prefix('sites')->group(function () {
            /**
             * get site list
             */
            Route::get('', 'SiteController@index');
            /**
             * Update site
             */
            Route::post('/{site}', 'SiteController@save');
            /**
             * pick a site
             */
            Route::get('/{site}', 'SiteController@get');
            /**
             * Create site
             */
            Route::post('', 'SiteController@save');

            /**
             * Delete site
             */
            Route::delete('/{site}', 'SiteController@delete');
        });
    });

    Route::group(['middleware' => ['permission:manage partners']], function () {
        Route::prefix('partners')->group(function () {
            /**
             * get partner list
             */
            Route::get('', 'PartnerController@index');
            /**
             * Update partner
             */
            Route::post('/{partner}', 'PartnerController@save');
            /**
             * pick a partner
             */
            Route::get('/{partner}', 'PartnerController@get');
            /**
             * Create partner
             */
            Route::post('', 'PartnerController@save');

            /**
             * Delete partner
             */
            Route::delete('/{partner}', 'PartnerController@delete');
        });
    });

    Route::group(['middleware' => ['permission:manage bookings|manage my bookings only']], function () {
        Route::prefix('bookings')->group(function () {
            /**
             * get booking statuses
             */
            Route::get('/statuses', 'BookingController@getStatuses');
            /**
             * get booking list
             */
            Route::get('', 'BookingController@index');
            /**
             * Update booking
             */
            Route::post('/{booking}', 'BookingController@save');
            /**
             * pick a booking
             */
            Route::get('/{booking}', 'BookingController@get');
        });
    });

    Route::group(['middleware' => ['permission:super administrator only']], function () {
        Route::prefix('admins')->group(function () {
            /**
             * get admin list
             */
            Route::get('', 'AdminController@index');
            /**
             * Update admin
             */
            Route::post('/{admin}', 'AdminController@save');
            /**
             * pick a admin
             */
            Route::get('/{admin}', 'AdminController@get');
            /**
             * Create admin
             */
            Route::post('', 'AdminController@save');

            /**
             * Delete admin
             */
            Route::delete('/{admin}', 'AdminController@delete');
        });

        Route::prefix('users')->group(function () {
            /**
             * Get user list
             */
            Route::get('', 'Admin\UserController@index');

            /**
             * Update user
             */
            Route::post('/{user}', 'Admin\UserController@save');

            /**
             * Resend verification email to user
             */
            Route::post('/{user}/resend', 'Admin\UserController@resend');

            /**
             * Pick a user
             */
            Route::get('/{user}', 'Admin\UserController@get');
            /**
             * Delete user
             */
            Route::delete('/{user}', 'Admin\UserController@delete');
        });

        Route::prefix('roles')->group(function () {
            /**
             * get role list
             */
            Route::get('', 'RoleController@index');
        });
    });

    Route::group(['middleware' => ['permission:manage services']], function () {
        Route::prefix('services')->group(function () {
            /**
             * get services list
             */
            Route::get('', 'ServiceController@index');
            /**
             * Update service
             */
            Route::post('/{service}', 'ServiceController@save');
            /**
             * pick a service
             */
            Route::get('/{service}', 'ServiceController@get');
            /**
             * Create service
             */
            Route::post('', 'ServiceController@save');

            /**
             * Delete service
             */
            Route::delete('/{service}', 'ServiceController@delete');
        });
    });
    Route::group(['middleware' => ['permission:manage products|manage my products only']], function () {
        Route::prefix('products')->group(function () {
            /**
             * get products list
             */
            Route::get('', 'ProductController@index');
            /**
             * Update product
             */
            Route::post('/{product}', 'ProductController@save');
            /**
             * read a product
             */
            Route::get('/{product}', 'ProductController@get');
            /**
             * Create product
             */
            Route::post('', 'ProductController@save');

            /**
             * Delete product
             */
            Route::delete('/{product}', 'ProductController@delete');
        });
        Route::prefix('product')->group(function () {
            /**
             * read a product Client Sites
             */
            Route::get('/sites', 'ProductController@getSites');
        });
        /**
         * get product Tags
         */
        Route::prefix('product')->group(function () {

            Route::get('/tags', 'TagController@index');
        });
    });

    /**
     * get locales
     */
    Route::get('/locales', 'LocaleController@index');
});
