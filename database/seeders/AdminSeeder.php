<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Criar ou atualizar o Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@biblioteca.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('123456789'),
            ]
        );

        // Atribuir o papel admin (ID=1 na tabela roles)
        $admin->roles()->sync([1]);

        echo "✔ Admin admin@biblioteca.com criado ou atualizado com sucesso!\n";
        echo "🔑 Password: 123456789\n";
    }
}
