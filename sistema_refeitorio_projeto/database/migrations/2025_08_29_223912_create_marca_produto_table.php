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
        Schema::create('tb_marca_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('tb_produtos','idProdutos')->onDelete('cascade');
            $table->foreignId('marca_id')->constrained('tb_marca','idMarca')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marca_produto');
    }
};
