<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Editora;

#[Layout('layouts.admin')]
class EditorasManager extends Component
{
    use WithFileUploads;

    public $modalOpen = false;
    public $modalDeleteOpen = false;

    public $editora_id;
    public $nome;
    public $logotipo;         // novo upload
    public $logotipo_atual;   // caminho já guardado

    protected $rules = [
        'nome' => 'required|string|max:255',
        'logotipo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:4096',
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
        $this->editora_id = null;
        $this->nome = '';
        $this->logotipo = null;
        $this->logotipo_atual = null;
    }

    public function store()
    {
        $this->validate();

        $logoPath = $this->logotipo
            ? $this->logotipo->store('images/editoras', 'public')
            : $this->logotipo_atual;

        $editora = Editora::updateOrCreate(
            ['id' => $this->editora_id],
            [
                'nome' => $this->nome,
                'logotipo' => $logoPath,
            ]
        );

        session()->flash(
            'message',
            $this->editora_id ? 'Editora atualizada com sucesso!' : 'Editora criada com sucesso!'
        );

        $this->closeModal();
    }

    public function edit($id)
    {
        $editora = Editora::findOrFail($id);

        $this->editora_id = $editora->id;
        $this->nome = $editora->nome;
        $this->logotipo_atual = $editora->logotipo;

        $this->modalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->editora_id = $id;
        $this->modalDeleteOpen = true;
    }

    public function delete()
    {
        Editora::find($this->editora_id)?->delete();
        $this->modalDeleteOpen = false;

        session()->flash('message', 'Editora eliminada com sucesso!');
    }

    public function render()
    {
        $editoras = Editora::orderBy('id', 'desc')->get();

        return view('livewire.editoras-manager', compact('editoras'));
    }
}
