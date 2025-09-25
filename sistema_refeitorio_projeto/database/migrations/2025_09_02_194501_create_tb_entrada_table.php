<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_entrada', function (Blueprint $table) {
            $table->id('idEntradas');
            $table->foreignId('idFornecedor')->constrained('tb_fornecedor','idFornecedor')->onDelete('cascade');
            $table->foreignId('idLotes')->constrained('tb_lotes','idLotes')->onDelete('cascade');
            $table->date('dataEntrada');
            $table->integer('numeroNotaFiscal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_entrada');
    }
};
