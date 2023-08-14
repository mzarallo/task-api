<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Actions\Users\DeleteUserById;
use App\Actions\Users\GetAllUsers;
use App\Actions\Users\GetUserById;
use App\Actions\Users\UpdateUserById;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UsersController extends Controller
{
    public function all(GetAllUsers $getAllUsers): AnonymousResourceCollection
    {
        return UserResource::collection($getAllUsers->handle(sortFields: ['last_name']));
    }

    public function getById(User $user, GetUserById $getUserById): UserResource|JsonResponse
    {
        return new UserResource($getUserById->handle($user->id));
    }

    public function deleteById(User $user, DeleteUserById $deleteUserById): JsonResponse
    {
        $deleteUserById->handle($user->id);

        return response()->json([], 204);
    }

    public function updateById(User $user, UpdateUserRequest $request, UpdateUserById $updateUserById): JsonResponse
    {
        $userResource = new UserResource($updateUserById->handle($user->id, $request->validated()));

        return response()->json($userResource);
    }

    public function create(CreateUserRequest $request, CreateUser $createUser): JsonResponse
    {
        $userResource = new UserResource($createUser->run($request->validated()));

        return response()->json($userResource, 201);
    }
}
