<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        $roles = [
            ['name' => 'Root'],
            ['name' => 'Kepala Sekolah'],
            ['name' => 'Administrator'],
            ['name' => 'Guru'],
            ['name' => 'Siswa'],
            ['name' => 'Visitor'],
        ];
    
        Role::insert($roles);
    }
}
