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
        Schema::create('tb_saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idLotes')->constrained('tb_lotes','idLotes')->onDelete('cascade');
            $table->date('dataSaida');
            $table->integer('qtdSaida');
            $table->char('destino');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_saidas');
    }
};
