<?php

use Illuminate\Database\Seeder;
use App\Role as Role;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        Role::truncate();

        Role::create(['name' => 'Admin']);
		Role::create(['name' => 'User']);
		Role::create(['name' => 'Branch1']);
		Role::create(['name' => 'Branch2']);
    }

}