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
        Schema::create('aplicaciones_promociones', function (Blueprint $table) {
            //$table->id('id_aplicacion_promocion');

            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_promocion');
            $table->primary(['id_producto','id_promocion']);
            $table->foreign('id_producto')->references('id_producto')->on('productos');
            $table->foreign('id_promocion')->references('id_promocion')->on('promociones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicaciones_promociones');
    }
};
