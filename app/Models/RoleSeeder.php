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
        // Roles
        $admin  = Role::create(['name' => 'admin']);
        $seller = Role::create(['name' => 'seller']);
        $buyer  = Role::create(['name' => 'buyer']);

        // Permissions
        $permissions = [
            'manage users', 'manage sellers', 'manage cars',
            'approve listings', 'manage transactions', 'view reports',
            'manage own cars', 'respond offers', 'respond test drives',
        ];
        foreach ($permissions as $p) {
            Permission::create(['name' => $p]);
        }

        $admin->givePermissionTo(Permission::all());
        $seller->givePermissionTo(['manage own cars', 'respond offers', 'respond test drives']);

        // Default admin
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bmw.test',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
        $user->assignRole('admin');
    }
}