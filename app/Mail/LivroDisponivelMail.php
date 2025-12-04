<?php

namespace App\Mail;

use App\Models\Livro;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LivroDisponivelMail extends Mailable
{
    use Queueable, SerializesModels;

    public $livro;

    public function __construct(Livro $livro)
    {
        $this->livro = $livro;
    }

    public function build()
    {
        return $this->subject('📚 O livro que pediu já está disponível!')
                    ->view('emails.livro-disponivel');
    }
}
