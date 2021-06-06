<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    use HasFactory;

    protected $table = 'areas';
    protected $primaryKey = 'idar';
    protected $fillable = [
        'nombre',
        'idtar'
    ];

    public function tipoArea()
    {
        return $this->belongsTo(tiposAreas::class,$this->primaryKey,'idtar');
    }

    public function actividades()
    {
        return $this->hasMany(actividades::class,'idar_areas');
    }

    public function getPromedioAttribute()
    {
        $areas = actividades::count('idac');
        return number_format( ($this->actividades()->count()/$areas)*100 ,2);
    }

}
