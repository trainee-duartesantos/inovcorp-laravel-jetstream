<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Editora;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]

class EditorasManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modalOpen = false;
    public $modalDeleteOpen = false;

    public $editora_id;
    public $nome;
    public $logo;
    public $logo_atual;

    protected $rules = [
        'nome' => 'required|max:255',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    protected $paginationTheme = 'tailwind';

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
        $this->logo = null;
        $this->logo_atual = null;
    }

    public function store()
    {
        $this->validate();

        $logoUrl = $this->logo
            ? $this->logo->store('images/editoras', 'public')
            : $this->logo_atual;

        Editora::updateOrCreate(
            ['id' => $this->editora_id],
            [
                'nome' => $this->nome,      // será encriptado pelo model
                'logo_url' => $logoUrl,
            ]
        );

        session()->flash(
            'message',
            $this->editora_id
                ? 'Editora atualizada com sucesso!'
                : 'Editora criada com sucesso!'
        );

        $this->closeModal();
    }

    public function edit($id)
    {
        $editora = Editora::findOrFail($id);

        $this->editora_id = $editora->id;
        $this->nome = $editora->nome;       // já vem descifrado
        $this->logo_atual = $editora->logo_url;
        $this->logo = null;

        $this->modalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->editora_id = $id;
        $this->modalDeleteOpen = true;
    }

    public function delete()
    {
        Editora::findOrFail($this->editora_id)->delete();

        $this->modalDeleteOpen = false;
        session()->flash('message', 'Editora apagada com sucesso!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $editoras = Editora::query()
            ->when($this->search, fn($q) =>
                $q->where('nome', 'like', '%' . $this->search . '%')
            )
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.editoras-manager', compact('editoras'));
    }

}
