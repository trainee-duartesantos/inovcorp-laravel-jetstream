<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequisicaoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao'     => 'required|string|min:10|max:2000',
            'categoria_id'  => 'required|integer|exists:categorias,id',
            'prioridade'    => 'required|string|in:baixa,media,alta',
            'data_limite'   => 'nullable|date|after:today',
            'ficheiro'      => 'nullable|file|mimes:png,jpg,pdf,doc,docx,zip|max:4096',
        ];
    }

}
