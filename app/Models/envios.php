<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class envios extends Model
{
    use HasFactory;
    protected $primaryKey='id_envio';
    protected $fillable=
    [
        'direccion_envio',
        'fecha_envio',
        'fecha_estimada_llegada',
        'metodo_envio',
        'estado_envio',
        'id_pedido'
    ];

    public function pedidos()
    {
        return $this->belongsTo(pedidos::class, 'id_envio');
    }
}
