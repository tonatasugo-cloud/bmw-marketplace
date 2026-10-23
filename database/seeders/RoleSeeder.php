<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============ ROLES ============
        $admin  = Role::firstOrCreate(['name' => 'admin']);
        $seller = Role::firstOrCreate(['name' => 'seller']);
        $buyer  = Role::firstOrCreate(['name' => 'buyer']);

        // ============ PERMISSIONS ============
        $permissions = [
            'manage users',
            'manage sellers',
            'manage cars',
            'approve listings',
            'manage transactions',
            'view reports',
            'manage own cars',
            'respond offers',
            'respond test drives',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Assign permission
        $admin->syncPermissions(Permission::all());
        $seller->syncPermissions([
            'manage own cars',
            'respond offers',
            'respond test drives',
        ]);

        // ============ DEFAULT ADMIN USER ============
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@bmw.test'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );
        $adminUser->assignRole('admin');

        $this->command->info('✅ RoleSeeder selesai. Login: admin@bmw.test / password');
    }
}