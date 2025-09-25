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
        Schema::create('tb_lotes', function (Blueprint $table) {
            $table->id('idLotes');
            $table->foreignId('idProdutos')->constrained('tb_produtos','idProdutos')->onDelete('cascade');
            $table->integer('qtdLote');
            $table->date('dataValidade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_lotes');
    }
};
