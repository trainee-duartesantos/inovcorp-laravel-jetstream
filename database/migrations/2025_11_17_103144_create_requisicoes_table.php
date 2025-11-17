<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('requisicoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // numeração sequencial (p.ex. R-0001)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('livro_id')->constrained('livros')->onDelete('cascade');
            $table->string('foto_cidadao')->nullable(); // foto no momento (se necessário)
            $table->date('data_requisicao');
            $table->date('data_prevista'); // data_requisicao + 5 dias
            $table->date('data_entrega')->nullable(); // preenchida pelo admin
            $table->enum('estado', ['ativa','entregue','cancelada','atrasada'])->default('ativa');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicoes');
    }
};
