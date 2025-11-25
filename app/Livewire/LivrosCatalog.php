<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Livro;

class LivrosCatalog extends Component
{
    public $query = '';

    public function render()
    {
        $livros = Livro::with('editora')
            ->where('nome', 'like', '%' . $this->query . '%')
            ->orWhereHas('autores', fn($q) =>
                $q->where('nome', 'like', '%' . $this->query . '%'))
            ->orWhere('isbn', 'like', '%' . $this->query . '%')
            ->orderBy('nome')
            ->get();

        return view('livewire.livros-catalog', [
            'livros' => $livros
        ]);
    }
}
