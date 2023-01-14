<?php

declare(strict_types=1);

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StageController;
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
    Route::prefix('auth')->name('authentication.')->controller(AuthenticationController::class)
        ->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/refresh', 'refresh')->name('refresh');
        });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('permissions')->name('permissions.')
            ->controller(PermissionController::class)->group(function () {
                Route::middleware('can:list-permissions')->get('/', 'all')->name('all');
            });

        Route::name('roles.')->controller(RoleController::class)->group(function () {
            Route::middleware('can:list-roles')->get('/', 'all')->name('all');
        });

        Route::prefix('users')->name('users.')
            ->controller(UserController::class)->group(function () {
                Route::middleware('can:list-users')->get('/', 'all')->name('all');
                Route::middleware('can:list-users')
                    ->get('/{user}', 'getById')->name('getById');
                Route::middleware('can:delete-users')
                    ->delete('/{user}', 'deleteById')->name('deleteById');
                Route::middleware('can:edit-users')
                    ->patch('/{user}', 'updateById')->name('updateById');
                Route::middleware('can:create-users')
                    ->post('/', 'create')->name('create');
            });

        Route::prefix('boards')->name('boards.')
            ->controller(BoardController::class)->group(function () {
                Route::middleware('can:list-boards')
                    ->get('/', 'all')->name('all');
                Route::middleware('can:list-boards')
                    ->get('/{board}', 'getById')->name('getById');
                Route::middleware('can:delete-boards')
                    ->delete('/{board}', 'deleteById')->name('deleteById');
                Route::middleware('can:edit-boards')
                    ->patch('/{board}', 'updateById')->name('updateById');
                Route::middleware('can:create-boards')
                    ->post('/', 'create')->name('create');

                Route::name('stages.')->controller(StageController::class)->group(function () {
                    Route::middleware('can:list-stages')
                        ->get('/{board}/stages', 'all')->name('all');
                    Route::middleware('can:list-stages')
                        ->get('/{board}/stages/{stage}', 'getById')->name('getById');
                    Route::middleware('can:delete-stages')
                        ->delete('/{board}/stages/{stage}', 'deleteById')->name('deleteById');
                    Route::middleware('can:edit-stages')
                        ->patch('/{board}/stages/{stage}', 'updateById')->name('updateById');
                    Route::middleware('can:edit-stages')
                        ->patch('/{board}/stages/{stage}', 'updateById')->name('updateById');
                    Route::middleware('can:create-stages')
                        ->post('/{board}/stages', 'create')->name('create');
                });
            });
    });
});
