<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Actions\Users\DeleteUserById;
use App\Actions\Users\GetAllUsers;
use App\Actions\Users\GetUserById;
use App\Actions\Users\UpdateUserById;
use App\Data\Services\Users\CreateUserServiceDto;
use App\Data\Services\Users\DeleteUserByIdServiceDto;
use App\Data\Services\Users\GetAllUsersServiceDto;
use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Data\Services\Users\UpdateUserByIdServiceDto;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function all(GetAllUsers $getAllUsers): AnonymousResourceCollection
    {
        return UserResource::collection(
            $getAllUsers->handle(
                GetAllUsersServiceDto::validateAndCreate([
                    'sort_fields' => ['last_name'],
                    'paginated' => true,
                ])
            )
        );
    }

    public function getById(User $user, GetUserById $getUserById): UserResource|JsonResponse
    {
        return new UserResource(
            $getUserById->handle(
                GetUserByIdServiceDto::validateAndCreate(['user_id' => $user->id])
            )
        );
    }

    public function deleteById(User $user, DeleteUserById $deleteUserById): JsonResponse
    {
        $deleteUserById->handle(DeleteUserByIdServiceDto::validateAndCreate(['user_id' => $user->id]));

        return response()->json([], 204);
    }

    public function updateById(UpdateUserRequest $request, User $user, UpdateUserById $updateUserById): JsonResponse
    {
        $userResource = new UserResource(
            $updateUserById->handle(
                $user->id,
                UpdateUserByIdServiceDto::validateAndCreate($request->validated())
            )
        );

        return response()->json($userResource);
    }

    public function create(CreateUserRequest $request, CreateUser $createUser): JsonResponse
    {
        $userResource = new UserResource(
            $createUser->handle(CreateUserServiceDto::validateAndCreate($request->validated()))
        );

        return response()->json($userResource, Response::HTTP_CREATED);
    }
}
