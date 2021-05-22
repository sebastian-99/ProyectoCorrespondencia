<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividades extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    protected $primaryKey = 'idac';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asunto',
        'descripcion',
        'fecha_creacion',
        'turno',
        'comunicado',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'idtac',
        'idar',
        'idu',
        'status',
        'importancia',
        'activo',
        'archivo1',
        'archivo2',
        'archivo3',
        'link1',
        'link2',
        'link3'

    ];
}
