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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->string('email');
            $table->string('morada');
            $table->string('cidade');
            $table->string('codigo_postal');
            $table->string('telefone')->nullable();
            $table->decimal('total', 8, 2)->default(0);
            $table->enum('status', ['pendente', 'pago'])->default('pendente'); // estado pagamento
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
