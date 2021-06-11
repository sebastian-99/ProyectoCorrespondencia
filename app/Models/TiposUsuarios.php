<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposUsuarios extends Model
{
    use HasFactory;

    protected $table = 'tipos_usuarios';
    protected $primaryKey = 'idtu';
    protected $fillable = [
        'nombre'
    ];

}
