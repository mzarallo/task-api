<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Permissions;

use App\Actions\Permissions\GetAllPermissions;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class GetAllPermissionsTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_get_all_permissions(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = GetAllPermissions::make()->handle();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(Permission::class, $response);
    }
}
