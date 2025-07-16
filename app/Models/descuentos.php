<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class descuentos extends Model
{
    use HasFactory;
    protected $primaryKey='id_descuento';
    protected $fillable=
    [
        'codigo',
        'descripcion',
        'porcentaje',
        'fecha_inicio',
        'fecha_fin'
    ];

    public function aplicaciones_descuentos()
    {
        return $this->hasMany(aplicaciones_descuentos::class, 'id_descuento');
    }
}
