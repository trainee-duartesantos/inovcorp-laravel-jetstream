<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Quem fez a ação
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Onde aconteceu
            $table->string('module'); // ex: livros, requisicoes, users

            // Em que objeto
            $table->unsignedBigInteger('object_id')->nullable();

            // O que aconteceu
            $table->string('action'); // created, updated, deleted, login, etc.

            // Detalhes da alteração
            $table->json('changes')->nullable();

            // Contexto técnico
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();

            // Data + Hora
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
