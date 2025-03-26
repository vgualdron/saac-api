<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';
    protected $table = 'activos';

    protected $fillable = [
        'asociado_id',
        'tipo_primer_bien',
        'direccion_primer_bien',
        'valor_primer_bien',
        'hipoteca_primer_bien',
        'saldo_primer_bien',
        'tipo_segundo_bien',
        'direccion_segundo_bien',
        'valor_segundo_bien',
        'hipoteca_segundo_bien',
        'saldo_segundo_bien',
        'tipo_primer_vehiculo',
        'valor_primer_vehiculo',
        'marca_primer_vehiculo',
        'placa_primer_vehiculo',
        'saldo_primer_vehiculo',
        'prenda_primer_vehiculo',
        'tipo_segundo_vehiculo',
        'valor_segundo_vehiculo',
        'marca_segundo_vehiculo',
        'placa_segundo_vehiculo',
        'saldo_segundo_vehiculo',
        'prenda_segundo_vehiculo',
        'descripcion_primer_otrobien',
        'valor_primer_otrobien',
        'saldo_primer_otrobien',
        'prenda_primer_otrobien',
        'descripcion_segundo_otrobien',
        'valor_segundo_otrobien',
        'saldo_segundo_otrobien',
        'prenda_segundo_otrobien',
    ];

    // Definir la relación uno a uno con el modelo Asociado
    public function asociado()
    {
        return $this->hasOne(Asociado::class);
    }
}
