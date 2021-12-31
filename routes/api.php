<?php

declare(strict_types=1);

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

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

Route::name('api.')->group(function () {
    Route::prefix('auth')->name('authentication.')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
    });

    //Routes with authentication required
    Route::middleware('auth:api')->group(function () {
        Route::middleware('can:list-permissions')->prefix('permissions')->name('permissions.')->group( function () {
            Route::get('/', [PermissionController::class, 'all'])->name('all');
        });
    });
});
