<?php

namespace App\Observers;

use App\Models\Requisicao;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class RequisicaoObserver
{
    public function created(Requisicao $requisicao): void
    {
        AuditLogger::log(
            module: 'requisicoes',
            action: 'created',
            objectId: $requisicao->id,
            changes: [
                'livro_id' => $requisicao->livro_id,
                'estado'   => $requisicao->estado,
            ],
            request: request()
        );
    }

    public function updated(Requisicao $requisicao): void
    {
        AuditLogger::log(
            module: 'requisicoes',
            action: 'updated',
            objectId: $requisicao->id,
            changes: $requisicao->getChanges(),
            request: request()
        );
    }

    public function deleted(Requisicao $requisicao): void
    {
        AuditLogger::log(
            module: 'requisicoes',
            action: 'deleted',
            objectId: $requisicao->id,
            changes: null,
            request: request()
        );
    }
}
