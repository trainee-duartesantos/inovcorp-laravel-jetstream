<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Autor;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]

class AutoresManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modalOpen = false;
    public $modalDeleteOpen = false;

    public $autor_id;
    public $nome;
    public $foto;        // ficheiro upload
    public $foto_atual;  // caminho atual no BD

    protected $rules = [
        'nome' => 'required|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    public function resetForm()
    {
        $this->autor_id = null;
        $this->nome = '';
        $this->foto = null;
        $this->foto_atual = null;
    }

    public function store()
    {
        $this->validate();

        $fotoUrl = $this->foto
            ? $this->foto->store('images/autores', 'public')
            : $this->foto_atual;

        $autor = Autor::updateOrCreate(
            ['id' => $this->autor_id],
            [
                'nome' => $this->nome,
                'foto_url' => $fotoUrl,
            ]
        );

        session()->flash(
            'message',
            $this->autor_id
                ? 'Autor atualizado com sucesso!'
                : 'Autor criado com sucesso!'
        );

        $this->closeModal();
    }

    public function edit($id)
    {
        $autor = Autor::findOrFail($id);

        $this->autor_id = $autor->id;
        $this->nome = $autor->nome;
        $this->foto_atual = $autor->foto_url;
        $this->foto = null;

        $this->modalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->autor_id = $id;
        $this->modalDeleteOpen = true;
    }

    public function delete()
    {
        Autor::findOrFail($this->autor_id)->delete();

        $this->modalDeleteOpen = false;
        session()->flash('message', 'Autor apagado com sucesso!');
    }

    public function render()
    {
        $autores = Autor::query()
            ->when($this->search, fn($q) =>
                $q->where('nome', 'like', '%' . $this->search . '%')
            )
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.autores-manager', compact('autores'));
    }

}
