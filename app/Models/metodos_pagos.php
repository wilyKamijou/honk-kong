<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class metodos_pagos extends Model
{
    use HasFactory;

    protected $table = 'metodos_pagos';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'tipo',
        'alias',
        'nombre_titular',
        'ultimos_digitos',
        'marca',
        'fecha_expiracion',
        'codigo_qr',
        'es_predeterminado',
        'user_id'
    ];

    public function pedidos()
    {
        return $this->hasMany(pedidos::class, 'id_pago');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
