<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiposUsuarios extends Model
{
    use HasFactory;

    protected $primaryKey = 'idtu';
    protected $table = 'tiposUsuarios';
    protected $fillable = ['idtu', 'nombre'];

}
