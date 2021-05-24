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
        //'idtar',
        'nombre',
        'activo'
    ];

    public function areas()
    {
        return $this->hasMany(areas::class,$this->primaryKey,'idar');
    }

}
