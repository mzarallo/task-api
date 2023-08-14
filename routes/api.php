<?php

declare(strict_types=1);

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StagesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
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
        //Permission routes
        Route::prefix('permissions')->name('permissions.')
            ->controller(PermissionsController::class)->group(function () {
                Route::get('/', 'all')->name('all')->middleware('can:list-permissions');
            });

        //Role routes
        Route::prefix('roles')->name('roles.')
            ->controller(RolesController::class)->group(function () {
                Route::get('/', 'all')->name('all')->middleware('can:list-roles');
            });

        //User routes
        Route::prefix('users')->name('users.')
            ->controller(UsersController::class)->group(function () {
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

        //Board routes
        Route::prefix('boards')->name('boards.')
            ->controller(BoardsController::class)->group(function () {
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
            });

        //Stage routes
        Route::prefix('boards/{board}/stages')->name('boards.stages.')
            ->controller(StagesController::class)->group(function () {
                Route::middleware('can:list-stages')
                    ->get('/', 'all')->name('all')->scopeBindings();
                Route::middleware('can:list-stages')
                    ->get('/{stage}', 'getById')->name('getById')->scopeBindings();
                Route::middleware('can:delete-stages')
                    ->delete('/{stage}', 'deleteById')->name('deleteById')->scopeBindings();
                Route::middleware('can:edit-stages')
                    ->patch('/{stage}', 'updateById')->name('updateById')->scopeBindings();
                Route::middleware('can:edit-stages')
                    ->patch('/{stage}', 'updateById')->name('updateById')->scopeBindings();
                Route::middleware('can:create-stages')
                    ->post('/', 'create')->name('create')->scopeBindings();
            });

        //Task routes
        Route::prefix('boards/{board}/stages/{stage}/tasks')->name('boards.stages.tasks.')
            ->controller(TasksController::class)->group(function () {
                Route::get('/', 'all')->name('all')->can('list-tasks')->scopeBindings();
                Route::post('/', 'create')->name('create')->can('create-tasks')->scopeBindings();
            });
    });
});
