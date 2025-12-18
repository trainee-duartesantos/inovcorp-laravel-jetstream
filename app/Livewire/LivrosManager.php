<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Livro;
use App\Models\Editora;
use App\Models\Autor;

#[Layout('layouts.admin')]

class LivrosManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modalOpen = false;
    public $modalDeleteOpen = false;
    public $disponivel = true;
    public $livro_id;
    public $isbn;
    public $nome;
    public $editora_id;
    public $autores_id = [];
    public $preco;
    public $bibliografia;
    public $capa; // upload file
    public $capa_atual;

    protected $rules = [
        'isbn' => 'required|max:255',
        'nome' => 'required|max:255',
        'editora_id' => 'required|integer',
        'autores_id' => 'required|array|min:1',
        'preco' => 'required|numeric',
        'bibliografia' => 'nullable|string',
        'capa' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
        $this->livro_id = null;
        $this->isbn = '';
        $this->nome = '';
        $this->editora_id = '';
        $this->autores_id = [];
        $this->preco = '';
        $this->bibliografia = '';
        $this->capa = null;
        $this->capa_atual = null;
        $this->disponivel = true;
    }

    public function store()
    {
        $this->validate();

        $capaUrl = $this->capa
            ? $this->capa->store('images/livros', 'public')
            : $this->capa_atual;

        $livro = Livro::updateOrCreate(
            ['id' => $this->livro_id],
            [
                'isbn' => $this->isbn,
                'nome' => $this->nome,
                'editora_id' => $this->editora_id,
                'bibliografia' => $this->bibliografia,
                'preco' => $this->preco,
                'capa_url' => $capaUrl,
                'disponivel' => $this->disponivel,
            ]
        );

        $livro->autores()->sync($this->autores_id);

        session()->flash(
            'message',
            $this->livro_id
                ? 'Livro atualizado com sucesso!'
                : 'Livro criado com sucesso!'
        );

        $this->closeModal();
        $this->reset('capa');
        $this->resetPage();
    }

    public function edit($id)
    {
        $livro = Livro::findOrFail($id);
        $this->livro_id = $id;
        $this->isbn = $livro->isbn;
        $this->nome = $livro->nome;
        $this->editora_id = $livro->editora_id;
        $this->preco = $livro->preco;
        $this->bibliografia = $livro->bibliografia;
        $this->autores_id = $livro->autores->pluck('id')->toArray();
        $this->capa_atual = $livro->capa_url;
        $this->disponivel = $livro->disponivel;
        $this->modalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->livro_id = $id;
        $this->modalDeleteOpen = true;
    }

    public function delete()
    {
        Livro::find($this->livro_id)?->delete();
        $this->modalDeleteOpen = false;
        session()->flash('message', 'Livro apagado com sucesso!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $livros = Livro::with(['editora', 'autores'])
            ->where(function ($query) {
                $query->where('nome', 'LIKE', '%' . $this->search . '%')
                    ->orWhereEncrypted('isbn', 'LIKE', '%' . $this->search . '%')
                    ->orWhereHas('autores', function ($q) {
                        $q->where('nome', 'LIKE', '%' . $this->search . '%');
                    });
            })
            ->paginate(10);

        $editoras = Editora::orderBy('nome')->get();
        $autores  = Autor::orderBy('nome')->get();

        return view('livewire.livros-manager', compact(
            'livros',
            'editoras',
            'autores'
        ));
    }
}
