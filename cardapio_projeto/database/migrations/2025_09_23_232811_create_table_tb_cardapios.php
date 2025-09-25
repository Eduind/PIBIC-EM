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

        Schema::create('tb_cardapios', function (Blueprint $table) {
            $table->id('idCardapio');
            $table->string('nomeCardapio', 100);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->timestamps();
            $table->softDeletes();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_tb_cardapios');
    }
};
