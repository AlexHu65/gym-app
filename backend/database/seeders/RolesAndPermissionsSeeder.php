<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage-exercises' => 'Manage exercises',
            'manage-taxonomies' => 'Manage taxonomies',
            'manage-imports' => 'Manage imports',
            'manage-users' => 'Manage users',
            'view-audit-logs' => 'View audit logs',
        ];

        $permissionModels = collect($permissions)->map(function (string $label, string $name) {
            return Permission::updateOrCreate(
                ['name' => $name],
                ['label' => $label]
            );
        });

        $roles = [
            'super-admin' => 'Super Admin',
            'content-editor' => 'Content Editor',
            'reviewer' => 'Reviewer',
            'support' => 'Support',
            'customer' => 'Customer',
        ];

        $roleModels = collect($roles)->map(function (string $label, string $name) {
            return Role::updateOrCreate(
                ['name' => $name],
                ['label' => $label]
            );
        });

        $allPermissions = $permissionModels->values();
        $editorPermissions = $permissionModels->only([
            'manage-exercises',
            'manage-taxonomies',
            'manage-imports',
        ])->values();
        $supportPermissions = $permissionModels->only([
            'view-audit-logs',
        ])->values();

        $roleModels['super-admin']->permissions()->sync($allPermissions->pluck('id'));
        $roleModels['content-editor']->permissions()->sync($editorPermissions->pluck('id'));
        $roleModels['reviewer']->permissions()->sync($permissionModels->only([
            'manage-exercises',
            'manage-taxonomies',
        ])->pluck('id'));
        $roleModels['support']->permissions()->sync($supportPermissions->pluck('id'));
        $roleModels['customer']->permissions()->sync([]);
    }
}

