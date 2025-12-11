<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Livro;

class AtualizarPrecosLivros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livros:atualizar-precos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza preços de livros que não possuem valor, atribuindo entre 15€ e 35€';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Livros sem preço ou com preço 0 ou vazio
        $livros = Livro::all()->filter(function($l) {
            return empty($l->preco) || floatval($l->preco) <= 0;
        });

        if ($livros->isEmpty()) {
            $this->info('Todos os livros já têm preço 👍');
            return 0;
        }

        foreach ($livros as $livro) {
            $novoPreco = rand(1500, 3500) / 100; // 15€ a 35€

            $livro->preco = $novoPreco;
            $livro->save();

            $this->info("Livro ID {$livro->id} atualizado para {$novoPreco}€");
        }

        $this->info("✔ Preços atualizados para {$livros->count()} livros!");

        return 0;
    }
}
