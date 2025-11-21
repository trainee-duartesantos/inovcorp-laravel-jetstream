<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequisicaoCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requisicao;

    public function __construct(Requisicao $requisicao)
    {
        $this->requisicao = $requisicao;
    }

    public function build()
    {
        return $this->subject("📚 Requisição #{$this->requisicao->numero}")
            ->markdown('emails.requisicoes.created')
            ->with(['requisicao' => $this->requisicao]);
    }
}
