<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposAreas;


class TiposAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TiposAreas::create(['nombre' => 'Administrativa']);
        TiposAreas::create(['nombre' => 'Dirección de Carrera']);
    }
}
