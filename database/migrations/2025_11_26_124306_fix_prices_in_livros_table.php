<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Converte preços tipo "2499,00" para "24.99"
        DB::table('livros')->select('id', 'preco')->orderBy('id')->chunk(100, function ($livros) {
            foreach ($livros as $livro) {
                if (!$livro->preco) continue;

                // Remove vírgulas e pontos e converte
                $raw = str_replace(['.', ','], ['', '.'], $livro->preco);
                $num = floatval($raw) / 100;

                DB::table('livros')
                    ->where('id', $livro->id)
                    ->update(['preco' => number_format($num, 2, '.', '')]);
            }
        });
    }

    public function down(): void
    {
        // Sem retorno — migracao irreversível
    }
};
