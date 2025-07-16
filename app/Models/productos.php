<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos extends Model
{
    use HasFactory;
    protected $primaryKey='id_producto';
    protected $fillable=[
        'nombre',
        'descripcion',
        'precio',
        'imagen_url',
        'id_categoria'
    ];

    public function categorias()    
    {
        return $this->belongsTo(categorias::class, 'id_categoria');
    }
    public function aplicaciones_promociones()
    {
        return $this->hasMany(aplicaciones_promociones::class, 'id_producto');
    }
    public function detalle_pedidos()
    {
        return $this->hasMany(detalle_pedidos::class, 'id_producto');
    }
    public function reseñas ()
{
      return $this->hasMany(Resena::class, 'producto_id', 'id_producto');
}

}
