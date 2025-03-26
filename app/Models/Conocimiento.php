<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conocimiento extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';
    protected $table = 'conocimientos';

    protected $fillable = [
        'asociado_id',
        'politica_expuesta',
        'indique_politica_expuesta',
        'representa_ong',
        'indique_representa_ong',
        'persona_publica',
        'indique_persona_publica',
        'movimiento_politico',
        'indique_movimiento_politico',
        'administra_publico',
        'indique_administra_publico',
        'tributa_otro_pais',
        'indique_tributa_otro_pais',
        'vinculo_pep',
        'indique_vinculo_pep',
        'vinculo1',
        'nombre_vinculo1',
        'tipodoc_vinculo1',
        'numero_vinculo1',
        'nacionalidad_vinculo1',
        'entidad_vinculo1',
        'cargo_vinculo1',
        'fecha_vinculo1',
        'vinculo2',
        'nombre_vinculo2',
        'tipodoc_vinculo2',
        'numero_vinculo2',
        'nacionalidad_vinculo2',
        'entidad_vinculo2',
        'cargo_vinculo2',
        'fecha_vinculo2',
        'operaciones_extranjeras',
        'tipo_operaciones',
        'cuentas_extranjeras',
        'numero_cuenta',
        'entidad_cuenta',
        'moneda',
        'monto',
        'ciudad_operaciones',
        'pais',
    ];

    // Definir la relación uno a uno con el modelo Asociado
    public function asociado()
    {
        return $this->hasOne(Asociado::class);
    }
}
