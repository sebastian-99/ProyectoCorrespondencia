<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiposActividades extends Model
{
    use HasFactory;

    protected $primaryKey = 'idtac';
    protected $table = 'tiposActividades';
    protected $fillable = ['idtac','nombre','activo'];

}
