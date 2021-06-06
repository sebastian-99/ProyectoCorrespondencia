<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposAreas extends Model
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
        return $this->hasMany(areas::class,'idtar',$this->primaryKey);
    }

}
