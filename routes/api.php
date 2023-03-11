<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// api/ping
Route::get('/ping', function() {
    return response()->json([
        'pong',
    ], Response::HTTP_OK);
});

/*
|--------------------------------------------------------------------------
| Route Prefix = api/v1/
| Route Name = api.
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'v1',
    'as' => 'api.',
], function() {

    /*
    |--------------------------------------------------------------------------
    | api.register
    |--------------------------------------------------------------------------
    */
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    /*
    |--------------------------------------------------------------------------
    | api.login
    |--------------------------------------------------------------------------
    */
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::group([
        'middleware' => ['auth:sanctum',],
    ], function () {

        /*
        |--------------------------------------------------------------------------
        | api.me
        |--------------------------------------------------------------------------
        */
        Route::get('/me', [AuthController::class, 'me'])->name('me');

        /*
        |--------------------------------------------------------------------------
        | api.logout
        |--------------------------------------------------------------------------
        */
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

