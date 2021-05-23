<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class archivosSeguimientos extends Model
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
        'idseac',
        'nombre',
        'ruta',
        'detalle',
    ];
}
