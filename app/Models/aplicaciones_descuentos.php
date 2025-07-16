<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aplicaciones_descuentos extends Model
{
    public $incrementing=false;
    protected $primaryKey= null;
    protected $fillable= [
        'id_pedido',
        'id_descuento'
    ];
    public $timestamps=true;

    public function descuento()
    {
        return $this->belongsTo(descuentos::class, 'id_descuento');
    }
    public function pedidos()
    {
        return $this->belongsTo(pedidos::class, 'id_pedido');
    }
}
