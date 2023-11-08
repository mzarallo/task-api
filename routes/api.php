<?php

declare(strict_types=1);

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StagesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
                    ->can('viewAny', Permission::class);
            });

        //Role routes
        Route::prefix('roles')->name('roles.')
            ->controller(RolesController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('viewAny', Role::class);
            });

        //User routes
        Route::prefix('users')->name('users.')
            ->controller(UsersController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('viewAny', User::class);
                Route::get('/{user}', 'getById')->name('getById')
                    ->can('view', 'user')->scopeBindings();
                Route::delete('/{user}', 'deleteById')->name('deleteById')
                    ->can('delete', 'user')->scopeBindings();
                Route::patch('/{user}', 'updateById')->name('updateById')
                    ->can('edit', 'user')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create', User::class)->scopeBindings();
            });

        //Board routes
        Route::prefix('boards')->name('boards.')
            ->controller(BoardsController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('viewAny', Board::class)->scopeBindings();
                Route::get('/{board}', 'getById')->name('getById')
                    ->can('view', 'board')->scopeBindings();
                Route::delete('/{board}', 'deleteById')->name('deleteById')
                    ->can('delete', 'board')->scopeBindings();
                Route::patch('/{board}', 'updateById')->name('updateById')
                    ->can('edit', 'board')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create', Board::class)->scopeBindings();
            });

        //Stage routes
        Route::prefix('boards/{board}/stages')->name('boards.stages.')
            ->controller(StagesController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('viewAny', Stage::class)->scopeBindings();
                Route::get('/{stage}', 'getById')->name('getById')
                    ->can('view', 'stage')->scopeBindings();
                Route::delete('/{stage}', 'deleteById')->name('deleteById')
                    ->can('delete', 'stage')->scopeBindings();
                Route::patch('/{stage}', 'updateById')->name('updateById')
                    ->can('edit', 'stage')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create', Stage::class)->scopeBindings();
            });

        //Task routes
        Route::prefix('boards/{board}/stages/{stage}/tasks')->name('boards.stages.tasks.')
            ->controller(TasksController::class)->group(function () {
                Route::get('/', 'all')->name('all')
                    ->can('viewAny', Task::class)->scopeBindings();
                Route::delete('/{task}', 'deleteById')->name('deleteById')
                    ->can('delete', 'task')->scopeBindings();
                Route::patch('/{task}', 'updateById')->name('updateById')
                    ->can('edit', 'task')->scopeBindings();
                Route::post('/', 'create')->name('create')
                    ->can('create', Task::class)->scopeBindings();
            });
    });
});
