<?php

namespace App\Notifications;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisicaoReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisicao;

    public function __construct(Requisicao $requisicao)
    {
        $this->requisicao = $requisicao;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reminder: Entrega de Livro Amanhã')
            ->line("Tem uma requisição que deve ser entregue amanhã.")
            ->line("Livro: " . $this->requisicao->livro->titulo)
            ->line("Data prevista: " . $this->requisicao->data_prevista->format('d/m/Y'))
            ->action('Ver Requisição', url('/requisicoes/'.$this->requisicao->id))
            ->line('Obrigado por utilizar a Biblioteca!');
    }
}
