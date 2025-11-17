<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;  
use App\Jobs\SendRequisicaoReminderJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agenda o JOB todos os dias às 08h
Schedule::job(new SendRequisicaoReminderJob())->dailyAt('08:00');
