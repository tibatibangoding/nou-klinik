<?php

namespace Database\Seeders;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $defval = [
            'email_verified_at' => now(),
            'password' => bcrypt('klinikapp'), // password
            'remember_token' => Str::random(10),
        ];

        $userdokter = User::create(array_merge([
            'username' => '109190501sp',
            'nama' => 'Dr. Rifat Sp.Bs',
            'spesialis' => 'Bedah Syaraf',
            'id_poli' => 1,
            'biaya_dokter' => 120000,
        ], $defval));
        $useradmin = User::create(array_merge([
            'username' => '102190501sp',
            'nama' => 'Admin',
        ], $defval));
        $userapoteker = User::create(array_merge([
            'username' => '104190501sp',
            'nama' => 'Apoteker',
        ], $defval));
        $poli = Poli::create([
            'nama_poli' => 'Bedah Syaraf',
        ]);


        $role = Role::create(['name' => 'admin']);
        $role = Role::create(['name' => 'dokter']);
        $role = Role::create(['name' => 'apoteker']);

        $permission = Permission::create(['name' => 'admin_stuff']);
        $permission = Permission::create(['name' => 'dokter_stuff']);
        $permission = Permission::create(['name' => 'apoteker_stuff']);

        $admin = Role::findByName('admin');
        $dokter = Role::findByName('dokter');
        $apoteker = Role::findByName('apoteker');

        $dokter->givePermissionTo('dokter_stuff');
        $admin->givePermissionTo('admin_stuff');
        $apoteker->givePermissionTo('apoteker_stuff');

        $useradmin->assignRole('admin');
        $userdokter->assignRole('dokter');
        $userapoteker->assignRole('apoteker');
    }
}
