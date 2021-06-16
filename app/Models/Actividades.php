<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
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
        'fecha_hora_inicio',
        'fecha_hora_fin',
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

    public function area()
    {
        return $this->belongsTo(Areas::class,'idar_areas','idar');
    }

    public function responsables()
    {
        return $this->hasMany(ResponsablesActividades::class,'idac_actividades');
    }

    public function getCompletadasAttribute()
    {
        return $this->responsables()->where('fecha','!=', null)->get();
    }

    public function getEnProcesoAttribute()
    {
        return $this->responsables()->where('fecha','=', null)->get();;
    }

    public function getIncompletasAttribute()
    {
        return $this->where('actividades.fecha_fin','<',Carbon::now()->format('Y-m-d'))
            ->join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->where('responsables_actividades.fecha',null)
            ->where('actividades.idar_areas',$this->area->idar)
            ->get();
    }

    public function getTotalCompletadasAttribute()
    {
        return $this->responsables()->where('fecha','!=', null)->count();
    }

    public function getTotalEnProcesoAttribute()
    {
        return $this->responsables()->where('fecha','=', null)->count();
    }

    public function getTotalIncompletasAttribute()
    {
        return $this->where('actividades.fecha_fin','<',Carbon::now()->format('Y-m-d'))
            ->join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->where('responsables_actividades.fecha',null)
            ->where('actividades.idar_areas',$this->area->idar)
            ->count();
    }


}
