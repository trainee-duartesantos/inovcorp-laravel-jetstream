<?php

namespace App\Jobs;

use App\Models\Requisicao;
use App\Notifications\RequisicaoReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRequisicaoReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $amanha = now()->addDay()->toDateString();

        $requisicoes = Requisicao::whereNull('data_entrega')
            ->whereDate('data_prevista', $amanha)
            ->get();

        foreach ($requisicoes as $req) {
            $req->user->notify(new RequisicaoReminderNotification($req));
        }
    }
}
