<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function () {
    Route::get('login', 'AuthController@login')->name('login');
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::get('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('me', 'AuthController@me')->name('me');

    Route::group([
        'middleware' => 'admin'
    ], function () {
        Route::get('users', 'UserController@getAll');
        Route::post('users', 'UserController@create');
        Route::post('work-shift', 'WorkShiftController@create');
        Route::post('work-shift/{id}/open', 'WorkShiftController@open');
        Route::post('work-shift/{id}/close', 'WorkShiftController@close');
    });
});
