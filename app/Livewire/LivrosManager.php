<?php

namespace App\Livewire;

use Livewire\Component;

class LivrosManager extends Component
{
    public function render()
    {
        return view('livewire.livros-manager')
            ->layout('layouts.app');
    }
}
