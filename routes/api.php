<?php

declare(strict_types=1);

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
        Route::middleware('can:list-permissions')->prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'all'])->name('all');
        });

        Route::middleware('can:list-roles')->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'all'])->name('all');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::middleware('can:list-users')->get('/', [UserController::class, 'all'])->name('all');
            Route::middleware('can:list-users')->get('/{id}', [UserController::class, 'getById'])->name('getById');
            Route::middleware('can:delete-users')->delete('/{id}', [UserController::class, 'deleteById'])->name('deleteById');
            Route::middleware('can:edit-users')->patch('/{id}', [UserController::class, 'updateById'])->name('updateById');
            Route::middleware('can:create-users')->post('/', [UserController::class, 'create'])->name('create');
        });

        Route::prefix('boards')->name('boards.')->group(function () {
            Route::middleware('can:list-boards')->get('/', [BoardController::class, 'all'])->name('all');
        });
    });
});
