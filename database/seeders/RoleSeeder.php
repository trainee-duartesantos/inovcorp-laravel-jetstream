<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder {
    public function run() {
        Role::updateOrCreate(
            ['id' => 1],
            ['slug'=>'admin', 'name'=>'Admin']
        );

        Role::updateOrCreate(
            ['id' => 2],
            ['slug'=>'cidadao', 'name'=>'Cidadão']
        );

    }
}

