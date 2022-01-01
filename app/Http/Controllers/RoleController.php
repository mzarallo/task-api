<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Role\GetAllRoles;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function all(GetAllRoles $getAllRoles): AnonymousResourceCollection
    {
        return RoleResource::collection($getAllRoles->run());
    }
}
