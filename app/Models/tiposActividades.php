<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposActividades extends Model
{
    use HasFactory;

    protected $table = 'tipos_actividades';
    protected $primaryKey = 'idtac';
    protected $fillable = [
        'nombre',
        'activo'
    ];
}
