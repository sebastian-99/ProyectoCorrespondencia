<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposActividades;


class TiposActSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TiposActividades::create(['nombre' => 'Actividades Académicas']);
        TiposActividades::create(['nombre' => 'Actividades Administrativas']);
        TiposActividades::create(['nombre' => 'Acreditaciones']);
        TiposActividades::create(['nombre' => 'Certificaciones']);
        TiposActividades::create(['nombre' => 'Consejo']);
        TiposActividades::create(['nombre' => 'Direcciones de Área']);
        TiposActividades::create(['nombre' => 'Direcciones de Carrera']);
        TiposActividades::create(['nombre' => 'Rectoria']);
        TiposActividades::create(['nombre' => 'Sociales']);
    }
}
