<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 15:05:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = collect(json_decode(Storage::disk('datasets')->get('permissions.json'), true));
        $roles       = collect(json_decode(Storage::disk('datasets')->get('roles.json'), true));


        $this->storePermissionsRoles($permissions, $roles, 'web');

        $landlordPermissions = collect(json_decode(Storage::disk('datasets')->get('landlord-permissions.json'), true));
        $landlordRoles       = collect(json_decode(Storage::disk('datasets')->get('landlord-roles.json'), true));

        $this->storePermissionsRoles($landlordPermissions, $landlordRoles, 'landlord');
    }


    private function storePermissionsRoles($permissions, $roles, $guard): void
    {


        $currentPermissions = Permission::where('guard_name', $guard)->pluck('name');
        $currentPermissions->diff($permissions)
            ->each(function ($permissionName) use ($guard) {
                Permission::where('name', $permissionName)->where('guard_name', $guard)->first()->delete();
            });


        $currentRoles = Role::where('guard_name', $guard)->pluck('name');

        $currentRoles->diff(collect(config("blueprint.roles"))->keys())
            ->each(function ($roleName) use ($guard) {
                Role::where('name', $roleName)->where('guard_name', $guard)->first()->delete();
            });


        $permissions->each(function ($permissionName) use ($guard) {
            try {
                Permission::create(
                    [
                        'guard_name' => $guard,
                        'name'       => $permissionName
                    ]
                );
            } catch (Exception) {
            }
        });


        $roles->each(function ($roleData) use ($guard) {


            if (!$role = (new Role())->where('name', $roleData['name'])->where('guard_name', $guard)
                ->first()) {



                $role = Role::create([
                    'guard_name' => $guard,
                    'name'       => $roleData['name']
                ]);
            }
            $permissions = [];
            foreach ($roleData['permissions'] as $permissionName) {
                if ($permission = (new Permission())
                    ->where('name', $permissionName)
                    ->where('guard_name', $guard)
                    ->first()) {
                    $permissions[] = $permission;
                }
            }

            $role->syncPermissions($permissions);
        });
    }


}
