<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-users',
            'show-users',
            'create-users',
            'edit-users',
            'download-users',
            'delete-users',
            'view-roles',
            'show-roles',
            'create-roles',
            'edit-roles',
            'download-roles',
            'delete-roles',
            'view-permissions',
            'show-permissions',
            'create-permissions',
            'edit-permissions',
            'download-permissions',
            'delete-permissions',
            'view-products',
            'show-products',
            'create-products',
            'edit-products',
            'download-products',
            'delete-products',
            'view-nasabah',
            'show-nasabah',
            'create-nasabah',
            'edit-nasabah',
            'download-nasabah',
            'delete-nasabah',
            'view-simpan-pinjam',
            'show-simpan-pinjam',
            'create-simpan-pinjam',
            'edit-simpan-pinjam',
            'download-simpan-pinjam',
            'delete-simpan-pinjam',
            'view-pembelian',
            'show-pembelian',
            'create-pembelian',
            'edit-pembelian',
            'download-pembelian',
            'delete-pembelian',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $superAdminRole->permissions()->sync(Permission::all());
        $adminRole->permissions()->sync(Permission::all());

        $editorRole->permissions()->sync(
            Permission::whereIn('name', [
                'view-users', 'show-users',
                'view-roles', 'show-roles',
                'view-permissions', 'show-permissions'
            ])->pluck('id')
        );

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->roles()->sync([$superAdminRole->id]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $admin->roles()->sync([$adminRole->id]);

        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
            ]
        );

        $editor->roles()->sync([$editorRole->id]);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );

        $user->roles()->sync([$userRole->id]);
    }
}
