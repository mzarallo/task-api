<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\DeleteUserById;
use App\Actions\User\GetUserById;
use App\Actions\User\GetAllUsers;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function all(GetAllUsers $getAllUsers): AnonymousResourceCollection
    {
        return UserResource::collection($getAllUsers->run());
    }

    public function getById(int $userId, GetUserById $getUserById): UserResource | JsonResponse
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
}
