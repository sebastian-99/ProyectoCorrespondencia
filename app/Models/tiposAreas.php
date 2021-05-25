<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiposAreas extends Model
{
    use HasFactory;

    protected $table = 'tipos_areas';
    protected $primaryKey = 'idtar';
    protected $fillable = [
        'idtar',
        'nombre',
        'activo'
    ];

}
