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
        Schema::create('envios', function (Blueprint $table) {
            $table->id('id_envio');
            $table->string('direccion_envio', 255);
            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamp('fecha_estimada_llegada')->nullable();
            $table->string('metodo_envio', 50);
            $table->string('estado_envio');

            $table->unsignedBigInteger('id_pedido');
            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};
