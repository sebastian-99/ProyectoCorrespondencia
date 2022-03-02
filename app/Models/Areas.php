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
        'idtar',
        'activo'
    ];

    public function tipoArea()
    {
        return $this->belongsTo(TiposAreas::class,$this->primaryKey,'idtar');
    }

    public function actividades()
    {
        return $this->hasMany(Actividades::class,'idar_areas');
    }

    public function getPromedioAttribute()
    {
        $areas = Actividades::count('idac');
        return number_format( ($this->actividades()->count()/$areas)*100 ,2);
    }

    public function scopeCosa($query){
        return $query->first();
    }


}
