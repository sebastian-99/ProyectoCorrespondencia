<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsablesActividades extends Model
{
    use HasFactory;

    protected $table = 'responsables_actividades';
    protected $primaryKey = 'idreac';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idu',
        'idac',
        'firma',
        'acuse',
        'fecha'
    ];

}
