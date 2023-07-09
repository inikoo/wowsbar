<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 15:05:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Tenancy\Permission;
use App\Models\Tenancy\Role;
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

        $currentPermissions = Permission::all()->pluck('name');
        $currentPermissions->diff($permissions)
            ->each(function ($permissionName) {
                Permission::where('name', $permissionName)->first()->delete();
            });


        $currentRoles = Role::all()->pluck('name');
        $currentRoles->diff(collect(config("blueprint.roles"))->keys())
            ->each(function ($roleName) {
                Role::where('name', $roleName)->first()->delete();
            });


        $permissions->each(function ($permissionName) {
            try {

                Permission::create(['name' => $permissionName]);
            } catch (Exception) {
            }
        });

        $roles->each(function ($permissionData) {
            if (!$role = (new Role())->where('name', $permissionData['name'])
                ->first()) {
                $role = Role::create(['name' => $permissionData['name']]);
            }
            $permissions = [];
            foreach ($permissionData['permissions'] as $permissionName) {
                if ($permission = (new Permission())->where('name', $permissionName)->first()) {
                    $permissions[] = $permission;
                }
            }

            $role->syncPermissions($permissions);
        });
    }
}
