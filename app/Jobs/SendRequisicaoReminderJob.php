<?php

namespace App\Jobs;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RequisicaoReminderMail;

class SendRequisicaoReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $amanha = now()->addDay()->startOfDay();

        $requisicoes = Requisicao::where('estado', 'ativa')
            ->whereDate('data_prevista', $amanha)
            ->with(['user', 'livro'])
            ->get();

        foreach ($requisicoes as $req) {
            Mail::to($req->user->email)
                ->send(new RequisicaoReminderMail($req));
        }

        Log::info("Lembretes enviados: {$requisicoes->count()} requisições");
    }
}
