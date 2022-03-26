<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Areas;


class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Areas::create(['idtar' => '1', 'nombre' => 'Administración']);
        Areas::create(['idtar' => '1', 'nombre' => 'Abogacía General e Igualdad de Género']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Actividades Culturales y Deportiva']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Control Escolar']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Educación Continua']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Prensa y Difusión']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Psicopedagogía']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Servicios Bibliotecarios']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Sistemas']);
        Areas::create(['idtar' => '1', 'nombre' => 'Dirección de Administración y Finanzas']);
        Areas::create(['idtar' => '2', 'nombre' => 'Mantenimiento Industrial']);
        Areas::create(['idtar' => '2', 'nombre' => 'Negocios y Gestión Empresarial']);
        Areas::create(['idtar' => '2', 'nombre' => 'Paramédico y Protección Civil']);
        Areas::create(['idtar' => '2', 'nombre' => 'Procesos Alimentarios y Química Área Biotecnología']);
        Areas::create(['idtar' => '2', 'nombre' => 'Salud Pública y Enfermería']);
        Areas::create(['idtar' => '2', 'nombre' => 'Tecnología Ambiental']);
        Areas::create(['idtar' => '2', 'nombre' => 'Tecnologías de la Información y Comunicación']);
        Areas::create(['idtar' => '1', 'nombre' => 'Dirección de Desarrollo y Fortalecimiento Académico']);
        Areas::create(['idtar' => '1', 'nombre' => 'Dirección de Difusión y Extensión Universitaria']);
        Areas::create(['idtar' => '2', 'nombre' => 'Mecatrónica y Sistemas Productivos']);
        Areas::create(['idtar' => '1', 'nombre' => 'Órgano Interno de Control']);
        Areas::create(['idtar' => '1', 'nombre' => 'Planeación Académica']);
        Areas::create(['idtar' => '1', 'nombre' => 'Rectoría']);
        Areas::create(['idtar' => '1', 'nombre' => 'Secretaría Académica']);
        Areas::create(['idtar' => '1', 'nombre' => 'Secretaría de Vinculación']);
        Areas::create(['idtar' => '1', 'nombre' => 'Subdirección de Finanzas']);
        Areas::create(['idtar' => '1', 'nombre' => 'Subdirección de Servicios Educativos']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Desempeño a Egresados']);
        Areas::create(['idtar' => '1', 'nombre' => 'Departamento de Servicios Tecnológicos']);
        Areas::create(['idtar' => '1', 'nombre' => 'Jefatura de Departamento de Educación Continua']);
                               
    }
}
