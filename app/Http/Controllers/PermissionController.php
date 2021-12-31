<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Permission\GetAllPermissions;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    public function all(GetAllPermissions $getAllPermissions): AnonymousResourceCollection
    {
        return PermissionResource::collection($getAllPermissions->run());
    }
}
