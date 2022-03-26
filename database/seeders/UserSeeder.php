<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'idtu_tipos_usuarios' => '1',
            'imagen' => 'default.jpg',
            'titulo' => 'Ing.',
            'nombre' => 'Uriel',
            'app' => 'Aguilar',
            'apm' => 'Ortega',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'idar_areas' => '1',
            'activo' => '1'
        ])->assignRole('Administrador');

        User::create([
            'idtu_tipos_usuarios' => '3',
            'imagen' => 'default.jpg',
            'titulo' => 'Maestro.',
            'nombre' => 'Jorge',
            'app' => 'Bernal',
            'apm' => 'García',
            'email' => 'jorge@gmail.com',
            'password' => bcrypt('123456789'),
            'idar_areas' => '23',
            'activo' => '1'
        ])->assignRole('Rector');

        User::create([
            'idtu_tipos_usuarios' => '2',
            'imagen' => 'default.jpg',
            'titulo' => 'Ing.',
            'nombre' => 'Maricela',
            'app' => 'Norman',
            'apm' => 'Osborn',
            'email' => 'maricela@gmail.com',
            'password' => bcrypt('123'),
            'idar_areas' => '17',
            'activo' => '0'
        ])->assignRole('Usuario');

        User::create([
            'idtu_tipos_usuarios' => '4',
            'imagen' => 'default.jpg',
            'titulo' => 'Tsu.',
            'nombre' => 'Victor',
            'app' => 'Lechuga',
            'apm' => 'Libre',
            'email' => 'victor@gmail.com',
            'password' => bcrypt('123'),
            'idar_areas' => '17',
            'activo' => '1'
        ])->assignRole('Asistente');

    //  ---- Usuarios de prueba  ------

    User::create([
        'idtu_tipos_usuarios' => '2',
        'imagen' => 'default.jpg',
        'titulo' => 'Tsu.',
        'nombre' => 'Lizbeth',
        'app' => 'Mendez',
        'apm' => 'Pera',
        'email' => 'liz@gmail.com',
        'password' => bcrypt('123'),
        'idar_areas' => '14',
        'activo' => '1'
    ])->assignRole('Usuario');

    User::create([
        'idtu_tipos_usuarios' => '2',
        'imagen' => 'default.jpg',
        'titulo' => 'Tsu.',
        'nombre' => 'Areli',
        'app' => 'Lucha',
        'apm' => 'Libre',
        'email' => 'are@gmail.com',
        'password' => bcrypt('123'),
        'idar_areas' => '21',
        'activo' => '1'
    ])->assignRole('Usuario');

    User::create([
        'idtu_tipos_usuarios' => '2',
        'imagen' => 'default.jpg',
        'titulo' => 'Tsu.',
        'nombre' => 'Marco',
        'app' => 'Lechuga',
        'apm' => 'Libre',
        'email' => 'marco@gmail.com',
        'password' => bcrypt('123'),
        'idar_areas' => '22',
        'activo' => '1'
    ])->assignRole('Usuario');

    }
}
