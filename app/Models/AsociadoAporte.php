<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsociadoAporte extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';
    protected $table = 'asociado_aportes';

    protected $fillable = [
        'asociado_id',
        'lineaaporte_id',
        'valor_aporte',
    ];

    /**
     * Obtiene el asociado del crédito.
     */
    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }

    /**
     * Obtiene la linea de crédito asociada al crédito.
     */
    public function lineaaporte()
    {
        return $this->belongsTo(LineaCredito::class);
    }
}
