<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Roles;

use App\Actions\Roles\GetAllRoles;
use App\Data\Services\Roles\GetAllRolesServiceDto;
use Database\Seeders\RoleSeeder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetAllRolesTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_get_all_roles_as_collection(): void
    {
        $this->seed(RoleSeeder::class);

        $response = GetAllRoles::make()->handle(
            GetAllRolesServiceDto::from([
                'paginated' => false,
            ])
        );

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(Role::class, $response);
    }

    /**
     * @test
     */
    public function it_get_all_roles_as_pagination(): void
    {
        $this->seed(RoleSeeder::class);

        $response = GetAllRoles::make()->handle(
            GetAllRolesServiceDto::from([
                'paginated' => true,
            ])
        );

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertContainsOnlyInstancesOf(Role::class, $response->items());
    }

    /**
     * @test
     */
    public function it_get_all_roles_sorted(): void
    {
        Role::query()->create(['name' => 'AAA']);
        Role::query()->create(['name' => 'CCC']);
        Role::query()->create(['name' => 'BBB']);

        $response = GetAllRoles::make()->handle(
            GetAllRolesServiceDto::from([
                'sort_fields' => ['name'],
                'paginated' => false,
            ])
        );

        $this->assertEquals(1, $response->offsetGet(0)->id);
        $this->assertEquals(3, $response->offsetGet(1)->id);
        $this->assertEquals(2, $response->offsetGet(2)->id);
    }
}
