<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_reseña';
    protected $table = 'resenas';
    protected $fillable = ['comentario', 'calificacion', 'fecha', 'user_id', 'producto_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Get the product associated with the review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(productos::class, 'producto_id', 'id_producto');
    }
}