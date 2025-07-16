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
        Schema::create('resenas', function (Blueprint $table) {
            $table->id('id_resena');
            
            // Relación con usuarios
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Relación con productos (nota el nombre de la clave foránea)
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
                  ->references('id_producto')
                  ->on('productos')
                  ->onDelete('cascade');
            
            // Contenido de la reseña
            $table->text('comentario');
            $table->integer('calificacion')
                  ->check('calificacion >= 1 AND calificacion <= 5');
            
            // Metadata
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('user_id');
            $table->index('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};