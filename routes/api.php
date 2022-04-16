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
    Route::prefix('auth')->name('authentication.')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
    });

    //Routes with authentication required
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
                    ->get('/{id}', 'getById')->name('getById');
                Route::middleware('can:delete-users')
                    ->delete('/{id}', 'deleteById')->name('deleteById');
                Route::middleware('can:edit-users')
                    ->patch('/{id}', 'updateById')->name('updateById');
                Route::middleware('can:create-users')
                    ->post('/', 'create')->name('create');
            });

        Route::prefix('boards')->name('boards.')
            ->controller(BoardController::class)->group(function () {
                Route::middleware('can:list-boards')
                    ->get('/', 'all')->name('all');
                Route::middleware('can:list-boards')
                    ->get('/{id}', 'getById')->name('getById');
                Route::middleware('can:delete-boards')
                    ->delete('/{id}', 'deleteById')->name('deleteById');
                Route::middleware('can:edit-boards')
                    ->patch('/{id}', 'updateById')->name('updateById');
                Route::middleware('can:create-boards')
                    ->post('/', 'create')->name('create');

                Route::name('stages.')->controller(StageController::class)->group(function () {
                    Route::middleware('can:list-stages')
                    ->get('/{boardId}/stages', 'all')->name('all');
                    Route::middleware('can:list-stages')
                    ->get('/{boardId}/stages/{stageId}', 'getById')->name('getById');
                    Route::middleware('can:delete-stages')
                    ->delete('/{boardId}/stages/{stageId}', 'deleteById')->name('deleteById');
                });
            });
    });
});
