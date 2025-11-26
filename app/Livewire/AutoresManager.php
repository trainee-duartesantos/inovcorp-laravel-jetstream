<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Autor;

#[Layout('layouts.admin')]
class AutoresManager extends Component
{
    use WithFileUploads;

    public $modalOpen = false;
    public $modalDeleteOpen = false;

    public $autor_id;
    public $nome;
    public $foto;          // novo upload
    public $foto_atual;    // caminho já guardado
    public $search = '';

    protected $rules = [
        'nome' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

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

        // Se houve upload novo, guardamos. Caso contrário, mantemos a foto atual.
        $fotoPath = $this->foto
            ? $this->foto->store('images/autores', 'public')
            : $this->foto_atual;

        $autor = Autor::updateOrCreate(
            ['id' => $this->autor_id],
            [
                // O nome será cifrado pelo mutator do modelo
                'nome' => $this->nome,
                'foto' => $fotoPath,
            ]
        );

        session()->flash(
            'message',
            $this->autor_id ? 'Autor atualizado com sucesso!' : 'Autor criado com sucesso!'
        );

        $this->closeModal();
    }

    public function edit($id)
    {
        $autor = Autor::findOrFail($id);

        $this->autor_id = $autor->id;
        $this->nome = $autor->nome;        // já vem descifrado pelo accessor
        $this->foto_atual = $autor->foto;

        $this->modalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->autor_id = $id;
        $this->modalDeleteOpen = true;
    }

    public function delete()
    {
        Autor::find($this->autor_id)?->delete();
        $this->modalDeleteOpen = false;

        session()->flash('message', 'Autor eliminado com sucesso!');
    }

    public function render()
    {
        $autores = \App\Models\Autor::query()
            ->when($this->search, function ($query) {
                $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.autores-manager', [
            'autores' => $autores,
        ]);
    }

}
