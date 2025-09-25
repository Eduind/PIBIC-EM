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
        Schema::create('tb_produtos', function (Blueprint $table) {
            $table->id('idProdutos');
            $table->string('nomeProduto',70);
            $table->integer('qtdEstoque')->default(0);
            $table->string('UnidadeMedida',45);
            $table->decimal('pesoLiquido', 10, 2)->nullable();
            $table->integer('tamanho');
            $table->integer('ativoCategoria');
            $table->integer('ativoMarca');
            $table->decimal('precoProduto',10,2);
            $table->integer('qtdMinima');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};
