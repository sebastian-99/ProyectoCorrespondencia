<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivosSeguimientos extends Model
{
    use HasFactory;

    protected $table = 'archivos_seguimientos';
    protected $primaryKey = 'idarseg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idseac_seguimientos_actividades',
        'nombre',
        'ruta',
        'detalle_a',
    ];
}
