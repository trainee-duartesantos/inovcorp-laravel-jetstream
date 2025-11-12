<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ADICIONAR APENAS SE NÃO EXISTIR - MUITO SEGURO
        if (!Schema::hasColumn('livros', 'capa_url')) {
            Schema::table('livros', function (Blueprint $table) {
                $table->string('capa_url')->nullable()->after('preco');
            });
            echo "✅ Coluna capa_url adicionada à tabela livros\n";
        } else {
            echo "ℹ️ Coluna capa_url já existe na tabela livros\n";
        }

        if (!Schema::hasColumn('autors', 'foto_url')) {
            Schema::table('autors', function (Blueprint $table) {
                $table->string('foto_url')->nullable()->after('nome');
            });
            echo "✅ Coluna foto_url adicionada à tabela autors\n";
        } else {
            echo "ℹ️ Coluna foto_url já existe na tabela autors\n";
        }

        if (!Schema::hasColumn('editoras', 'logo_url')) {
            Schema::table('editoras', function (Blueprint $table) {
                $table->string('logo_url')->nullable()->after('nome');
            });
            echo "✅ Coluna logo_url adicionada à tabela editoras\n";
        } else {
            echo "ℹ️ Coluna logo_url já existe na tabela editoras\n";
        }
    }

    public function down()
    {
        // REMOVER APENAS SE EXISTIR
        if (Schema::hasColumn('livros', 'capa_url')) {
            Schema::table('livros', function (Blueprint $table) {
                $table->dropColumn('capa_url');
            });
        }

        if (Schema::hasColumn('autors', 'foto_url')) {
            Schema::table('autors', function (Blueprint $table) {
                $table->dropColumn('foto_url');
            });
        }

        if (Schema::hasColumn('editoras', 'logo_url')) {
            Schema::table('editoras', function (Blueprint $table) {
                $table->dropColumn('logo_url');
            });
        }
    }
};