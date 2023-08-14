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
    Route::prefix('auth')->name('authentication.')
        ->controller(AuthenticationController::class)->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/refresh', 'refresh')->name('refresh');
        });

    Route::middleware('auth:api')->group(function () {
        //Permission routes
        Route::prefix('permissions')->name('permissions.')
            ->controller(PermissionsController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('list-permissions');
            });

        //Role routes
        Route::prefix('roles')->name('roles.')
            ->controller(RolesController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('list-roles');
            });

        //User routes
        Route::prefix('users')->name('users.')
            ->controller(UsersController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('list-users');
                Route::get('/{user}', 'getById')->name('getById')
                    ->can('list-users')->scopeBindings();
                Route::delete('/{user}', 'deleteById')->name('deleteById')
                    ->can('delete-users')->scopeBindings();
                Route::patch('/{user}', 'updateById')->name('updateById')
                    ->can('edit-users')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create-users')->scopeBindings();
            });

        //Board routes
        Route::prefix('boards')->name('boards.')
            ->controller(BoardsController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('list-boards')->scopeBindings();
                Route::get('/{board}', 'getById')->name('getById')
                    ->can('list-boards')->scopeBindings();
                Route::delete('/{board}', 'deleteById')->name('deleteById')
                    ->can('delete-boards')->scopeBindings();
                Route::patch('/{board}', 'updateById')->name('updateById')
                    ->can('edit-boards')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create-boards')->scopeBindings();
            });

        //Stage routes
        Route::prefix('boards/{board}/stages')->name('boards.stages.')
            ->controller(StagesController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('list-stages')->scopeBindings();
                Route::get('/{stage}', 'getById')->name('getById')
                    ->can('list-stages')->scopeBindings();
                Route::delete('/{stage}', 'deleteById')->name('deleteById')
                    ->can('delete-stages')->scopeBindings();
                Route::patch('/{stage}', 'updateById')->name('updateById')
                    ->can('edit-stages')->scopeBindings();
                Route::patch('/{stage}', 'updateById')->name('updateById')
                    ->can('edit-stages')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create-stages')->scopeBindings();
            });

        //Task routes
        Route::prefix('boards/{board}/stages/{stage}/tasks')->name('boards.stages.tasks.')
            ->controller(TasksController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('list-tasks')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create-tasks')->scopeBindings();
                Route::delete('/{task}', 'deleteById')->name('deleteById')
                    ->can('delete-tasks')->scopeBindings();
            });
    });
});
