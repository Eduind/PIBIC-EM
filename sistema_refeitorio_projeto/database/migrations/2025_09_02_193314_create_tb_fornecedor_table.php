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
        Schema::create('tb_fornecedor', function (Blueprint $table) {
            $table->id('idFornecedor');
            $table->string('nomeFornecedor',70);
            $table->string('cnpj',14);
            $table->string('email',150);
            $table->string('endereco',500);
            $table->string('telefone',45);
            $table->string('Num_Empenho',100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_fornecedor');
    }
};
