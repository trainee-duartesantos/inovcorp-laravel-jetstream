<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('editoras', function (Blueprint $table) {
            $table->id();
            $table->text('nome'); // TEXT para dados cifrados
            $table->string('logotipo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('editoras');
    }
};