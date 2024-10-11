<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin M',
            'email' => 'admin@culturecf.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $admin->assignRole([$role->id]);

        $manager = User::create([
            'name' => 'Manager M',
            'email' => 'manager@culturecf.com',
            'password' => bcrypt('123456')
        ]);

        // Manager
        $role = Role::create(['name' => 'Manger']);
        $permissions = ['member', 'game', 'operation', 'management', 'user', 'log', 'permission'];
        $role->givePermissionTo($permissions);

        $manager->assignRole([$role->id]);

        // Operator
        $operator = User::create([
            'name' => 'Operator M',
            'email' => 'operator@culturecf.com',
            'password' => bcrypt('123456')
        ]);

        // Manager
        $role = Role::create(['name' => 'Operator']);
        $permissions = ['member', 'game'];
        $role->givePermissionTo($permissions);

        $operator->assignRole([$role->id]);


        // Custom Permission User
        $user = User::create([
            'name' => 'Operator U',
            'email' => 'user@culturecf.com',
            'password' => bcrypt('123456')
        ]);

        $user->assignRole('Operator');

        $user->givePermissionTo(['member', 'game', 'operation', 'management']);

    }
}
