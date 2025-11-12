<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->text('isbn'); // TEXT para dados cifrados
            $table->string('nome');
            $table->foreignId('editora_id')->constrained()->onDelete('cascade');
            $table->text('bibliografia'); // TEXT para dados cifrados
            $table->text('preco'); // TEXT para dados cifrados
            $table->string('capa')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('livros');
    }
};