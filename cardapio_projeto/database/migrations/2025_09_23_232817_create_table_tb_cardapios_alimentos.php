<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_cardapio_refeicoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cardapio_id')->constrained('tb_cardapios','idCardapio')->onDelete('cascade');
            $table->foreignId('alimento_id')->constrained('tb_alimentos','idAlimento')->onDelete('cascade');

            $table->enum('dia_semana', [
                'segunda',
                'terca',
                'quarta',
                'quinta',
                'sexta',
                'sabado',
            ]);

            $table->enum('horario', [
                'matutino',
                'almoco',
                'vespertino',
                'noturno'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_refeicoes');
    }
};
