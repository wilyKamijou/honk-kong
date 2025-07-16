<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pedidos extends Model
{
    use HasFactory;
    protected $primaryKey='id_pedido';
    protected $fillable=
    [
        'user_id',
        'fecha',
        'total',
        'estado',
        'direccion_envio',
        'id_pago'
    ];

    public function envios()
    {
        return $this->hasOne(envios::class, 'id_pedido', 'id_pedido');
    }
    public function detalle_pedido()
    {
        return $this->hasMany(detalle_pedidos::class, 'id_pedido');
    }
    public function aplicaciones_descuentos()
    {
        return $this->hasMany(aplicaciones_descuentos::class, 'id_pedido');
    }
    public function metodos_pagos()
    {
        return $this->belongsTo(metodos_pagos::class, 'id_pago');
    }
    public function users()
    {
        return $this->belongsTo(user::class, 'user_id', 'id');
    }
}
