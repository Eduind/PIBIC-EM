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
        Schema::create('tb_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome_usuario',50);
            $table->string('senha',300);
            $table->string('email',200)->unique();
            $table->string('token',100)->nullable();
            $table->dateTime('email_verified_at')->nullable()->default(null);
            $table->dateTime('last_login_at')->nullable()->default(null);
            $table->boolean('active')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_usuarios');
    }
};
