<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Economica extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';
    protected $table = 'economicas';

    protected $fillable = [
        'asociado_id',
        'actividad_economica',
        'declara_renta',
        'codigo_ciiu',
        'descripcion_ciiu',
        'ocupacion',
        'cargo',
        'empresa',
        'empresa_id',
        'tipo_empresa',
        'descuento',
        'tipo_contrato',
        'tiempo_actividad',
        'jornada',
        'direccion_empresa',
        'ciudad_empresa',
        'dpto_empresa',
        'telefono_empresa',
        'extension',
        'actividad_secundaria',
        'direccion_secundaria',
        'ciudad_secundaria',
        'dpto_secundaria',
        'telefono_secundaria',
        'descripcion_secundaria',
        'pensionado',
        'entidad_pension',
        'valor_pension',
        'fecha_pension',
        'resolucion_pension',
        'fecha_corte',
        'ingresos_anuales',
        'ingresos_mensuales',
        'egresos_anuales',
        'egresos_mensuales',
        'total_activos',
        'total_pasivos',
        'otros_ingresos',
        'descripcion_ingresos',
    ];

    // Definir la relación uno a uno con el modelo Asociado
    public function asociado()
    {
        return $this->hasOne(Asociado::class);
    }

     // Definir la relación uno a uno con el modelo Empresa
    public function empresas()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
}
