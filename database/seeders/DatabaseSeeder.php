<?php

namespace Database\Seeders;

use App\Models\Areas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\TiposActividades;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$faker = Faker::create();
        $this->call(TiposActSeeder::class);
        $this->call(TiposAreasSeeder::class);
        $this->call(AreasSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        
        /**
       *foreach (range(1,100) as $index) {
       *    DB::table('actividades')->insert([
       *         'asunto' => $faker->paragraph,
       *        'descripcion' => $faker->paragraph,
       *         'fecha_creacion' => $faker->dateTimeBetween('now', '+1 month'),
       *         'turno' => $faker->randomDigit,
       *         'comunicado' => $faker->paragraph,
       *         'fecha_hora_inicio' => $faker->dateTimeBetween('+1 week', '+1 month'),
       *         'fecha_hora_fin' => $faker->dateTimeBetween('+1 month', '+2 months'),
       *         'idtac_tipos_actividades' => $faker->numberBetween(1,3),
       *         'idar_areas' => $faker->numberBetween(1,4),
       *         'idu_users' => $faker->numberBetween(1,2),
       *        
       *     ]);
       * }
        */
    }
}
