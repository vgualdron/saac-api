<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';
    protected $table = 'referencias';

    protected $fillable = [
        'asociado_id',
        'referenciap_nombre1',
        'referenciap_relacion1',
        'referenciap_direccion1',
        'referenciap_ciudad1',
        'referenciap_telefono1',
        'referenciap_nombre2',
        'referenciap_relacion2',
        'referenciap_direccion2',
        'referenciap_ciudad2',
        'referenciap_telefono2',
        'referenciac_entidad1',
        'referenciac_tipo1',
        'referenciac_producto1',
        'referenciac_ciudad1',
        'referenciac_telefono1',
        'referenciac_entidad2',
        'referenciac_tipo2',
        'referenciac_producto2',
        'referenciac_ciudad2',
        'referenciac_telefono2',
        'beneficiario_nombre1',
        'beneficiario_documento1',
        'beneficiario_porcentaje1',
        'beneficiario_nacimiento1',
        'beneficiario_parentesco1',
        'beneficiario_nombre2',
        'beneficiario_documento2',
        'beneficiario_porcentaje2',
        'beneficiario_nacimiento2',
        'beneficiario_parentesco2',
        'beneficiario_nombre3',
        'beneficiario_documento3',
        'beneficiario_porcentaje3',
        'beneficiario_nacimiento3',
        'beneficiario_parentesco3',
        //'referido_por',
    ];

    // Definir la relación uno a uno con el modelo Asociado
    public function asociado()
    {
        return $this->hasOne(Asociado::class);
    }
}
