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
        Schema::create('aplicaciones_descuentos', function (Blueprint $table) {
            //$table->id('id_aplicacion');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_descuento');
            $table->primary(['id_pedido','id_descuento']);
            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos');
            $table->foreign('id_descuento')->references('id_descuento')->on('descuentos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicaciones_descuentos');
    }
};
