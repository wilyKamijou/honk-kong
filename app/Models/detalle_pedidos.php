<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detalle_pedidos extends Model
{
    public $incrementing=false;
    protected $primaryKey= ['id_pedido','id_producto'];
    protected $fillable= [
        'cantidad',
        'precio',
        'id_pedido',
        'id_producto'
    ];
    public $timestamps=true;

    public function pedidos()
    {
        return $this->belongsTo(pedidos::class, 'id_pedido');
    }
    
    public function productos()
    {
        return $this->belongsTo(productos::class, 'id_producto');
    }
}
