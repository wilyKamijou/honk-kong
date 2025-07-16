<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->enum('tipo', ['tarjeta', 'qr', 'efectivo', 'transferencia']);
            $table->string('alias')->nullable();
            
            // Para tarjetas (simulación)
            $table->string('nombre_titular')->nullable();
            $table->string('ultimos_digitos', 4)->nullable();
            $table->string('marca')->nullable(); // visa, mastercard, etc
            $table->string('fecha_expiracion', 5)->nullable(); // MM/YY
            
            // Para QR (simulación)
            $table->string('codigo_qr')->nullable();
            
            $table->boolean('es_predeterminado')->default(false);
            $table->unsignedBigInteger('user_id');
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pagos');
    }
};