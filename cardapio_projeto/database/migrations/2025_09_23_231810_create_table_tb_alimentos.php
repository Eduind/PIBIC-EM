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
        Schema::create('tb_alimentos', function (Blueprint $table) {
            $table->id('idAlimento');
            $table->string('nomeAlimento', 150);
            $table->text('ingredientes');

            $table->decimal('calorias', 6, 2);
            $table->decimal('carboidratos', 6, 2);
            $table->decimal('proteinas', 6, 2);
            $table->decimal('gorduras_totais', 6, 2);

            $table->boolean('contem_gluten')->default(false);
            $table->json('alergicos')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_tb_alimentos');
    }
};
