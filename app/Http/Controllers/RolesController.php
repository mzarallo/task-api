<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Roles\GetAllRoles;
use App\Data\Services\Roles\GetAllRolesServiceDto;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RolesController extends Controller
{
    public function all(GetAllRoles $getAllRoles): AnonymousResourceCollection
    {
        return RoleResource::collection(
            $getAllRoles->handle(GetAllRolesServiceDto::validateAndCreate([
                'sor_fields' => ['name'],
                'paginated' => false,
            ]))
        );
    }
}
