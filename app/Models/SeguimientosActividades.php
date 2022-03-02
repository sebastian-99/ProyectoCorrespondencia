<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientosActividades extends Model
{
    use HasFactory;

    protected $table = 'seguimientos_actividades';
    protected $primaryKey = 'idseac';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idreac_seguimientos_actividades',
        'fecha',
        'detalle',
        'porcentaje',
        'estado',
        'archivo_fin'
    ];
}
