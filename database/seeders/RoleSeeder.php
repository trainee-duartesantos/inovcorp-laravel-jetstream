<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder {
    public function run() {
        Role::updateOrCreate(['slug'=>'admin'], ['name'=>'Admin']);
        Role::updateOrCreate(['slug'=>'cidadao'], ['name'=>'Cidadão']);
    }
}

