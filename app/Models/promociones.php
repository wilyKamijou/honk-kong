<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promociones extends Model
{
    use HasFactory;
    protected $primaryKey='id_promocion';
    protected $fillable=
    [
        'nombre',
        'valor',
        'fecha_inicio',
        'fecha_fin'
    ];
    
    public function aplicaciones_promociones()
    {
        return $this->hasMany(aplicaciones_promociones::class, 'id_promocion');
    }
}
