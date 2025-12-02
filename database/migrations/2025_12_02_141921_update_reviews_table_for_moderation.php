<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->default(0)
                ->after('comment')
                ->comment('0=pending,1=approved,2=rejected');

            $table->text('justification')->nullable()
                ->after('status');

            $table->unsignedBigInteger('requisicao_id')->nullable()
                ->after('livro_id');

            $table->foreign('requisicao_id')
                ->references('id')->on('requisicoes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['requisicao_id']);
            $table->dropColumn(['status','justification','requisicao_id']);
        });
    }
};
