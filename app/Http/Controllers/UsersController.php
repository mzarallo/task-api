<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUserById;
use App\Actions\User\GetAllUsers;
use App\Actions\User\GetUserById;
use App\Actions\User\UpdateUserById;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UsersController extends Controller
{
    public function all(GetAllUsers $getAllUsers): AnonymousResourceCollection
    {
        return UserResource::collection($getAllUsers->handle(sortFields: ['last_name']));
    }

    public function getById(int $userId, GetUserById $getUserById): UserResource|JsonResponse
    {
        try {
            return new UserResource($getUserById->run($userId));
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function deleteById(int $userId, DeleteUserById $deleteUserById): JsonResponse
    {
        try {
            $deleteUserById->run($userId);

            return response()->json([], 204);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function updateById(int $userId, UpdateUserRequest $request, UpdateUserById $updateUserById): JsonResponse
    {
        try {
            $userResource = new UserResource($updateUserById->run($userId, $request->validated()));

            return response()->json($userResource);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function create(CreateUserRequest $request, CreateUser $createUser): JsonResponse
    {
        $userResource = new UserResource($createUser->run($request->validated()));

        return response()->json($userResource, 201);
    }
}
