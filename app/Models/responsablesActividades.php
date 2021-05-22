<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class responsablesActividades extends Model
{
    use HasFactory;

    protected $table = 'responsablesActividades';
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
