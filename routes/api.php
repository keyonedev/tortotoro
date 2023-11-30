<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::group([
    'middleware' => 'api',
], function () {
    // jwt auth routes
    Route::get('login', 'AuthController@login')->name('login');
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::get('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('me', 'AuthController@me')->name('me');

    // admin routes
    Route::group([
        'middleware' => 'admin'
    ], function () {
        Route::get('users', 'UserController@getAll');
        Route::post('users', 'UserController@create');
        Route::post('work-shift', 'WorkShiftController@create');
        Route::get('work-shift/{id}/open', 'WorkShiftController@open');
        Route::get('work-shift/{id}/close', 'WorkShiftController@close');
        Route::post('work-shift/{id}/user', 'WorkShiftController@user');
        Route::get('work-shift/{id}/order', 'WorkShiftController@orders');
    });

    // waiter routes
    Route::group([
        'middleware' => 'waiter'
    ], function () {
        Route::post('orders', 'OrderController@create');
        Route::get('orders/{id}', 'OrderController@get');
        Route::get('work-shift/{id}/orders', 'OrderController@orders');
        Route::patch('orders/{id}/change-status', 'OrderController@changeStatus');
    });

    // chef routes
    Route::group([
        'middleware' => 'chef'
    ], function () {
        Route::get('order/taken', 'OrderController@getAllForChef');
        Route::patch('order/{id}/change-status', 'OrderController@changeStatusForChef');
    });
});
