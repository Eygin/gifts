<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = array('user', 'permission', 'role', 'gift', 'gift kategori', 'redeem', 'rating');
        foreach ($permission as $item) {
            Permission::create(['name' => $item]);
            if ($item != 'redeem' && $item != 'rating') {
                Permission::create(['name' => 'tambah '.$item]);
                Permission::create(['name' => 'edit '.$item]);
                Permission::create(['name' => 'hapus '.$item]);
                Permission::create(['name' => 'lihat '.$item]);
            }
        }

        $roleAdmin = Role::findOrCreate('super admin');
        $dataPermissionAdmin = Permission::get();
        $roleAdmin->syncPermissions($dataPermissionAdmin);
        $dataUserAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('Qwebnm123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $dataUserAdmin->assignRole($roleAdmin->name);

        $roleUser = Role::findOrCreate('user');
        $dataPermissionUser = Permission::whereIn('name', ['gift', 'lihat gift', 'gift kategori', 'redeem', 'rating'])->get();
        $roleUser->syncPermissions($dataPermissionUser);
        $dataUser = User::create([
            'name' => 'User Example',
            'email' => 'user@example.com',
            'password' => bcrypt('Qwebnm123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $dataUser->assignRole($roleUser->name);
    }
}
