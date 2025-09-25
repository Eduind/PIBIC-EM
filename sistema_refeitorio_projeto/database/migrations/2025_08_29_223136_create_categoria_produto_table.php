<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tb_categoria_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('tb_produtos','idProdutos')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('tb_categorias','idCategorias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_produto');
    }
};
