<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participantes extends Model
{
    use HasFactory;

    protected $table = 'participantes';
    protected $primaryKey = 'id_participantes';
    protected $fillable = [
        'idac',
        'idu',
    ];
}
